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
        Schema::table('quizzes', function (Blueprint $table) {

            // Add exam_date only if not exists
            if (!Schema::hasColumn('quizzes', 'exam_date')) {
                $table->dateTime('exam_date')->nullable();
            }

            // Add duration only if not exists
            if (!Schema::hasColumn('quizzes', 'duration')) {
                $table->integer('duration')->nullable();
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {

            // Drop only if exists
            if (Schema::hasColumn('quizzes', 'exam_date')) {
                $table->dropColumn('exam_date');
            }

            if (Schema::hasColumn('quizzes', 'duration')) {
                $table->dropColumn('duration');
            }

        });
    }
};