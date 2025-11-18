<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Report Templates
        Schema::create('report_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category'); // sales, financial, inventory, hr, crm
            $table->string('report_type'); // tabular, chart, pivot, summary
            $table->string('data_source');
            $table->json('default_config')->nullable();
            $table->json('required_parameters')->nullable();
            $table->boolean('is_system_template')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        // Reports
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('user_id');
            $table->uuid('report_template_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('report_type'); // tabular, chart, pivot, summary, custom
            $table->string('data_source');
            $table->json('query_config')->nullable();
            $table->json('filters')->nullable();
            $table->json('columns')->nullable();
            $table->json('grouping')->nullable();
            $table->json('sorting')->nullable();
            $table->string('chart_type')->nullable(); // line, bar, pie, doughnut, area, scatter
            $table->json('chart_config')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('is_favorite')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('report_template_id')->references('id')->on('report_templates')->onDelete('set null');
        });

        // Dashboards
        Schema::create('dashboards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('user_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('layout_config')->nullable();
            $table->integer('refresh_interval')->nullable(); // seconds
            $table->boolean('is_default')->default(false);
            $table->boolean('is_public')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // KPIs
        Schema::create('kpis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category'); // sales, finance, operations, hr, customer
            $table->string('metric_type'); // count, sum, average, percentage, ratio
            $table->string('data_source');
            $table->json('calculation_formula')->nullable();
            $table->decimal('target_value', 15, 2)->nullable();
            $table->decimal('current_value', 15, 2)->nullable();
            $table->string('unit')->nullable(); // currency, percentage, number
            $table->string('trend')->nullable(); // up, down, stable
            $table->string('comparison_period')->nullable(); // daily, weekly, monthly, quarterly, yearly
            $table->json('color_rules')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        // Widgets
        Schema::create('widgets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('dashboard_id');
            $table->uuid('report_id')->nullable();
            $table->uuid('kpi_id')->nullable();
            $table->string('name');
            $table->string('widget_type'); // chart, metric, table, kpi, custom
            $table->integer('position_x')->default(0);
            $table->integer('position_y')->default(0);
            $table->integer('width')->default(4);
            $table->integer('height')->default(4);
            $table->json('config')->nullable();
            $table->integer('refresh_interval')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('dashboard_id')->references('id')->on('dashboards')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('kpi_id')->references('id')->on('kpis')->onDelete('cascade');
        });

        // Scheduled Reports
        Schema::create('scheduled_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('report_id');
            $table->uuid('user_id');
            $table->string('name');
            $table->string('schedule_type'); // daily, weekly, monthly, quarterly, yearly, custom
            $table->json('schedule_config')->nullable();
            $table->json('recipients')->nullable();
            $table->string('export_format')->default('pdf'); // pdf, excel, csv, json
            $table->dateTime('last_run_at')->nullable();
            $table->dateTime('next_run_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_reports');
        Schema::dropIfExists('widgets');
        Schema::dropIfExists('kpis');
        Schema::dropIfExists('dashboards');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('report_templates');
    }
};
