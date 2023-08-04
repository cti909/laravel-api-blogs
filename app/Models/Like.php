<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Like extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'user_id',
        'object_id',
        'type_id'
    ];

    /**
     * TypeLIke
     */
    function typeLike()
    {
        return $this->belongsTo(TypeLike::class);
    }

    /**
     * User
     */
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
