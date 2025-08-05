<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('investigations', function (Blueprint $table) {
            $table->text('context_info')->nullable()->after('type');
            $table->text('target_persona')->nullable()->after('context_info');
            $table->text('research_goal')->nullable()->after('target_persona');
            $table->text('product_info')->nullable()->after('research_goal');
        });
    }

    public function down(): void {
        Schema::table('investigations', function (Blueprint $table) {
            $table->dropColumn(['context_info', 'target_persona', 'research_goal', 'product_info']);
        });
    }
};