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
        Schema::create('cobit_raci_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cobit_practice_id')
                ->constrained('cobit_practices')
                ->cascadeOnDelete();
            $table->foreignId('cobit_role_id')
                ->constrained('cobit_roles')
                ->cascadeOnDelete();
            $table->char('raci_level', 1); // 'A' or 'R'
            $table->timestamps();

            $table->unique(['cobit_practice_id', 'cobit_role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cobit_raci_assignments');
    }
};
