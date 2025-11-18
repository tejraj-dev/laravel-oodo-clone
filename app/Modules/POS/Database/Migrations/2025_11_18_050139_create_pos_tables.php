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
        // Payment Methods
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->string('name');
            $table->string('type'); // cash, card, mobile_money, bank_transfer, credit, other
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_reference')->default(false);
            $table->string('icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        // Cash Registers
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->string('name');
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_close_session')->default(false);
            $table->text('receipt_header')->nullable();
            $table->text('receipt_footer')->nullable();
            $table->string('printer_ip')->nullable();
            $table->integer('printer_port')->nullable();
            $table->boolean('allow_discount')->default(true);
            $table->decimal('max_discount_percent', 5, 2)->nullable();
            $table->boolean('require_customer')->default(false);
            $table->boolean('allow_credit_sale')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        // POS Sessions
        Schema::create('pos_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('cash_register_id');
            $table->uuid('user_id');
            $table->string('name');
            $table->string('session_number')->unique();
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('closing_balance', 15, 2)->nullable();
            $table->decimal('expected_closing_balance', 15, 2)->nullable();
            $table->decimal('cash_in', 15, 2)->default(0);
            $table->decimal('cash_out', 15, 2)->default(0);
            $table->decimal('total_sales', 15, 2)->default(0);
            $table->decimal('total_refunds', 15, 2)->default(0);
            $table->string('status')->default('draft'); // draft, open, closed
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('cash_register_id')->references('id')->on('cash_registers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // POS Orders
        Schema::create('pos_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('pos_session_id');
            $table->uuid('customer_id')->nullable();
            $table->uuid('user_id');
            $table->string('order_number')->unique();
            $table->dateTime('order_date');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->string('discount_type')->nullable(); // fixed, percentage
            $table->decimal('discount_value', 15, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('amount_due', 15, 2)->default(0);
            $table->decimal('change_amount', 15, 2)->default(0);
            $table->string('status')->default('draft'); // draft, paid, partially_paid, refunded, cancelled
            $table->string('payment_status')->default('pending'); // pending, paid, partially_paid, refunded
            $table->text('notes')->nullable();
            $table->text('customer_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('pos_session_id')->references('id')->on('pos_sessions')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // POS Order Items
        Schema::create('pos_order_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('pos_order_id');
            $table->uuid('product_id')->nullable();
            $table->string('product_name');
            $table->string('product_code')->nullable();
            $table->decimal('quantity', 15, 2);
            $table->decimal('unit_price', 15, 2);
            $table->string('discount_type')->nullable(); // fixed, percentage
            $table->decimal('discount_value', 15, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2);
            $table->decimal('total', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pos_order_id')->references('id')->on('pos_orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });

        // POS Payments
        Schema::create('pos_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('company_id');
            $table->uuid('pos_order_id');
            $table->uuid('pos_session_id');
            $table->uuid('payment_method_id')->nullable();
            $table->uuid('user_id');
            $table->dateTime('payment_date');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method'); // cash, card, mobile_money, bank_transfer, credit
            $table->string('reference_number')->nullable();
            $table->string('card_type')->nullable(); // visa, mastercard, amex, etc.
            $table->string('card_last_four')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('status')->default('pending'); // pending, completed, failed, refunded
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('pos_order_id')->references('id')->on('pos_orders')->onDelete('cascade');
            $table->foreign('pos_session_id')->references('id')->on('pos_sessions')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_payments');
        Schema::dropIfExists('pos_order_items');
        Schema::dropIfExists('pos_orders');
        Schema::dropIfExists('pos_sessions');
        Schema::dropIfExists('cash_registers');
        Schema::dropIfExists('payment_methods');
    }
};
