<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int    $id
 * @property int    $user_id
 * @property int    $type
 * @property float  $amount
 * @property float  $balance
 * @property int    $record_time
 * @property string $remark
 */
class YuebaoCoinLog extends Model
{
    /**
     * @var string
     */
    public const CREATED_AT = 'record_time';
    /**
     * @var string
     */
    public const UPDATED_AT = null;
    /**
     * @var string
     */
    protected $dateFormat = 'U';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'yuebao_coin_log';
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
        'id'          => 'integer',
        'user_id'     => 'integer',
        'type'        => 'integer',
        'amount'      => 'float',
        'balance'     => 'float',
        'record_time' => 'integer'
    ];
    /**
     * @var array
     */
    protected $hidden = [
        'id',
        'user_id',
    ];

    /**
     * 交易类型
     *
     * @param $value
     *
     * @return mixed
     */
    public function getTypeAttribute($value)
    {
        return [
                1 => __('view.TRANSFER_IN'),
                2 => __('view.TRANSFER_OUT'),
                3 => __('view.INCOME'),
            ][$value] ?? $value;
    }

    /**
     * 记录时间
     *
     * @param $value
     *
     * @return mixed
     */
    public function getRecordTimeAttribute($value)
    {
        return (int)$value === 0 ? null : date('m-d H:i', (int)$value);
    }
}