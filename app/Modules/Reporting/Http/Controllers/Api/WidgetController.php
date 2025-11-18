<?php

namespace App\Modules\Reporting\Http\Controllers\Api;

use App\Modules\Reporting\Models\Widget;
use App\Modules\Reporting\Http\Resources\WidgetResource;
use App\Http\Controllers\Api\V1\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WidgetController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $query = Widget::query();

        if (method_exists(Widget::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $data = $query->paginate($perPage);

        return $this->paginatedResponse($data, WidgetResource::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        if (method_exists(Widget::class, 'company')) {
            $validated['company_id'] = $request->user()->company_id;
        }

        $validated['user_id'] = $request->user()->id;

        $record = Widget::create($validated);

        return $this->successResponse(
            new WidgetResource($record),
            'Widget created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $query = Widget::query();

        if (method_exists(Widget::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        return $this->successResponse(new WidgetResource($record));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $query = Widget::query();

        if (method_exists(Widget::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|max:255',
        ]);

        $record->update($validated);

        return $this->successResponse(
            new WidgetResource($record),
            'Widget updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $query = Widget::query();

        if (method_exists(Widget::class, 'company')) {
            $query->where('company_id', $request->user()->company_id);
        }

        $record = $query->findOrFail($id);
        $record->delete();

        return $this->successResponse(null, 'Widget deleted successfully');
    }
}
