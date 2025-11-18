<?php

namespace App\Modules\POS\Http\Controllers\Api;

use App\Modules\POS\Models\PosPayment;
use App\Modules\POS\Http\Resources\PosPaymentResource;
use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PosPaymentController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $query = PosPayment::query();

        // Add company scoping if model has company_id
        if (method_exists(PosPayment::class, 'company')) {
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

        return $this->paginatedResponse($data, PosPaymentResource::class);
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
        if (method_exists(PosPayment::class, 'company')) {
            $validated['company_id'] = $request->user()->company_id;
        }

        $record = PosPayment::create($validated);

        return $this->successResponse(
            new PosPaymentResource($record),
            'PosPayment created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $query = PosPayment::query();

        // Add company scoping if model has company_id
        if (method_exists(PosPayment::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        return $this->successResponse(new PosPaymentResource($record));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $query = PosPayment::query();

        // Add company scoping if model has company_id
        if (method_exists(PosPayment::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        $validated = $request->validate([
            // Add validation rules
        ]);

        $record->update($validated);

        return $this->successResponse(
            new PosPaymentResource($record),
            'PosPayment updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $query = PosPayment::query();

        // Add company scoping if model has company_id
        if (method_exists(PosPayment::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);
        $record->delete();

        return $this->successResponse(null, 'PosPayment deleted successfully');
    }
}
