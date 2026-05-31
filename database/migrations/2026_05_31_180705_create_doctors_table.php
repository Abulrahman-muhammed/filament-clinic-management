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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
    $table->foreignId('user_id')
        ->constrained()
        ->cascadeOnDelete();

        $table->foreignId('specialization_id')
            ->constrained()
            ->cascadeOnDelete();
            $table->string('phone');
            $table->string('gender')->nullable();
            $table->text('bio')->nullable();
            $table->integer('experience_years')->default(0);
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
