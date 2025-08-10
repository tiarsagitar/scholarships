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
        Schema::create('disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disbursement_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('award_id')->constrained()->onDelete('cascade');
            $table->foreignId('cost_category_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->timestamp('disbursed_at')->nullable();
            $table->string('status')->default('pending');
            $table->string('idempotency')->unique();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disbursements');
    }
};
