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
        Schema::create('department_user_type_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onUpdate('cascade')->onDelete('set null');
            $table->uuid('user_type_id')->nullable();
            $table->foreign('user_type_id')->references('id')->on('user_types')->onUpdate('cascade')->onDelete('set null');
            $table->uuid('permission_id')->nullable();
            $table->foreign('permission_id')->references('id')->on('permissions')->onUpdate('cascade')->onDelete('set null');
            $table->unique(
                ['department_id', 'user_type_id', 'permission_id'],
                'dept_type_permission_unique'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_user_type_permissions');
    }
};
