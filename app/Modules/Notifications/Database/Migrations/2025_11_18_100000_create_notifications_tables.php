<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Notification Templates
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('type'); // order, payment, inventory, crm, hr, system, custom
            $table->json('channels'); // database, email, sms, push, slack
            $table->string('subject')->nullable();
            $table->text('message_template');
            $table->text('email_template')->nullable();
            $table->text('sms_template')->nullable();
            $table->json('variables')->nullable(); // Available variables for template
            $table->string('icon')->nullable();
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->string('action_url_template')->nullable();
            $table->string('action_text')->nullable();
            $table->boolean('is_system_template')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'type']);
            $table->index('is_active');
        });

        // Notification Preferences
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('notification_type'); // order, payment, inventory, etc.
            $table->boolean('channel_database')->default(true);
            $table->boolean('channel_email')->default(true);
            $table->boolean('channel_sms')->default(false);
            $table->boolean('channel_push')->default(false);
            $table->boolean('channel_slack')->default(false);
            $table->string('frequency')->default('immediate'); // immediate, daily_digest, weekly_digest
            $table->time('quiet_hours_start')->nullable();
            $table->time('quiet_hours_end')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'notification_type']);
            $table->index(['company_id', 'user_id']);
        });

        // Notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('notification_template_id')->nullable()->constrained('notification_templates')->nullOnDelete();
            $table->string('type'); // order, payment, inventory, crm, hr, system, custom
            $table->string('channel'); // database, email, sms, push, slack
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data
            $table->string('action_url')->nullable();
            $table->string('action_text')->nullable();
            $table->string('icon')->nullable();
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->string('related_type')->nullable(); // Polymorphic relation
            $table->uuid('related_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'user_id', 'is_read']);
            $table->index(['type', 'channel']);
            $table->index('priority');
            $table->index(['related_type', 'related_id']);
        });

        // Scheduled Notifications
        Schema::create('scheduled_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('notification_template_id')->nullable()->constrained('notification_templates')->nullOnDelete();
            $table->string('name');
            $table->string('type'); // order, payment, inventory, crm, hr, system, custom
            $table->json('channels'); // database, email, sms, push, slack
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->string('action_url')->nullable();
            $table->string('action_text')->nullable();
            $table->string('icon')->nullable();
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->string('schedule_type'); // once, daily, weekly, monthly, yearly, custom
            $table->timestamp('scheduled_at')->nullable();
            $table->string('recurrence_rule')->nullable(); // Cron expression or custom rule
            $table->json('recipients')->nullable(); // User IDs or roles
            $table->string('recipient_type')->default('user'); // user, role, all
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamp('next_send_at')->nullable();
            $table->integer('send_count')->default(0);
            $table->integer('max_send_count')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('status')->default('pending'); // pending, sent, failed, cancelled
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status', 'is_active']);
            $table->index('next_send_at');
            $table->index('schedule_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_notifications');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('notification_templates');
    }
};
