<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Single-row, app-wide table: the login page is shown before anyone
        // picks a company, so it isn't company-scoped. Every column is
        // nullable — null means "use the built-in default" (see LoginSetting).
        Schema::create('login_settings', function (Blueprint $table) {
            $table->id();

            // Desktop: video on the left of the login form.
            $table->string('desktop_video')->nullable();

            // Mobile: welcome screen → 3 intro slides → login form image.
            $table->string('welcome_logo')->nullable();
            $table->string('welcome_image')->nullable();

            foreach ([1, 2, 3] as $n) {
                $table->string("slide_{$n}_image")->nullable();
                $table->string("slide_{$n}_title")->nullable();
                $table->string("slide_{$n}_text", 500)->nullable();
            }

            $table->string('mobile_image')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_settings');
    }
};
