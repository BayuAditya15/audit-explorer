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
    Schema::create('cobit_objectives', function (Blueprint $table) {
        $table->id();
        $table->string('objective_id', 10)->unique();
        $table->string('area', 30);
        $table->string('domain', 100);
        $table->string('name');
        $table->text('description')->nullable();
        $table->text('purpose_statement')->nullable();
        $table->boolean('new_in_cobit_2019')->default(false);
        $table->boolean('new_in_cobit_5')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cobit_objectives');
    }
};
