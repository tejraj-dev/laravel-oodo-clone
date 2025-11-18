<?php

namespace App\Modules\Email\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Email\Http\Resources\EmailCampaignResource;
use App\Modules\Email\Models\EmailCampaign;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EmailCampaignController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $campaigns = EmailCampaign::query()
            ->when($request->campaign_type, fn($q) => $q->where('campaign_type', $request->campaign_type))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => EmailCampaignResource::collection($campaigns),
            'meta' => [
                'current_page' => $campaigns->currentPage(),
                'last_page' => $campaigns->lastPage(),
                'per_page' => $campaigns->perPage(),
                'total' => $campaigns->total(),
            ],
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $campaign = EmailCampaign::with(['template', 'messages', 'recipients'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new EmailCampaignResource($campaign),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_id' => 'required|uuid|exists:companies,id',
            'user_id' => 'required|uuid|exists:users,id',
            'email_template_id' => 'nullable|uuid|exists:email_templates,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'campaign_type' => 'required|string',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'from_name' => 'required|string|max:255',
            'from_email' => 'required|email',
            'recipient_list_type' => 'required|string',
            'recipient_list' => 'nullable|array',
            'scheduled_at' => 'nullable|date',
        ]);

        $validated['status'] = 'draft';
        $campaign = EmailCampaign::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Email campaign created successfully',
            'data' => new EmailCampaignResource($campaign),
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $campaign = EmailCampaign::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'sometimes|string|max:255',
            'body_html' => 'sometimes|string',
            'recipient_list' => 'nullable|array',
            'scheduled_at' => 'nullable|date',
            'status' => 'sometimes|string',
        ]);

        $campaign->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Email campaign updated successfully',
            'data' => new EmailCampaignResource($campaign),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $campaign = EmailCampaign::findOrFail($id);

        if (in_array($campaign->status, ['sending', 'sent'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete campaigns that are sending or have been sent',
            ], 403);
        }

        $campaign->delete();

        return response()->json([
            'success' => true,
            'message' => 'Email campaign deleted successfully',
        ]);
    }

    public function statistics(string $id): JsonResponse
    {
        $campaign = EmailCampaign::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'total_sent' => $campaign->total_sent,
                'total_delivered' => $campaign->total_delivered,
                'total_opened' => $campaign->total_opened,
                'total_clicked' => $campaign->total_clicked,
                'total_bounced' => $campaign->total_bounced,
                'total_unsubscribed' => $campaign->total_unsubscribed,
                'total_failed' => $campaign->total_failed,
                'open_rate' => $campaign->getOpenRate(),
                'click_rate' => $campaign->getClickRate(),
                'delivery_rate' => $campaign->total_sent > 0
                    ? round(($campaign->total_delivered / $campaign->total_sent) * 100, 2)
                    : 0,
                'bounce_rate' => $campaign->total_sent > 0
                    ? round(($campaign->total_bounced / $campaign->total_sent) * 100, 2)
                    : 0,
            ],
        ]);
    }
}
