<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'id',
        'content',
        'post_id',
        'creator_id',
        'path',
        'path_length',
        'created_at',
        'updated_at'
    ];

    /**
     * Post
     */
    function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * User
     */
    function user()
    {
        return $this->belongsTo(User::class);
    }
}
