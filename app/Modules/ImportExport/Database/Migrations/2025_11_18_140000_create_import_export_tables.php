<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Import Templates
        Schema::create('import_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('entity_type'); // customers, products, invoices, etc.
            $table->json('mapping'); // Field mapping configuration
            $table->json('validation_rules')->nullable();
            $table->json('default_values')->nullable();
            $table->string('sample_file_path')->nullable();
            $table->boolean('is_system_template')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'entity_type']);
            $table->index('is_active');
        });

        // Import Jobs
        Schema::create('import_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('import_template_id')->nullable()->constrained('import_templates')->nullOnDelete();
            $table->string('name');
            $table->string('entity_type'); // customers, products, invoices, etc.
            $table->string('file_name');
            $table->string('file_path');
            $table->bigInteger('file_size'); // in bytes
            $table->string('file_type'); // csv, xlsx, etc.
            $table->json('mapping'); // Field mapping used for this import
            $table->json('options')->nullable(); // Import options (skip_duplicates, update_existing, etc.)
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->integer('total_rows')->default(0);
            $table->integer('processed_rows')->default(0);
            $table->integer('successful_rows')->default(0);
            $table->integer('failed_rows')->default(0);
            $table->integer('skipped_rows')->default(0);
            $table->json('errors')->nullable(); // Array of errors per row
            $table->json('warnings')->nullable(); // Array of warnings
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
            $table->index('entity_type');
            $table->index('user_id');
        });

        // Export Jobs
        Schema::create('export_jobs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('entity_type'); // customers, products, invoices, etc.
            $table->string('format'); // csv, xlsx, pdf
            $table->json('filters')->nullable(); // Export filters/criteria
            $table->json('columns')->nullable(); // Columns to export
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->bigInteger('file_size')->nullable(); // in bytes
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->integer('total_rows')->default(0);
            $table->boolean('is_scheduled')->default(false);
            $table->string('schedule_frequency')->nullable(); // daily, weekly, monthly, yearly
            $table->timestamp('next_run_at')->nullable();
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['company_id', 'status']);
            $table->index('entity_type');
            $table->index(['is_scheduled', 'next_run_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_jobs');
        Schema::dropIfExists('import_jobs');
        Schema::dropIfExists('import_templates');
    }
};
