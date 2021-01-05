<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\SoftDeletes;

/**
 * @property int     $id
 * @property string  $title
 * @property float   $daily_interest_rate
 * @property int     $mode
 * @property int     $period
 * @property int     $min_amount
 * @property int     $income_mode
 * @property boolean $is_enable
 * @property int     $created_at
 * @property int     $updated_at
 * @property int     $deleted_at
 */
class MFMode extends Model
{
    use SoftDeletes;
    /**
     * @var string
     */
    protected $dateFormat = 'U';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mf_mode';
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
        'daily_interest_rate' => 'float',
        'mode'                => 'integer',
        'period'              => 'integer',
        'min_amount'          => 'integer',
        'income_mode'         => 'integer',
        'is_enable'           => 'boolean',
        'created_at'          => 'integer',
        'updated_at'          => 'integer',
        'deleted_at'          => 'integer'
    ];
    /**
     * @var array
     */
    protected $hidden = [
        'is_enable',
        'updated_at',
        'deleted_at'
    ];

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
     * 发布时间
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