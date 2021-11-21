<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Donation extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice',
        'campaign_id',
        'donatur_id',
        'amount',
        'pray',
        'snap_token',
        'status'
    ];

    public function campaign(){
        return $this->belongsTo(Campaign::class);
    }

    public function donatur(){
        return $this->belongsTo(Donatur::class);
    }
    public function getCreatedAttribute($date){
        return Carbon::parse($date)->format('d-m-y');
    }

    public function getUpdateAttribute($date){
        return Carbon::parse($date)->format('d-m-y');
    }

}
