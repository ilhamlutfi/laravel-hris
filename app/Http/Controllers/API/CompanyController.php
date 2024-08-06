<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        // relation by logged in users
        $companyQuery = Company::with('Users:id,name,email')->whereHas('Users', function ($query) {
            $query->where('user_id', auth()->user()->id);
        });

        if ($id) {
            $company = $companyQuery->find($id);

            if ($company) {
                return ApiFormatter::success($company, 'Company Found');
            }

            return ApiFormatter::error('Company not found', 404);
        }

        // get relation by logged in users
        $companies = $companyQuery;

        if ($name) {
            $companies->where('name', 'like', '%' . $name . '%');
        }

        return ApiFormatter::success($companies->paginate($limit), 'List Companies');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request)
    {
        $data = $request->validated();

        try {
            if ($request->hasFile('logo')) {
                $data['logo'] = $request->file('logo')->store('public/logos');
            }

            $company = Company::create($data);

            // add user to company
            $user = User::findOrFail(auth()->user()->id);
            $user->Companies()->attach($company->id);

            // load users at company
            $company->load('Users:id,name,email');

            return ApiFormatter::success($company, 'Company Created', 201);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request, string $id)
    {
        $data = $request->validated();

        $company = Company::find($id);

        if (!$company) {
            throw new Exception('Company not found');
        }

        try {
            if ($request->hasFile('logo')) {
                if ($company->logo) {
                    Storage::delete($company->logo);
                }

                $data['logo'] = $request->file('logo')->store('public/logos');
            }

            $company->update($data);

            return ApiFormatter::success($company, 'Company Updated', 200);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
