<?php

namespace App\Http\Controllers\APIs;

use App\Constants\MessageConstant;
use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResponse;
use App\Repositories\Like\LikeRepository;
use App\Services\Like\LikeService;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    use BaseResponse;
    public function __construct()
    {
        new LikeService(new LikeRepository());
    }
    public function likeObjectAdd(Request $request)
    {
        try {
            $data = LikeService::likeObjectAdd($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$CREATE_LIKE_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$CREATE_LIKE_FAILED
            );
        }
    }
    public function likeObjectDel(Request $request)
    {
        try {
            $data = LikeService::likeObjectDel($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$DELETE_LIKE_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$DELETE_LIKE_FAILED
            );
        }
    }
}
