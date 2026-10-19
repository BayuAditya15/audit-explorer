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
    Schema::create('cobit_practices', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cobit_objective_id')
            ->constrained('cobit_objectives')
            ->cascadeOnDelete();

        $table->string('practice_id', 15)->unique();
        $table->string('name');
        $table->text('description')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cobit_practices');
    }
};
