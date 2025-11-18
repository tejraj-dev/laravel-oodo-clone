<?php

namespace App\Modules\Email\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailCampaignResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'user_id' => $this->user_id,
            'email_template_id' => $this->email_template_id,
            'name' => $this->name,
            'description' => $this->description,
            'campaign_type' => $this->campaign_type,
            'subject' => $this->subject,
            'body_html' => $this->body_html,
            'body_text' => $this->body_text,
            'from_name' => $this->from_name,
            'from_email' => $this->from_email,
            'reply_to' => $this->reply_to,
            'recipient_list_type' => $this->recipient_list_type,
            'recipient_count' => $this->recipient_count,
            'scheduled_at' => $this->scheduled_at?->toISOString(),
            'sent_at' => $this->sent_at?->toISOString(),
            'completed_at' => $this->completed_at?->toISOString(),
            'total_sent' => $this->total_sent,
            'total_delivered' => $this->total_delivered,
            'total_opened' => $this->total_opened,
            'total_clicked' => $this->total_clicked,
            'total_bounced' => $this->total_bounced,
            'total_unsubscribed' => $this->total_unsubscribed,
            'total_failed' => $this->total_failed,
            'open_rate' => $this->getOpenRate(),
            'click_rate' => $this->getClickRate(),
            'status' => $this->status,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
