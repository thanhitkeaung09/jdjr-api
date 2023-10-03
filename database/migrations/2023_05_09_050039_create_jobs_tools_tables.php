<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('jobs_tools', static function (Blueprint $table): void {
            $table->foreignUuid('job_id')
                ->constrained('jobs')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignUuid('tool_id')
                ->constrained('tools')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs_tools');
    }
};
