<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Email Templates
        Schema::create('email_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('category'); // transactional, marketing, newsletter, system
            $table->string('subject');
            $table->longText('body_html');
            $table->text('body_text')->nullable();
            $table->json('variables')->nullable(); // Available variables
            $table->json('attachments')->nullable();
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();
            $table->string('reply_to')->nullable();
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->boolean('is_system_template')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'category']);
            $table->index('is_active');
        });

        // SMTP Configurations
        Schema::create('smtp_configurations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('driver')->default('smtp'); // smtp, sendmail, mailgun, ses, postmark
            $table->string('host');
            $table->integer('port')->default(587);
            $table->string('username')->nullable();
            $table->text('password')->nullable(); // Encrypted
            $table->string('encryption')->nullable(); // tls, ssl
            $table->string('from_name');
            $table->string('from_email');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('daily_limit')->nullable();
            $table->integer('hourly_limit')->nullable();
            $table->integer('emails_sent_today')->default(0);
            $table->integer('emails_sent_this_hour')->default(0);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'is_default']);
            $table->index('is_active');
        });

        // Email Campaigns
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('email_template_id')->nullable()->constrained('email_templates')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('campaign_type'); // newsletter, promotion, announcement, follow_up, drip
            $table->string('subject');
            $table->longText('body_html');
            $table->text('body_text')->nullable();
            $table->string('from_name');
            $table->string('from_email');
            $table->string('reply_to')->nullable();
            $table->string('recipient_list_type'); // manual, segment, import, all_customers, all_leads
            $table->json('recipient_list')->nullable();
            $table->integer('recipient_count')->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('total_sent')->default(0);
            $table->integer('total_delivered')->default(0);
            $table->integer('total_opened')->default(0);
            $table->integer('total_clicked')->default(0);
            $table->integer('total_bounced')->default(0);
            $table->integer('total_unsubscribed')->default(0);
            $table->integer('total_failed')->default(0);
            $table->string('status')->default('draft'); // draft, scheduled, sending, sent, completed, cancelled
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
            $table->index('campaign_type');
            $table->index('scheduled_at');
        });

        // Email Messages
        Schema::create('email_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('email_template_id')->nullable()->constrained('email_templates')->nullOnDelete();
            $table->foreignUuid('email_campaign_id')->nullable()->constrained('email_campaigns')->cascadeOnDelete();
            $table->string('message_id')->unique()->nullable(); // External email ID from provider
            $table->string('subject');
            $table->longText('body_html');
            $table->text('body_text')->nullable();
            $table->string('from_name');
            $table->string('from_email');
            $table->string('to_email');
            $table->string('to_name')->nullable();
            $table->string('reply_to')->nullable();
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->json('attachments')->nullable();
            $table->json('headers')->nullable();
            $table->string('status')->default('queued'); // queued, sent, delivered, opened, clicked, bounced, failed
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->string('related_type')->nullable(); // Polymorphic
            $table->uuid('related_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
            $table->index('to_email');
            $table->index(['related_type', 'related_id']);
        });

        // Email Recipients
        Schema::create('email_recipients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('email_campaign_id')->constrained('email_campaigns')->cascadeOnDelete();
            $table->foreignUuid('email_message_id')->nullable()->constrained('email_messages')->nullOnDelete();
            $table->string('email');
            $table->string('name')->nullable();
            $table->string('status')->default('pending'); // pending, sent, delivered, opened, clicked, bounced, failed
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamp('bounced_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->integer('open_count')->default(0);
            $table->integer('click_count')->default(0);
            $table->string('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'email']);
            $table->index(['email_campaign_id', 'status']);
        });

        // Auto Responders
        Schema::create('auto_responders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('email_template_id')->nullable()->constrained('email_templates')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('trigger_type'); // form_submission, lead_created, order_placed, payment_received, etc.
            $table->json('trigger_conditions')->nullable();
            $table->integer('delay_minutes')->default(0);
            $table->string('subject');
            $table->longText('body_html');
            $table->text('body_text')->nullable();
            $table->string('from_name');
            $table->string('from_email');
            $table->string('reply_to')->nullable();
            $table->integer('max_sends_per_recipient')->default(1);
            $table->boolean('is_active')->default(true);
            $table->integer('sent_count')->default(0);
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'trigger_type']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auto_responders');
        Schema::dropIfExists('email_recipients');
        Schema::dropIfExists('email_messages');
        Schema::dropIfExists('email_campaigns');
        Schema::dropIfExists('smtp_configurations');
        Schema::dropIfExists('email_templates');
    }
};
