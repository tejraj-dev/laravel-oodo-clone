<?php

namespace App\Modules\Reporting\Http\Controllers\Api;

use App\Modules\Reporting\Models\Dashboard;
use App\Modules\Reporting\Http\Resources\DashboardResource;
use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $query = Dashboard::query();

        if (method_exists(Dashboard::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate($perPage);

        return $this->paginatedResponse($data, DashboardResource::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        if (method_exists(Dashboard::class, 'company')) {
            $validated['company_id'] = $request->user()->company_id;
        }

        $validated['user_id'] = $request->user()->id;

        $record = Dashboard::create($validated);

        return $this->successResponse(
            new DashboardResource($record),
            'Dashboard created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $query = Dashboard::query();

        if (method_exists(Dashboard::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        return $this->successResponse(new DashboardResource($record));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $query = Dashboard::query();

        if (method_exists(Dashboard::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|max:255',
        ]);

        $record->update($validated);

        return $this->successResponse(
            new DashboardResource($record),
            'Dashboard updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $query = Dashboard::query();

        if (method_exists(Dashboard::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);
        $record->delete();

        return $this->successResponse(null, 'Dashboard deleted successfully');
    }
}
