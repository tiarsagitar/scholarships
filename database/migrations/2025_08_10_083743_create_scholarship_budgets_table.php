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
        Schema::create('scholarship_budgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('scholarship_id');
            $table->unsignedInteger('cost_category_id');
            $table->decimal('planned_amount', 12, 2)->default(0);
            $table->timestamps();
            
            $table->index('scholarship_id', 'scholarship_budget_scholarship_id_index');
            $table->index('cost_category_id', 'scholarship_budget_cost_category_id_index');
            $table->foreign('scholarship_id')->references('id')->on('scholarships')->onDelete('cascade');
            $table->foreign('cost_category_id')->references('id')->on('cost_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_budgets');
    }
};
