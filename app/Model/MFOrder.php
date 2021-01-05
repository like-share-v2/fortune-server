<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int    $id
 * @property int    $user_id
 * @property string $order_no
 * @property string $mode_title
 * @property int    $mode
 * @property int    $income_mode
 * @property float  $daily_interest_rate
 * @property float  $amount
 * @property float  $profit
 * @property int    $unfreeze_time
 * @property int    $is_settle
 * @property int    $settle_time
 * @property int    $created_at
 * @property int    $updated_at
 * @property User   $user
 */
class MFOrder extends Model
{
    /**
     * @var string
     */
    protected $dateFormat = 'U';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mf_order';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                  => 'integer',
        'user_id'             => 'integer',
        'mode'                => 'integer',
        'income_mode'         => 'integer',
        'daily_interest_rate' => 'float',
        'amount'              => 'float',
        'profit'              => 'float',
        'unfreeze_time'       => 'integer',
        'is_settle'           => 'integer',
        'settle_time'         => 'integer',
        'created_at'          => 'integer',
        'updated_at'          => 'integer'
    ];
    /**
     * @var array
     */
    protected $hidden = [
        'id',
        'user_id',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 模式
     *
     * @param $value
     *
     * @return mixed
     */
    public function getModeAttribute($value)
    {
        return [
                1 => __('view.MF_MODE_1'),
                2 => __('view.MF_MODE_2')
            ][$value] ?? $value;
    }

    /**
     * 模式
     *
     * @param $value
     *
     * @return mixed
     */
    public function getIncomeModeAttribute($value)
    {
        return [
                1 => __('view.INCOME_MODE_1'),
                2 => __('view.INCOME_MODE_2')
            ][$value] ?? $value;
    }

    /**
     * 解冻时间
     *
     * @param $value
     *
     * @return mixed
     */
    public function getUnfreezeTimeAttribute($value)
    {
        return (int)$value === 0 ? null : date('m-d H:i', (int)$value);
    }

    /**
     * 结算时间
     *
     * @param $value
     *
     * @return mixed
     */
    public function getSettleTimeAttribute($value)
    {
        return (int)$value === 0 ? null : date('m-d H:i', (int)$value);
    }

    /**
     * 购买时间
     *
     * @param $value
     *
     * @return mixed
     */
    public function getCreatedAtAttribute($value)
    {
        return (int)$value === 0 ? null : date('m-d H:i', (int)$value);
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function getDailyInterestRateAttribute($value)
    {
        return ($value * 100) . '%';
    }
}