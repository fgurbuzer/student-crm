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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('thread_id')->nullable()->constrained('threads')->cascadeOnDelete();
            $table->string('title');
            $table->enum('stage', [
                    'new_lead',
                    'contacted',
                    'consultation_booked',
                    'documents_requested',
                    'application_started',
                    'submitted',
                    'won',
                    'lost'
                ]);
            $table->decimal('value_amount', 12, 2)->nullable();
            $table->string('value_currency', 3)->default('USD');
            $table->text('expected_intake')->nullable();
            $table->text('lost_reason')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('owner_id');
            $table->index('stage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
