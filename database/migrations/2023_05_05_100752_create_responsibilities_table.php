<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('responsibilities', static function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('job_id')
                ->constrained('jobs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('icon');
            $table->string('text');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responsibilities');
    }
};
