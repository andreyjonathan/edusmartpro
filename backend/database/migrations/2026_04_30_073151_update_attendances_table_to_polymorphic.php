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
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropUnique(['student_id', 'date']);
            $table->dropColumn('student_id');
            
            $table->morphs('attendant');
            $table->unique(['attendant_id', 'attendant_type', 'date'], 'attendance_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropUnique('attendance_unique');
            $table->dropMorphs('attendant');
            
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('cascade');
            $table->unique(['student_id', 'date']);
        });
    }
};
