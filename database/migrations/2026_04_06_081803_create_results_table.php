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
        Schema::create('results', function (Blueprint $table) {
            $table->id();

            // user who attempted quiz
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // quiz reference
            $table->foreignId('quiz_id')
                  ->constrained()
                  ->onDelete('cascade');

            // score details
            $table->integer('score');
            $table->integer('total');

            // optional percentage (advanced feature)
            $table->float('percentage')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};