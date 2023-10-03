<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('jobs', static function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->string('title')->index();
            $table->string('icon');

            $table->foreignUuid('subcategory_id')
                ->constrained('subcategories')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignUuid('location_id')
                ->constrained('locations')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('tools_remark', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
