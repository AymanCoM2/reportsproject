<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('query_privots', function (Blueprint $table) {
            $table->longText("original")->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('query_privots', function (Blueprint $table) {
        });
    }
};
