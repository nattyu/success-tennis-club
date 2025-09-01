<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) 既存の user_id 外部キーがあれば名前を調べて DROP
        $fkName = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->select('CONSTRAINT_NAME')
            ->whereRaw('TABLE_SCHEMA = DATABASE()')
            ->where('TABLE_NAME', 'post_attendances')
            ->where('COLUMN_NAME', 'user_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->value('CONSTRAINT_NAME');

        if ($fkName) {
            DB::statement("ALTER TABLE `post_attendances` DROP FOREIGN KEY `{$fkName}`");
        }

        // 2) 型の整合性チェック（users.id に合わせて unsignedBigInteger）
        // ※ change() は doctrine/dbal が必要。入っていなければスキップOK。
        Schema::table('post_attendances', function (Blueprint $table) {
            // $table->unsignedBigInteger('user_id')->change(); // 必要なら有効化
        });

        // 3) CASCADE 付きで外部キーを付け直し
        Schema::table('post_attendances', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // 逆マイグレーション：今回付けたFKを落とす（名前を動的に取得）
        $fkName = DB::table('information_schema.KEY_COLUMN_USAGE')
            ->select('CONSTRAINT_NAME')
            ->whereRaw('TABLE_SCHEMA = DATABASE()')
            ->where('TABLE_NAME', 'post_attendances')
            ->where('COLUMN_NAME', 'user_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->value('CONSTRAINT_NAME');

        if ($fkName) {
            DB::statement("ALTER TABLE `post_attendances` DROP FOREIGN KEY `{$fkName}`");
        }

        // 必要なら元の（cascade無し）外部キーを復元
        Schema::table('post_attendances', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};
