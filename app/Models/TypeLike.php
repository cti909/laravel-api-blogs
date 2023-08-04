<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeLike extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name'
    ];

    /**
     * Likes
     */
    function likes()
    {
        return $this->hasMany(Like::class);
    }
}
