<?php

namespace App\Services\Like;

use App\Http\Filters\BaseFilter;
use App\Repositories\Like\ILikeRepository;
use App\Repositories\Like\LikeRepository;
use Illuminate\Http\Request;

class LikeService implements ILikeService
{
    private static $likeRepository;
    /**
     * Construct
     */
    public function __construct(ILikeRepository $likeRepository)
    {
        self::$likeRepository = $likeRepository;
    }

    public static function likeObjectAdd(Request $request)
    {
        $user_id = $request->input("user_id");
        $object_id = $request->input("object_id");
        $type_id = $request->input("type_id");
        return self::$likeRepository->likeObjectAdd($user_id, $object_id, $type_id);
    }
    /**
     * Xóa record bởi id
     */
    public static function likeObjectDel(Request $request)
    {
        $user_id = $request->input("user_id");
        $object_id = $request->input("object_id");
        $type_id = $request->input("type_id");
        // dd($user_id);
        return self::$likeRepository->likeObjectDel($user_id, $object_id, $type_id);
    }
}
