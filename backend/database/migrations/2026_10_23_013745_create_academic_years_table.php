<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "2026/2027"
            $table->string('semester'); // e.g., "Ganjil" or "Genap"
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('semester');
            $table->string('academic_year');
            $table->float('task_score')->default(0);
            $table->float('mid_exam_score')->default(0);
            $table->float('final_exam_score')->default(0);
            $table->float('final_score')->default(0);
            $table->char('letter_grade', 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('lesson_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('class_name');
            $table->date('date');
            $table->text('topic');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_logs');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('academic_years');
    }
};