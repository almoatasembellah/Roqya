<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conclave extends Model
{
    protected $guarded =[];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
