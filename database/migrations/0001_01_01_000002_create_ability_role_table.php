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
        Schema::create('ability_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ability_id')->constrained('abilities')->onDelete("cascade");
            $table->foreignId('role_id')->constrained('roles')->onDelete("cascade");
            $table->unique(['ability_id', 'role_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ability_role');
    }
};
