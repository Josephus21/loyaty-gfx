<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $fillable = [
    'bill_no',
    'bill_amount',
    'points',
    'member_id',
    'user_id',
];


    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, );
    }
    
}
