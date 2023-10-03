<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('app_versions', static function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('version');
            $table->string('build_no');
            $table->boolean('is_forced_updated')->default(false);
            $table->string('ios_link');
            $table->string('android_link');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_versions');
    }
};
