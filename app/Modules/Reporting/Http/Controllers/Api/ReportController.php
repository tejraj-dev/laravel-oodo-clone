<?php

namespace App\Modules\Reporting\Http\Controllers\Api;

use App\Modules\Reporting\Models\Report;
use App\Modules\Reporting\Http\Resources\ReportResource;
use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $query = Report::query();

        if (method_exists(Report::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate($perPage);

        return $this->paginatedResponse($data, ReportResource::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        if (method_exists(Report::class, 'company')) {
            $validated['company_id'] = $request->user()->company_id;
        }

        $validated['user_id'] = $request->user()->id;

        $record = Report::create($validated);

        return $this->successResponse(
            new ReportResource($record),
            'Report created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $query = Report::query();

        if (method_exists(Report::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        return $this->successResponse(new ReportResource($record));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $query = Report::query();

        if (method_exists(Report::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|max:255',
        ]);

        $record->update($validated);

        return $this->successResponse(
            new ReportResource($record),
            'Report updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $query = Report::query();

        if (method_exists(Report::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);
        $record->delete();

        return $this->successResponse(null, 'Report deleted successfully');
    }
}
