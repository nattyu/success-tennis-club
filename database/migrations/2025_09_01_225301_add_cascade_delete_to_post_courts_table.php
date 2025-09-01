<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 既存の user_id FK 名を動的に取得して、あればDROP
        $fkName = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->select('CONSTRAINT_NAME')
            ->whereRaw('TABLE_SCHEMA = DATABASE()')
            ->where('TABLE_NAME', 'post_courts')
            ->where('COLUMN_NAME', 'user_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->value('CONSTRAINT_NAME');

        if ($fkName) {
            DB::statement("ALTER TABLE `post_courts` DROP FOREIGN KEY `{$fkName}`");
        }

        // 型整合（users.id と合わせて unsignedBigInteger）
        // change() を使う場合は doctrine/dbal が必要。既に整合していれば不要。
        Schema::table('post_courts', function (Blueprint $table) {
            // $table->unsignedBigInteger('user_id')->change(); // 必要なら有効化
        });

        // CASCADE 付きで付け直し
        Schema::table('post_courts', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // 付けたFKを落として（名前を再取得）、cascade無しで復元
        $fkName = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->select('CONSTRAINT_NAME')
            ->whereRaw('TABLE_SCHEMA = DATABASE()')
            ->where('TABLE_NAME', 'post_courts')
            ->where('COLUMN_NAME', 'user_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->value('CONSTRAINT_NAME');

        if ($fkName) {
            DB::statement("ALTER TABLE `post_courts` DROP FOREIGN KEY `{$fkName}`");
        }

        Schema::table('post_courts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};
