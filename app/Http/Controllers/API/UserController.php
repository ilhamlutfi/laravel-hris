<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            // validation
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // verified credentials
            $credentials = request(['email', 'password']);
            if (!auth()->attempt($credentials)) {
                return ApiFormatter::error('Unauthorized Credentials', 401);
            }

            // get user by email
            $user = User::where('email', $request->email)->first();

            // check password
            if (!Hash::check($request->password, $user->password, [])) {
                throw new Exception('Invalid Password');
            }

            // generate token
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            // return ApiFormatter::success([
            //     'token' => $tokenResult,
            //     'token_type' => 'Bearer',
            //     'user' => $user
            // ], 'Login Success');

            return response()->json([
                'token' => $tokenResult,
            ]);
        } catch (Exception $e) {
            return ApiFormatter::error($e->getMessage());
        }
    }

    public function register(Request $request)
    {
        try {
            // validation
            $request->validate([
                'name' => 'required|min:3|max:125',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|min:8|max:255'
            ]);

            // create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // generate token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            // return response
            return ApiFormatter::success([
                'token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Register Success');
        } catch (Exception $e) {
            return ApiFormatter::error($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        // revoke token
        $token = $request->user()->currentAccessToken()->delete();

        return ApiFormatter::success($token, 'Logout Success. Token Revoked');
    }

    public function fetch(Request $request)
    {
        $user = $request->user();

        return ApiFormatter::success($user, 'Fetch User Success');
    }
}
