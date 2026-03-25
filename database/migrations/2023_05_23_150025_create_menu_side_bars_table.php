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
        Schema::create('menu_side_bars', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('icon')->nullable();
            $table->string('style')->nullable();
            $table->string('module')->nullable();
            $table->string('menu_above')->nullable();
            $table->integer('level');
            $table->string('route')->nullable();
            $table->string('acl')->nullable();
            $table->integer('order');
            $table->boolean('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_side_bars');
    }
};
