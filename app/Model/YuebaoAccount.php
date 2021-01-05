<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int   $user_id
 * @property float $balance
 * @property int   $withdraw_time
 */
class YuebaoAccount extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'user_id';
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'yuebao_account';
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
        'user_id'       => 'integer',
        'balance'       => 'float',
        'withdraw_time' => 'integer'
    ];
}