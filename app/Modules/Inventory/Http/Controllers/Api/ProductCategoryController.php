<?php

namespace App\Modules\Inventory\Http\Controllers\Api;

use App\Modules\Inventory\Models\ProductCategory;
use App\Modules\Inventory\Http\Resources\ProductCategoryResource;
use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $query = ProductCategory::query();

        // Add company scoping if model has company_id
        if (method_exists(ProductCategory::class, 'company')) {
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

        return $this->paginatedResponse($data, ProductCategoryResource::class);
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
        if (method_exists(ProductCategory::class, 'company')) {
            $validated['company_id'] = $request->user()->company_id;
        }

        $record = ProductCategory::create($validated);

        return $this->successResponse(
            new ProductCategoryResource($record),
            'ProductCategory created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $query = ProductCategory::query();

        // Add company scoping if model has company_id
        if (method_exists(ProductCategory::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        return $this->successResponse(new ProductCategoryResource($record));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $query = ProductCategory::query();

        // Add company scoping if model has company_id
        if (method_exists(ProductCategory::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        $validated = $request->validate([
            // Add validation rules
        ]);

        $record->update($validated);

        return $this->successResponse(
            new ProductCategoryResource($record),
            'ProductCategory updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $query = ProductCategory::query();

        // Add company scoping if model has company_id
        if (method_exists(ProductCategory::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);
        $record->delete();

        return $this->successResponse(null, 'ProductCategory deleted successfully');
    }
}
