<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Approval Workflows
        Schema::create('approval_workflows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('entity_type'); // purchase_order, invoice, leave_request, expense, etc.
            $table->string('workflow_type')->default('sequential'); // sequential, parallel, conditional
            $table->boolean('is_sequential')->default(true);
            $table->boolean('require_all_approvers')->default(true);
            $table->decimal('auto_approve_threshold', 15, 2)->nullable();
            $table->decimal('auto_reject_threshold', 15, 2)->nullable();
            $table->boolean('escalation_enabled')->default(false);
            $table->integer('escalation_hours')->nullable();
            $table->boolean('notification_enabled')->default(true);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'entity_type']);
            $table->index('is_active');
        });

        // Approval Steps
        Schema::create('approval_steps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('approval_workflow_id')->constrained('approval_workflows')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('step_order');
            $table->string('approver_type'); // user, role, manager, department_head
            $table->foreignUuid('approver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->uuid('role_id')->nullable(); // Reference to roles table
            $table->integer('required_approvals')->default(1);
            $table->boolean('allow_delegation')->default(true);
            $table->integer('sla_hours')->nullable(); // SLA in hours
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['approval_workflow_id', 'step_order']);
        });

        // Approval Rules
        Schema::create('approval_rules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('approval_workflow_id')->constrained('approval_workflows')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('condition_field'); // amount, department, priority, etc.
            $table->string('condition_operator'); // equals, greater_than, less_than, contains, etc.
            $table->string('condition_value');
            $table->string('rule_type')->default('trigger'); // trigger, skip, escalate
            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['approval_workflow_id', 'priority']);
        });

        // Approval Requests
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('approval_workflow_id')->constrained('approval_workflows')->cascadeOnDelete();
            $table->foreignUuid('requester_id')->constrained('users')->cascadeOnDelete();
            $table->string('request_number')->unique();
            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('entity_type'); // purchase_order, invoice, leave_request, etc.
            $table->uuid('entity_id'); // ID of the entity being approved
            $table->foreignUuid('current_step_id')->nullable()->constrained('approval_steps')->nullOnDelete();
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->string('status')->default('pending'); // pending, approved, rejected, cancelled
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->integer('total_steps')->default(0);
            $table->integer('completed_steps')->default(0);
            $table->foreignUuid('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
            $table->index(['entity_type', 'entity_id']);
            $table->index('requester_id');
        });

        // Approval Actions
        Schema::create('approval_actions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('approval_request_id')->constrained('approval_requests')->cascadeOnDelete();
            $table->foreignUuid('approval_step_id')->constrained('approval_steps')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('delegated_from')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // approved, rejected, delegated, requested_info
            $table->text('comments')->nullable();
            $table->timestamp('action_at');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['approval_request_id', 'action']);
            $table->index('user_id');
        });

        // Approval Delegates
        Schema::create('approval_delegates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('delegator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('delegate_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('approval_workflow_id')->nullable()->constrained('approval_workflows')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['delegator_id', 'delegate_id']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_delegates');
        Schema::dropIfExists('approval_actions');
        Schema::dropIfExists('approval_requests');
        Schema::dropIfExists('approval_rules');
        Schema::dropIfExists('approval_steps');
        Schema::dropIfExists('approval_workflows');
    }
};
