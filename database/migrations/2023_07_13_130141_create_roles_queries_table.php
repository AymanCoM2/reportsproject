<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles_queries', function (Blueprint $table) {
            // & There is no Model For this Migration; 
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('query_id');
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('query_id')->references('id')->on('query_of_reports')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles_queries');
    }
};
