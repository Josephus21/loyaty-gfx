<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'form_no',
        'card_no',
        'date',
        'name',
        'gender',
        'email',
        'address',
        'dob',
        'phone',
         'system_pk',
        'user_id' 
    ];
    

    public function user()
{
    return $this->belongsTo(User::class);
}

public function points()
    {
        return $this->hasMany(Point::class, 'member_id');
    }

}
