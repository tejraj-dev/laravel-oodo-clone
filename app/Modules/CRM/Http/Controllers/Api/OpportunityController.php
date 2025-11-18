<?php

namespace App\Modules\CRM\Http\Controllers\Api;

use App\Modules\CRM\Models\Opportunity;
use App\Modules\CRM\Http\Resources\OpportunityResource;
use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OpportunityController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $query = Opportunity::query();

        // Add company scoping if model has company_id
        if (method_exists(Opportunity::class, 'company')) {
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

        return $this->paginatedResponse($data, OpportunityResource::class);
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
        if (method_exists(Opportunity::class, 'company')) {
            $validated['company_id'] = $request->user()->company_id;
        }

        $record = Opportunity::create($validated);

        return $this->successResponse(
            new OpportunityResource($record),
            'Opportunity created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $query = Opportunity::query();

        // Add company scoping if model has company_id
        if (method_exists(Opportunity::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        return $this->successResponse(new OpportunityResource($record));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $query = Opportunity::query();

        // Add company scoping if model has company_id
        if (method_exists(Opportunity::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        $validated = $request->validate([
            // Add validation rules
        ]);

        $record->update($validated);

        return $this->successResponse(
            new OpportunityResource($record),
            'Opportunity updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $query = Opportunity::query();

        // Add company scoping if model has company_id
        if (method_exists(Opportunity::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);
        $record->delete();

        return $this->successResponse(null, 'Opportunity deleted successfully');
    }
}
