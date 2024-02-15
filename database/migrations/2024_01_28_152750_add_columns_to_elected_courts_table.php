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
        Schema::table('elected_courts', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id');
            $table->foreignId('court_id')->after('user_id');
            $table->string('court_number')->after('court_id');
            $table->string('start_time')->after('court_number');
            $table->string('end_time')->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elected_courts', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('court_id');
            $table->dropColumn('court_number');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
        });
    }
};
