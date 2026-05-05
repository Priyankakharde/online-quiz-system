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
        Schema::create('quizzes', function (Blueprint $table) {

            $table->id();

            // 📝 Basic Info
            $table->string('title');
            $table->text('description')->nullable();

            // 🔗 Category Relation
            $table->foreignId('category_id')
                  ->constrained()
                  ->onDelete('cascade');

            // ⏱ Exam Settings
            $table->dateTime('exam_date')->nullable(); // start time
            $table->integer('duration')->default(60);  // minutes

            // 📊 Optional Future Features
            $table->integer('total_marks')->default(0);
            $table->integer('pass_marks')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};