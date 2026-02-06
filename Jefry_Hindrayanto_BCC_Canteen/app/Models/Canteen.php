<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Canteen extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'description'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}
