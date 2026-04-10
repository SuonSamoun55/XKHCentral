<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_connections', function (Blueprint $table) {
            if (!Schema::hasColumn('company_connections', 'api_scope')) {
                $table->string('api_scope')->nullable()->after('token_url');
            }
            if (!Schema::hasColumn('company_connections', 'customers_endpoint')) {
                $table->text('customers_endpoint')->nullable()->after('api_scope');
            }
            if (!Schema::hasColumn('company_connections', 'items_endpoint')) {
                $table->text('items_endpoint')->nullable()->after('customers_endpoint');
            }
            if (!Schema::hasColumn('company_connections', 'sales_orders_endpoint')) {
                $table->text('sales_orders_endpoint')->nullable()->after('items_endpoint');
            }
            if (!Schema::hasColumn('company_connections', 'sales_order_lines_endpoint')) {
                $table->text('sales_order_lines_endpoint')->nullable()->after('sales_orders_endpoint');
            }
            if (!Schema::hasColumn('company_connections', 'sales_orders_by_number_endpoint')) {
                $table->text('sales_orders_by_number_endpoint')->nullable()->after('sales_order_lines_endpoint');
            }
            if (!Schema::hasColumn('company_connections', 'sales_order_pdf_endpoint')) {
                $table->text('sales_order_pdf_endpoint')->nullable()->after('sales_orders_by_number_endpoint');
            }
        });
    }

    public function down(): void
    {
        Schema::table('company_connections', function (Blueprint $table) {
            $columns = [];
            foreach ([
                'api_scope',
                'customers_endpoint',
                'items_endpoint',
                'sales_orders_endpoint',
                'sales_order_lines_endpoint',
                'sales_orders_by_number_endpoint',
                'sales_order_pdf_endpoint',
            ] as $column) {
                if (Schema::hasColumn('company_connections', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

