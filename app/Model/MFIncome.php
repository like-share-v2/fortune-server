<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int   $id
 * @property int   $mf_order_id
 * @property float $amount
 * @property int   $record_time
 */
class MFIncome extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mf_income';
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
        'mf_order_id' => 'integer',
        'amount'      => 'float',
        'record_time' => 'integer'
    ];
}