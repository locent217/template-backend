<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('island_id')->nullable();
            $table->foreign('island_id')->references('id')->on('islands')->onUpdate('cascade')->onDelete('set null');
            $table->uuid('territory_id')->nullable();
            $table->foreign('territory_id')->references('id')->on('territories')->onUpdate('cascade')->onDelete('set null');
            $table->uuid('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
