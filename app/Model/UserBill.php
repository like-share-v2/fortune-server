<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int    $id
 * @property int    $user_id
 * @property int    $type
 * @property float  $balance
 * @property float  $before_balance
 * @property float  $after_balance
 * @property string $remark
 * @property int    $low_id
 * @property int    $created_at
 * @property int    $updated_at
 */
class UserBill extends Model
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
    protected $table = 'user_bill';
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
        'id'             => 'integer',
        'user_id'        => 'integer',
        'type'           => 'integer',
        'balance'        => 'float',
        'before_balance' => 'float',
        'after_balance'  => 'float',
        'low_id'         => 'integer',
        'created_at'     => 'integer',
        'updated_at'     => 'integer'
    ];
}