<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Document Categories
        Schema::create('document_categories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('parent_id')->nullable()->constrained('document_categories')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'parent_id']);
            $table->index('is_active');
        });

        // Documents
        Schema::create('documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('document_category_id')->nullable()->constrained('document_categories')->nullOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable(); // pdf, doc, xlsx, etc.
            $table->bigInteger('file_size'); // in bytes
            $table->string('mime_type');
            $table->integer('version_number')->default(1);
            $table->uuid('current_version_id')->nullable(); // Foreign key added later to avoid circular dependency
            $table->boolean('is_locked')->default(false);
            $table->foreignUuid('locked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('locked_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('approval_status')->default('pending'); // pending, approved, rejected
            $table->foreignUuid('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('visibility')->default('private'); // private, internal, public
            $table->integer('download_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->string('related_type')->nullable(); // Polymorphic
            $table->uuid('related_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'document_category_id']);
            $table->index('approval_status');
            $table->index('visibility');
            $table->index('expires_at');
            $table->index(['related_type', 'related_id']);
            $table->fullText(['name', 'description']); // Full-text search
        });

        // Document Versions
        Schema::create('document_versions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->integer('version_number');
            $table->string('file_name');
            $table->string('file_path');
            $table->bigInteger('file_size');
            $table->string('mime_type');
            $table->text('change_summary')->nullable();
            $table->boolean('is_current')->default(false);
            $table->string('checksum')->nullable(); // For integrity verification
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['document_id', 'version_number']);
            $table->index(['document_id', 'is_current']);
        });

        // Document Tags
        Schema::create('document_tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->integer('usage_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'name']);
        });

        // Document Tag Pivot
        Schema::create('document_tag_pivot', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignUuid('document_tag_id')->constrained('document_tags')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['document_id', 'document_tag_id']);
        });

        // Document Permissions
        Schema::create('document_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();
            $table->uuid('role_id')->nullable(); // Reference to roles table
            $table->string('permission_type'); // user, role, public
            $table->boolean('can_view')->default(false);
            $table->boolean('can_download')->default(false);
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->boolean('can_share')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['document_id', 'user_id']);
            $table->index('permission_type');
            $table->index('expires_at');
        });

        // Document Comments
        Schema::create('document_comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('parent_id')->nullable()->constrained('document_comments')->cascadeOnDelete();
            $table->text('comment');
            $table->boolean('is_internal')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['document_id', 'parent_id']);
        });

        // Add foreign key to documents table for current_version_id
        // This is added after document_versions table is created to avoid circular dependency
        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('current_version_id')->references('id')->on('document_versions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['current_version_id']);
        });

        Schema::dropIfExists('document_comments');
        Schema::dropIfExists('document_permissions');
        Schema::dropIfExists('document_tag_pivot');
        Schema::dropIfExists('document_tags');
        Schema::dropIfExists('document_versions');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('document_categories');
    }
};
