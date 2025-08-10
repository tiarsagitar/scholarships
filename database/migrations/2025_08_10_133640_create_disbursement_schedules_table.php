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
        Schema::create('disbursement_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('award_allocation_id')->constrained();
            $table->foreignId('cost_category_id')->constrained();
            $table->decimal('scheduled_amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->date('scheduled_date');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disbursement_schedules');
    }
};
