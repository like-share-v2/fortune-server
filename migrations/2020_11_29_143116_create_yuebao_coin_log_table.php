<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateYuebaoCoinLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('yuebao_coin_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedTinyInteger('type')->comment('日志类型: 1=转入;2=转出;3=收益');
            $table->decimal('amount', 11, 2)->comment('操作金额');
            $table->unsignedDecimal('balance', 11, 2)->comment('余额');
            $table->unsignedInteger('record_time')->comment('记录时间');
            $table->string('remark', 255)->comment('备注');

            $table->index(['user_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yuebao_coin_log');
    }
}
