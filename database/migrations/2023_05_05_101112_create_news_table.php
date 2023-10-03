<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('news', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('short_body');
            $table->longText('body');
            $table->string('thumbnail');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
