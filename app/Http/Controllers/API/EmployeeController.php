<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Requests\EmployeeRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $id         = $request->input('id');
        $name       = $request->input('name');
        $email      = $request->input('email');
        $age        = $request->input('age');
        $phone      = $request->input('phone');
        $team_id    = $request->input('team_id');
        $role_id    = $request->input('role_id');
        $limit      = $request->input('limit', 10);

        // init query
        $employeeQuery = Employee::query();

        if ($id) {
            // filter with id
            $employee = $employeeQuery->with('Team:id,name,icon', 'Role:id,name')->find($id);

            if ($employee) {
                return ApiFormatter::success($employee, 'Employee Found');
            }

            return ApiFormatter::error('Employee not found', 404);
        }

        $employees = $employeeQuery;

        // filter with name
        if ($name) {
            $employees->where('name', 'like', '%' . $name . '%');
        }

        // filter with email
        if ($email) {
            $employees->where('email', $email);
        }

        // filter with age
        if ($age) {
            $employees->where('age', $age);
        }

        // filter with age
        if ($age) {
            $employees->where('age', $age);
        }

        // filter with phone
        if ($phone) {
            $employees->where('phone', 'like', '%' . $phone . '%');
        }

        // filter with role_id
        if ($role_id) {
            $employees->where('role_id', $role_id);
        }

        // filter with team_id
        if ($team_id) {
            $employees->where('team_id', $team_id);
        }

        return ApiFormatter::success($employees->paginate($limit), 'List Employees');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        $data = $request->validated();

        try {
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('public/photos');
            }

            $employee = Employee::create($data);

            // load related data
            $employee->load('Team:id,name,icon', 'Role:id,name');

            return ApiFormatter::success($employee, 'Employee Created', 201);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, string $id)
    {
        $data = $request->validated();

        $employee = Employee::find($id);

        if (!$employee) {
            throw new Exception('Employee not found');
        }

        try {
            if ($request->hasFile('photo')) {
                if ($employee->photo) {
                    Storage::delete($employee->photo);
                }

                $data['photo'] = $request->file('photo')->store('public/photos');
            }

            $employee->update($data);

            // load related data
            $employee->load('Team:id,name,icon', 'Role:id,name');

            return ApiFormatter::success($employee, 'Employee Updated', 200);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage(), 500);
        }
    }

    public function destroy(string $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            throw new Exception('Employee not found');
        }

        try {
            if ($employee->photo) {
                Storage::delete($employee->photo);
            }

            $employee->delete();

            return ApiFormatter::success($employee, 'Employee Deleted', 200);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage(), 500);
        }
    }
}
