<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'canteen_id',
        'name',
        'price',
        'stock',
        'description'
    ];

    public function canteen()
    {
        return $this->belongsTo(Canteen::class);
    }
}
