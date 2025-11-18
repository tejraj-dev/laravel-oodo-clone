<?php

namespace App\Http\Controllers\Api\V1\Projects;

use App\Modules\Projects\Models\Timesheet;
use App\Http\Resources\V1\Projects\TimesheetResource;
use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimesheetController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $query = Timesheet::query();

        // Add company scoping if model has company_id
        if (method_exists(Timesheet::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        // Add search if provided
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
                // Add more searchable fields as needed
            });
        }

        $data = $query->paginate($perPage);

        return $this->paginatedResponse($data, TimesheetResource::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Add validation rules
        ]);

        // Add company_id if model has it
        if (method_exists(Timesheet::class, 'company')) {
            $validated['company_id'] = $request->user()->company_id;
        }

        $record = Timesheet::create($validated);

        return $this->successResponse(
            new TimesheetResource($record),
            'Timesheet created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $query = Timesheet::query();

        // Add company scoping if model has company_id
        if (method_exists(Timesheet::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        return $this->successResponse(new TimesheetResource($record));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $query = Timesheet::query();

        // Add company scoping if model has company_id
        if (method_exists(Timesheet::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        $validated = $request->validate([
            // Add validation rules
        ]);

        $record->update($validated);

        return $this->successResponse(
            new TimesheetResource($record),
            'Timesheet updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $query = Timesheet::query();

        // Add company scoping if model has company_id
        if (method_exists(Timesheet::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);
        $record->delete();

        return $this->successResponse(null, 'Timesheet deleted successfully');
    }
}
