<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;



#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function threads()
    {
        return $this->hasMany(Threads::class, 'user_id');
    }

    public function assignedThreads()
    {
        return $this->hasMany(Threads::class, 'assigned_to');
    }

    public function deals()
    {
        return $this->hasMany(Deals::class, 'user_id');
    }

    public function ownedDeals()
    {
        return $this->hasMany(Deals::class, 'owner_id');
    }

    public function userInfo()
    {
        return $this->hasOne(UserInfo::class);
    }
}
