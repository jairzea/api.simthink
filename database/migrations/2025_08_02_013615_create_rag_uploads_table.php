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

        Schema::create('rag_uploads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained();
            $table->foreignUuid('investigation_id')->nullable()->constrained();
            $table->string('filename');
            $table->integer('size_kb');
            $table->enum('file_type', ["pdf","doc","docx","txt","xlsx","image"]);
            $table->string('path');
            $table->enum('status', ["uploaded","processed","deleted"]);
            $table->foreignId('user_investigation_id');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rag_uploads');
    }
};
