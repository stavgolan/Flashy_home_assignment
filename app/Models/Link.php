<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'target_url',
        'is_active'
    ];

    /**
     * Link has many link hits
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hits(){
        return $this->hasMany(LinkHit::class);
    }
}
