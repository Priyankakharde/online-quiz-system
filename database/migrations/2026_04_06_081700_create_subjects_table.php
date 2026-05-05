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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();

            // 🔗 Link to Quiz (VERY IMPORTANT)
            $table->foreignId('quiz_id')
                  ->constrained()
                  ->onDelete('cascade');

            // 📘 Subject Name
            $table->string('name');

            // 🚫 Prevent duplicate subject in same quiz
            $table->unique(['quiz_id', 'name']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};