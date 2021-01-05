<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int    $id
 * @property int    $country_id
 * @property int    $parent_id
 * @property int    $type
 * @property int    $level
 * @property int    $effective_time
 * @property string $account
 * @property string $password
 * @property string $trade_pass
 * @property string $country_code
 * @property string $phone
 * @property string $email
 * @property string $nickname
 * @property string $avatar
 * @property int    $gender
 * @property float  $balance
 * @property int    $integral
 * @property int    $credit
 * @property int    $status
 * @property string $ip
 * @property int    $last_login_time
 * @property int    $created_at
 * @property int    $updated_at
 */
class User extends Model
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
    protected $table = 'user';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'              => 'integer',
        'country_id'      => 'integer',
        'parent_id'       => 'integer',
        'type'            => 'integer',
        'level'           => 'integer',
        'effective_time'  => 'integer',
        'gender'          => 'integer',
        'balance'         => 'float',
        'integral'        => 'integer',
        'credit'          => 'integer',
        'status'          => 'integer',
        'last_login_time' => 'integer',
        'created_at'      => 'datetime',
        'updated_at'      => 'datetime'
    ];

    /**
     * @param $value
     *
     * @return false|null|string
     */
    public function getAvatarAttribute($value)
    {
        return $value !== null ? config('static_url') . $value : null;
    }
}