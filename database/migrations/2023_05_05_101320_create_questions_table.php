<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('questions', static function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('job_id')
                ->constrained('jobs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignUuid('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('question');
            $table->string('answer')->nullable();
            $table->boolean('is_favourited')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
