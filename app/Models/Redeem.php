<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redeem extends Model
{
    protected $table = 'redemptions';

    protected $fillable = [
        'member_id',
        'reward_id',
        'code',
        'status'
    ];

    public function member()
    {
        return $this->belongsTo(\App\Models\Member::class);
    }

    public function reward()
    {
        return $this->belongsTo(\App\Models\Reward::class);
    }
}
