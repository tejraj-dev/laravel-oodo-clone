<?php

namespace App\Modules\Notifications\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Notifications\Http\Resources\NotificationTemplateResource;
use App\Modules\Notifications\Models\NotificationTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationTemplateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $templates = NotificationTemplate::query()
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->is_active !== null, fn($q) => $q->where('is_active', $request->is_active))
            ->orderBy('name')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => NotificationTemplateResource::collection($templates),
            'meta' => [
                'current_page' => $templates->currentPage(),
                'last_page' => $templates->lastPage(),
                'per_page' => $templates->perPage(),
                'total' => $templates->total(),
            ],
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $template = NotificationTemplate::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new NotificationTemplateResource($template),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|uuid|exists:companies,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:notification_templates,code',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'channels' => 'required|array',
            'subject' => 'nullable|string|max:255',
            'message_template' => 'required|string',
            'email_template' => 'nullable|string',
            'sms_template' => 'nullable|string',
            'variables' => 'nullable|array',
            'icon' => 'nullable|string|max:255',
            'priority' => 'nullable|string|in:low,normal,high,urgent',
            'action_url_template' => 'nullable|string|max:255',
            'action_text' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $template = NotificationTemplate::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Notification template created successfully',
            'data' => new NotificationTemplateResource($template),
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $template = NotificationTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'channels' => 'sometimes|array',
            'subject' => 'nullable|string|max:255',
            'message_template' => 'sometimes|string',
            'email_template' => 'nullable|string',
            'sms_template' => 'nullable|string',
            'variables' => 'nullable|array',
            'priority' => 'sometimes|string|in:low,normal,high,urgent',
            'is_active' => 'sometimes|boolean',
        ]);

        $template->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Notification template updated successfully',
            'data' => new NotificationTemplateResource($template),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $template = NotificationTemplate::findOrFail($id);

        if ($template->is_system_template) {
            return response()->json([
                'success' => false,
                'message' => 'System templates cannot be deleted',
            ], 403);
        }

        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification template deleted successfully',
        ]);
    }
}
