<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            // 既存の外部キー制約を削除
            $table->dropForeign(['album_id']);  // ここで指定する配列内のキー名は外部キーとして設定されているカラム名

            // 新しい外部キー制約を追加
            $table->foreign('album_id')->references('id')->on('galleries')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            // 外部キー制約を元に戻す
            $table->foreign('album_id')->references('id')->on('galleries')->onDelete('restrict'); // 元々の設定に依存
        });
    }
};
