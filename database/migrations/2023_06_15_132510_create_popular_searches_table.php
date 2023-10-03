<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('popular_searches', static function (Blueprint $table): void {
            $table->uuid('id')->primary()->index();
            $table->uuid('job_id');
            $table->string('job_title');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('popular_searches');
    }
};
