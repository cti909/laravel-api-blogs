<?php

namespace App\Repositories\Like;

use App\Models\Like;
use App\Repositories\BaseRepository;
use App\Repositories\Like\ILikeRepository;
use Illuminate\Support\Facades\DB;

class LikeRepository extends BaseRepository implements ILikeRepository
{

    public function getModel()
    {
        return Like::class;
    }
    function findLikeIdByObject($object_id, $type_id)
    {
        $where = [
            ["object_id", "=", $object_id],
            ["type_id", "=", $type_id],
        ];
        $query = Like::where($where)->get();
        $arr = [];
        foreach ($query as $like) {
            array_push($arr, $like->id);
        }
        return $arr;
    }
    /**
     * add like (post or comment) by user
     */
    function likeObjectAdd($user_id, $object_id, $type_id)
    {
        return Like::create([
            'user_id' => $user_id,
            'object_id' => $object_id,
            'type_id' => $type_id,
        ]);
    }

    /**
     * delete like (post or comment) by user
     */
    function likeObjectDel($user_id, $object_id, $type_id)
    {
        $query = Like::where('object_id', "=", $object_id)
            ->where('user_id', "=", $user_id)
            ->where('type_id', "=", $type_id)
            ->delete();
        // dd($query);
        return $query;
    }
}
