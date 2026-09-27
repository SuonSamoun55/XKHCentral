<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('logo')->nullable();

            $table->boolean('show_logo')->default(true);
            // Matches the existing show_logo/show_address/... pattern — the
            // company name was the one field in that group with no toggle.
            $table->boolean('show_company_name')->default(true);
            $table->boolean('show_address')->default(true);
            $table->boolean('show_tax_number')->default(true);
            $table->boolean('show_phone')->default(true);
            $table->boolean('show_email')->default(true);

            $table->boolean('show_discount_column')->default(true);
            $table->boolean('show_vat_column')->default(true);
            // On by default — a data column already shown elsewhere in the app.
            $table->boolean('show_unit_column')->default(true);
            // Off by default: the current receipt layout has no image
            // column, so turning this on is an opt-in layout change.
            $table->boolean('show_item_image')->default(false);

            $table->string('spacing')->default('normal'); // compact | normal | spacious

            $table->boolean('show_signature')->default(false);
            $table->json('signature_labels')->nullable();

            $table->text('footer_note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_settings');
    }
};
