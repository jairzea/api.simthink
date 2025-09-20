<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('investigations', function (Blueprint $table) {
            $table->text('temp_id')->nullable()->after('research_goal');
            $table->text('error_message')->nullable()->after('temp_id');
        });
    }

    public function down(): void {
        Schema::table('investigations', function (Blueprint $table) {
            $table->dropColumn(['temp_id', 'results']);
        });
    }
};