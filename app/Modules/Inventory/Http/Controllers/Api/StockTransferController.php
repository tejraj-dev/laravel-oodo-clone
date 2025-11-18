<?php

namespace App\Modules\Inventory\Http\Controllers\Api;

use App\Modules\Inventory\Models\StockTransfer;
use App\Modules\Inventory\Http\Resources\StockTransferResource;
use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockTransferController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $query = StockTransfer::query();

        // Add company scoping if model has company_id
        if (method_exists(StockTransfer::class, 'company')) {
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

        return $this->paginatedResponse($data, StockTransferResource::class);
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
        if (method_exists(StockTransfer::class, 'company')) {
            $validated['company_id'] = $request->user()->company_id;
        }

        $record = StockTransfer::create($validated);

        return $this->successResponse(
            new StockTransferResource($record),
            'StockTransfer created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $query = StockTransfer::query();

        // Add company scoping if model has company_id
        if (method_exists(StockTransfer::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        return $this->successResponse(new StockTransferResource($record));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $query = StockTransfer::query();

        // Add company scoping if model has company_id
        if (method_exists(StockTransfer::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        $validated = $request->validate([
            // Add validation rules
        ]);

        $record->update($validated);

        return $this->successResponse(
            new StockTransferResource($record),
            'StockTransfer updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $query = StockTransfer::query();

        // Add company scoping if model has company_id
        if (method_exists(StockTransfer::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);
        $record->delete();

        return $this->successResponse(null, 'StockTransfer deleted successfully');
    }
}
