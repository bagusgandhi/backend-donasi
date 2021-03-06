<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',
        'image'
    ];

    public function campaigns(){
        return $this->hasMany(Campaign::class);
    }
    public function getImageAttribute($img){
        return asset('storage/categories/'.$img);
    }
}
