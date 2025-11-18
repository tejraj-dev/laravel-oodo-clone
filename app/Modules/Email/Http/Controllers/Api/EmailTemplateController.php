<?php

namespace App\Modules\Email\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Email\Http\Resources\EmailTemplateResource;
use App\Modules\Email\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmailTemplateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $templates = EmailTemplate::query()
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->is_active !== null, fn($q) => $q->where('is_active', $request->is_active))
            ->orderBy('name')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => EmailTemplateResource::collection($templates),
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
        $template = EmailTemplate::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new EmailTemplateResource($template),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|uuid|exists:companies,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:email_templates,code',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
            'variables' => 'nullable|array',
            'from_name' => 'nullable|string|max:255',
            'from_email' => 'nullable|email',
            'is_active' => 'nullable|boolean',
        ]);

        $template = EmailTemplate::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Email template created successfully',
            'data' => new EmailTemplateResource($template),
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $template = EmailTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category' => 'sometimes|string',
            'subject' => 'sometimes|string|max:255',
            'body_html' => 'sometimes|string',
            'body_text' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $template->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Email template updated successfully',
            'data' => new EmailTemplateResource($template),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $template = EmailTemplate::findOrFail($id);

        if ($template->is_system_template) {
            return response()->json([
                'success' => false,
                'message' => 'System templates cannot be deleted',
            ], 403);
        }

        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Email template deleted successfully',
        ]);
    }
}
