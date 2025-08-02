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
        Schema::disableForeignKeyConstraints();

        Schema::create('synthetic_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('synthetic_user_id')->constrained();
            $table->text('question');
            $table->text('answer');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('synthetic_responses');
    }
};
