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
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications');
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('approved_by')->constrained('users');
            $table->decimal('amount', 12, 2)->default(0);
            $table->date('awarded_at')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            // add index
            $table->index(['application_id', 'student_id'], 'awards_application_student_index');
            $table->index('approved_by', 'awards_approved_by_index');
            $table->index('awarded_at', 'awards_awarded_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awards');
    }
};
