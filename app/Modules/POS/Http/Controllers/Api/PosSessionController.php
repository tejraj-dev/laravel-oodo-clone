<?php

namespace App\Modules\POS\Http\Controllers\Api;

use App\Modules\POS\Models\PosSession;
use App\Modules\POS\Http\Resources\PosSessionResource;
use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PosSessionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $query = PosSession::query();

        // Add company scoping if model has company_id
        if (method_exists(PosSession::class, 'company')) {
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

        return $this->paginatedResponse($data, PosSessionResource::class);
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
        if (method_exists(PosSession::class, 'company')) {
            $validated['company_id'] = $request->user()->company_id;
        }

        $record = PosSession::create($validated);

        return $this->successResponse(
            new PosSessionResource($record),
            'PosSession created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $query = PosSession::query();

        // Add company scoping if model has company_id
        if (method_exists(PosSession::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        return $this->successResponse(new PosSessionResource($record));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $query = PosSession::query();

        // Add company scoping if model has company_id
        if (method_exists(PosSession::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        $validated = $request->validate([
            // Add validation rules
        ]);

        $record->update($validated);

        return $this->successResponse(
            new PosSessionResource($record),
            'PosSession updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $query = PosSession::query();

        // Add company scoping if model has company_id
        if (method_exists(PosSession::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);
        $record->delete();

        return $this->successResponse(null, 'PosSession deleted successfully');
    }
}
