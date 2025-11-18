<?php

namespace App\Modules\Email\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmailTemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'category' => $this->category,
            'subject' => $this->subject,
            'body_html' => $this->body_html,
            'body_text' => $this->body_text,
            'variables' => $this->variables,
            'attachments' => $this->attachments,
            'from_name' => $this->from_name,
            'from_email' => $this->from_email,
            'reply_to' => $this->reply_to,
            'cc' => $this->cc,
            'bcc' => $this->bcc,
            'is_system_template' => $this->is_system_template,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
