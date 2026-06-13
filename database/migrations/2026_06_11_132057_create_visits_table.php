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
        Schema::create('visits', function (Blueprint $table) {
        $table->id();

        $table->foreignId('patient_id')
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('appointment_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->tinyInteger('status')->default(0);
        $table->text('complaint')->nullable();
        $table->longText('diagnosis')->nullable();

        $table->longText('notes')->nullable();

        $table->dateTime('visited_at');

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
