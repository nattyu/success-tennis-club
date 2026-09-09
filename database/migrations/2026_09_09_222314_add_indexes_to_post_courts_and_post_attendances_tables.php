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
        Schema::table('post_courts', function (Blueprint $table) {
            $table->index(['elected_date', 'start_time']);
        });

        Schema::table('post_attendances', function (Blueprint $table) {
            $table->index('elected_court_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_courts', function (Blueprint $table) {
            $table->dropIndex(['elected_date', 'start_time']);
        });

        Schema::table('post_attendances', function (Blueprint $table) {
            $table->dropIndex(['elected_court_id']);
        });
    }
};
