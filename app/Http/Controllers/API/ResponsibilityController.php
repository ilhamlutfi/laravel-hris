<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Responsibility;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Requests\ResponsibilityRequest;
use App\Http\Controllers\Controller;

class ResponsibilityController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        // init query
        $responsibilityQuery = Responsibility::query();

        if ($id) {
            // filter with id
            $responsibility = $responsibilityQuery->find($id);

            if ($responsibility) {
                return ApiFormatter::success($responsibility, 'Responsibility Found');
            }

            return ApiFormatter::error('Responsibility not found', 404);
        }

        // filter with role_id
        $responsibilities = $responsibilityQuery->where('role_id', $request->role_id);

        // filter with name
        if ($name) {
            $responsibilities->where('name', 'like', '%' . $name . '%');
        }

        return ApiFormatter::success($responsibilities->paginate($limit), 'List Responsibilities');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResponsibilityRequest $request)
    {
        $data = $request->validated();

        try {
            $responsibility = Responsibility::create($data);

            // load roles at responsibility
            $responsibility->load('Role');

            return ApiFormatter::success($responsibility, 'Responsibility Created', 201);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResponsibilityRequest $request, string $id)
    {
        $data = $request->validated();

        $responsibility = Responsibility::find($id);

        if (!$responsibility) {
            throw new Exception('Responsibility not found');
        }

        try {
            $responsibility->update($data);

            return ApiFormatter::success($responsibility, 'Responsibility Updated', 200);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage(), 500);
        }
    }

    public function destroy(string $id)
    {
        $responsibility = Responsibility::find($id);

        if (!$responsibility) {
            throw new Exception('Responsibility not found');
        }

        try {
            $responsibility->delete();

            return ApiFormatter::success($responsibility, 'Responsibility Deleted', 200);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage(), 500);
        }
    }
}
