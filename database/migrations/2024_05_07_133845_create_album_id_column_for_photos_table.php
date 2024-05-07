<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            // album_id列を追加
            $table->unsignedBigInteger('album_id')->after('user_id');

            // 新しい外部キー制約を追加
            $table->foreign('album_id')->references('id')->on('galleries')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            // 外部キー制約を削除する
            $table->dropForeign(['album_id']);
            
            // album_id列を削除する
            $table->dropColumn('album_id');
        });
    }
};
