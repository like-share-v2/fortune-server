<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateMfModeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mf_mode', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255)->comment('标题');
            $table->unsignedDecimal('daily_interest_rate', 11, 4)->comment('日利率');
            $table->unsignedTinyInteger('mode')->comment('模式: 1=定期;2=活期');
            $table->unsignedInteger('period')->comment('定期模式冻结周期(天)');
            $table->unsignedInteger('min_amount')->comment('最低买入金额: 0=不限制');
            $table->unsignedTinyInteger('income_mode')->comment('收益计算方式: 1=本金;2=本金+利息');
            $table->unsignedTinyInteger('is_enable')->comment('是否启用');
            $table->unsignedInteger('created_at')->comment('创建时间');
            $table->unsignedInteger('updated_at')->comment('更新时间');
            $table->unsignedInteger('deleted_at')->nullable()->default(null)->comment('删除时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mf_mode');
    }
}
