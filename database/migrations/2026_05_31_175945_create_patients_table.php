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
        Schema::create('patients', function (Blueprint $table) {
        $table->id();

        $table->string('name');
        $table->string('phone', 20)->unique();

        $table->tinyInteger('gender'); //TODO: Use enum

        $table->date('birth_date')->nullable();

        $table->string('address')->nullable();

        $table->text('notes')->nullable();

        $table->foreignId('created_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
