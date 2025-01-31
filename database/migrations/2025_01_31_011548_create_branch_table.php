<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch', function (Blueprint $table) {
            $table->id()->index();
            $table->uuid('uuid')->unique()->index();
            $table->string('name', 150);
            $table->string('code', 100)->unique();
            $table->string('address', 255);
            $table->string('phone', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('is_active', 100)->default('yes');
            $table->string('user_created', 100)->nullable();
            $table->string('user_updated', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch');
    }
};
