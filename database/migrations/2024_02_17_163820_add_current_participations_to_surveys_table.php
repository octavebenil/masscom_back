<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surveys', static function (Blueprint $table) {
            $table->unsignedBigInteger('current_participations')
                  ->default(0)
                  ->after('max_participants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', static function (Blueprint $table) {
            $table->dropColumn('current_participations');
        });
    }
};
