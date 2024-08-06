<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);
        $with_responsibility = $request->input('with_responsibility');

        // init query
        $roleQuery = Role::query()->with('Responsibilities:id,role_id,name');

        if ($id) {
            // filter with id
            $role = $roleQuery->find($id);

            if ($role) {
                return ApiFormatter::success($role, 'Role Found');
            }

            return ApiFormatter::error('Role not found', 404);
        }

        // filter with company_id
        $roles = $roleQuery->where('company_id', $request->company_id);

        // filter with name
        if ($name) {
            $roles->where('name', 'like', '%' . $name . '%');
        }

        // get with responsibility
        if ($with_responsibility) {
            $roles->with('Responsibilities');
        }

        return ApiFormatter::success($roles->paginate($limit), 'List Roles');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        $data = $request->validated();

        try {
            $role = Role::create($data);

            // load companies at roles
            $role->load('Company');

            return ApiFormatter::success($role, 'Role Created', 201);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        $data = $request->validated();

        $role = Role::find($id);

        if (!$role) {
            throw new Exception('Role not found');
        }

        try {
            $role->update($data);

            return ApiFormatter::success($role, 'Role Updated', 200);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage(), 500);
        }
    }

    public function destroy(string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            throw new Exception('Role not found');
        }

        try {
            $role->delete();

            return ApiFormatter::success($role, 'Role Deleted', 200);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage(), 500);
        }
    }
}
