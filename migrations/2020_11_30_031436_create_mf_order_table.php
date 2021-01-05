<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateMfOrderTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mf_order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id')->index()->comment('用户ID');
            $table->string('order_no')->comment('订单号');
            $table->string('mode_title', 255)->comment('模式标题');
            $table->unsignedTinyInteger('mode')->comment('模式: 1=定期;2=活期');
            $table->unsignedTinyInteger('income_mode')->comment('收益计算方式: 1=本金;2=本金+利息');
            $table->unsignedDecimal('daily_interest_rate', 11, 4)->comment('日利率');
            $table->unsignedDecimal('amount', 11, 2)->comment('本金');
            $table->unsignedDecimal('profit', 11, 2)->comment('当前收益');
            $table->unsignedInteger('unfreeze_time')->default(0)->comment('解冻时间');
            $table->unsignedTinyInteger('is_settle')->index()->comment('是否结算: 0=结算中;1=已结算');
            $table->unsignedInteger('settle_time')->default(0)->comment('结算时间');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mf_order');
    }
}
