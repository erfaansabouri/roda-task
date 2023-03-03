<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';
    protected $guarded = [];

    public function movements()
    {
        return $this->hasMany(Movement::class)->orderBy('timestamp');
    }

    public function getPlanImageUrlAttribute()
    {
        return asset("user-data/" . $this->plan_image);
    }
}
