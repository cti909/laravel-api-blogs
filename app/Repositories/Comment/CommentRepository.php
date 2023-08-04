<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use App\Repositories\BaseRepository;
use App\Repositories\Comment\ICommentRepository;
use Illuminate\Support\Facades\DB;

class CommentRepository extends BaseRepository implements ICommentRepository
{
    public function getModel()
    {
        return Comment::class;
    }
    function findAllCommentsByPost(mixed $options)
    {
        // dd($options);
        $query = DB::table('comments')
            ->select(
                'comments.*',
                DB::raw('COUNT(likes.id) as likes_comment_count'),
                DB::raw('MAX(CASE WHEN likes.user_id = '.$options['userId'].' THEN 1 ELSE 0 END) as is_liked'),
                'users.name as creator_name'
            )
            ->where($options['where'])
            ->leftJoin('users', 'users.id', '=', 'comments.creator_id')
            ->leftJoin('likes', function ($join) {
                $join->on('likes.object_id', '=', 'comments.id')
                    ->where('likes.type_id', '=', 2);
            })
            ->orderBy($options['column'], $options['orderBy'])
            ->groupBy('comments.id')
            ->get();
        return $query;
    }

    function findIdCommentByPath($post_id, $path = "%")
    {
        $path_nor = $path . "%";
        $query = DB::table('comments')
            ->select(
                "comments.id"
            )
            ->where([
                ["comments.post_id", "=", $post_id],
                ["comments.path", "like", $path_nor],
            ])
            ->get();
        // dd($query);
        return $query;
    }

    function inforCommentByPost($post_id, $path = "%", $path_length = 1)
    {
        $path_nor = $path . "%";
        $query = DB::table('comments')
            ->select(
                DB::raw('MAX(comments.path) as comments_path'),
                DB::raw('COUNT(comments.id) as comments_count')
            )
            ->where([
                ["comments.post_id", "=", $post_id],
                ["comments.path", "like", $path_nor],
                ["comments.path_length", "=", $path_length]
            ])
            ->get();
        // dd($query);
        return $query;
    }
}
