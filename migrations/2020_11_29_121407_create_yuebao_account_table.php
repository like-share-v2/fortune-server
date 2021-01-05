<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateYuebaoAccountTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('yuebao_account', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->unique()->comment('用户ID');
            $table->unsignedDecimal('balance', 11, 2)->comment('余额宝账户余额');
            $table->unsignedInteger('withdraw_time')->default(0)->comment('可提现时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yuebao_account');
    }
}
