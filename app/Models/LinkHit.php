<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkHit extends Model
{
    use HasFactory;

    protected $fillable = [
        'link_id',
        'ip',
        'user_agent',
        'created_at',
    ];

    /**
     * Link hit belongs to link
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function link(){
        return $this->belongsTo(Link::class);
    }
}
