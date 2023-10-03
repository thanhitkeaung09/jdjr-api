<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('application_keys', static function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('app_id')->unique();
            $table->string('app_secrete')->unique();
            $table->boolean('obsoleted')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_keys');
    }
};
