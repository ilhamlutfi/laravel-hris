<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Requests\TeamRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        // init query
        $teamQuery = Team::query();

        if ($id) {
            // filter with id
            $team = $teamQuery->find($id);

            if ($team) {
                return ApiFormatter::success($team, 'Team Found');
            }

            return ApiFormatter::error('Team not found', 404);
        }

        // filter with company_id
        $teams = $teamQuery->where('company_id', $request->company_id);

        // filter with name
        if ($name) {
            $teams->where('name', 'like', '%' . $name . '%');
        }

        return ApiFormatter::success($teams->paginate($limit), 'List Teams');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeamRequest $request)
    {
        $data = $request->validated();

        try {
            if ($request->hasFile('icon')) {
                $data['icon'] = $request->file('icon')->store('public/icons');
            }

            $team = Team::create($data);

            // load users at team
            $team->load('Company');

            return ApiFormatter::success($team, 'Team Created', 201);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamRequest $request, string $id)
    {
        $data = $request->validated();

        $team = Team::find($id);

        if (!$team) {
            throw new Exception('Team not found');
        }

        try {
            if ($request->hasFile('icon')) {
                if ($team->icon) {
                    Storage::delete($team->icon);
                }

                $data['icon'] = $request->file('icon')->store('public/icons');
            }

            $team->update($data);

            return ApiFormatter::success($team, 'Team Updated', 200);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage(), 500);
        }
    }

    public function destroy(string $id)
    {
        $team = Team::find($id);

        if (!$team) {
            throw new Exception('Team not found');
        }

        try {
            if ($team->icon) {
                Storage::delete($team->icon);
            }

            $team->delete();

            return ApiFormatter::success($team, 'Team Deleted', 200);
        } catch (Exception $th) {
            return ApiFormatter::error($th->getMessage(), 500);
        }
    }
}
