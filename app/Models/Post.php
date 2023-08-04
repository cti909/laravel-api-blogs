<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'content',
        'photo',
        'creator_id',
        'category_id',
        'created_at',
        'updated_at'
    ];

    /**
     * User
     */
    function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Catgegory
     */
    function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Comments
     */
    function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
