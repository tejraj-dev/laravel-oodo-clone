<?php

namespace App\Modules\Notifications\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Notifications\Http\Resources\NotificationResource;
use App\Modules\Notifications\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = Notification::query()
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->is_read !== null, fn($q) => $q->where('is_read', $request->is_read))
            ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => NotificationResource::collection($notifications),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
            ],
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $notification = Notification::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new NotificationResource($notification),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|uuid|exists:companies,id',
            'user_id' => 'required|uuid|exists:users,id',
            'notification_template_id' => 'nullable|uuid|exists:notification_templates,id',
            'type' => 'required|string',
            'channel' => 'required|string',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'data' => 'nullable|array',
            'action_url' => 'nullable|string|max:255',
            'action_text' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'priority' => 'nullable|string|in:low,normal,high,urgent',
        ]);

        $notification = Notification::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Notification created successfully',
            'data' => new NotificationResource($notification),
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $notification = Notification::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'message' => 'sometimes|string',
            'is_read' => 'sometimes|boolean',
            'priority' => 'sometimes|string|in:low,normal,high,urgent',
        ]);

        $notification->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Notification updated successfully',
            'data' => new NotificationResource($notification),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully',
        ]);
    }

    public function markAsRead(string $id): JsonResponse
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'data' => new NotificationResource($notification),
        ]);
    }

    public function markAsUnread(string $id): JsonResponse
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsUnread();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as unread',
            'data' => new NotificationResource($notification),
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $userId = $request->user_id ?? auth()->id();

        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $userId = $request->user_id ?? auth()->id();

        $count = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'unread_count' => $count,
        ]);
    }
}
