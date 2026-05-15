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
        Schema::table('students', function (Blueprint $table) {
            // Link to the student's own user account
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
            
            // Parent Information
            $table->string('parent_name')->nullable()->after('phone');
            $table->string('parent_email')->nullable()->after('parent_name');
            $table->string('parent_phone')->nullable()->after('parent_email');
            
            // Link to the parent's user account
            $table->foreignId('parent_user_id')->nullable()->after('parent_phone')->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['parent_user_id']);
            $table->dropColumn(['user_id', 'parent_name', 'parent_email', 'parent_phone', 'parent_user_id']);
        });
    }
};
