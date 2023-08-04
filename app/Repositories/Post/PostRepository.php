<?php

namespace App\Repositories\Post;

use App\Models\Post;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class PostRepository extends BaseRepository implements IPostRepository
{

    public function getModel()
    {
        return Post::class;
    }
    function findAll(mixed $options)
    {
        // dd($options);
        $query = DB::table('posts')
            ->select(
                'posts.*',
                DB::raw('COUNT(DISTINCT comments.id) as comments_count'), // khong phai field
                DB::raw('COUNT(DISTINCT likes.id) as likes_post_count'),
                DB::raw('MAX(CASE WHEN likes.user_id = '.$options['userId'].' THEN 1 ELSE 0 END) as is_liked'),
                'users.name as creator_name',
            )
            ->where($options['where'])
            ->leftJoin('users', 'users.id', '=', 'posts.creator_id')
            ->leftJoin('comments', 'comments.post_id', '=', 'posts.id')
            ->leftJoin('likes', function ($join) {
                $join->on('likes.object_id', '=', 'posts.id')
                    ->where([['likes.type_id', '=', 1]]);
            })
            ->orderBy($options['column'], $options['orderBy'])
            ->groupBy('posts.id')
            ->paginate($options['limit']);
        return $query;
    }
}
