<?php

namespace App\Services\Comment;

use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use Illuminate\Http\Request;

interface ICommentService
{
    public static function getAllCommentsByPost(Request $request);
    public static function createComment(StoreCommentRequest $request);
    public static function getCommentById($id);
    public static function updateComment(UpdateCommentRequest $request, $id);
    public static function deleteComment(Request $request, $id);
}
