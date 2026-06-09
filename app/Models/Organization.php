<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Organization extends Model
{


    protected $fillable = [
        'name',
        'timezone',
        'pm_type',
        'pm_last_four',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }




}
