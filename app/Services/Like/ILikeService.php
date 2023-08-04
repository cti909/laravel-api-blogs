<?php

namespace App\Services\Like;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use Illuminate\Http\Request;

interface ILikeService
{
    public static function likeObjectAdd(Request $request);
    public static function likeObjectDel(Request $request);
}
