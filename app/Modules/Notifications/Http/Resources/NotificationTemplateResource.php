<?php

namespace App\Modules\Notifications\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationTemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'type' => $this->type,
            'channels' => $this->channels,
            'subject' => $this->subject,
            'message_template' => $this->message_template,
            'email_template' => $this->email_template,
            'sms_template' => $this->sms_template,
            'variables' => $this->variables,
            'icon' => $this->icon,
            'priority' => $this->priority,
            'action_url_template' => $this->action_url_template,
            'action_text' => $this->action_text,
            'is_system_template' => $this->is_system_template,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
