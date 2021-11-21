<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donatur extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'email', 'password', 'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function donations(){
        return $this->hasMany(Donation::class);
    }

    public function getAvatarAttribute($avatar)
    {
        if ($avatar != null) :
            return asset('storage/donaturs/'.$avatar);
        else :
            return 'https://ui-avatars.com/api/?name=' . str_replace(' ', '+', $this->name) . '&background=4e73df&color=ffffff&size=100';
        endif;
    }
}
