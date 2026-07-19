    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('item_setup_statuses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('item_id')->constrained('items')->cascadeOnDelete();

                $table->boolean('main_image_done')->default(false);
                $table->boolean('variants_done')->default(false);

                $table->timestamps();

                $table->unique('item_id');
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('item_setup_statuses');
        }
    };