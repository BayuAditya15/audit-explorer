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
    Schema::create('cobit_activities', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cobit_practice_id')
            ->constrained('cobit_practices')
            ->cascadeOnDelete();

        $table->unsignedInteger('sequence')->nullable();
        $table->text('description');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cobit_activities');
    }
};
