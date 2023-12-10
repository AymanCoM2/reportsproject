<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('temp_uds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('isUsed')->default(false);
            $table->boolean('isForSavingNewPivot')->default(false);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('query_id')->nullable();
            $table->longText('pivotCode')->nullable();
            $table->longText('sqlQuery')->nullable();
            $table->string('dbName')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temp_uds');
    }
};
