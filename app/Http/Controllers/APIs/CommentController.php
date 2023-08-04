<?php

namespace App\Http\Controllers\APIs;

use App\Constants\MessageConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Responses\BaseResponse;
use App\Models\Comment;
use App\Repositories\Comment\CommentRepository;
use App\Services\Comment\CommentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use BaseResponse;
    public function __construct()
    {
        new CommentService(new CommentRepository());
    }
    public function index(Request $request)
    {
        try {
            $data = CommentService::getAllCommentsByPost($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_LIST_COMMENTS_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_LIST_COMMENTS_FAILED
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        try {

            $create = CommentService::createComment($request);
            $data = CommentService::getAllCommentsByPost($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$CREATE_COMMENT_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$CREATE_COMMENT_FAILED
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            $data = CommentService::getCommentById($id);
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_DETAIL_COMMENT_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_DETAIL_COMMENT_FAILED
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, string $id)
    {
        try {
            $comment = Comment::findOrFail($id);
            // if (Auth::user()->id != $comment->creator_id)
            //     throw new Exception("User doesn't have permission to edit");
            $update_data = CommentService::updateComment($request, $id);
            $data = CommentService::getAllCommentsByPost($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$UPDATE_COMMENT_SUCCESS,
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$UPDATE_COMMENT_FAILED,
                400,
                'Bad Request'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $delete_data = CommentService::deleteComment($request, $id);
            $data = CommentService::getAllCommentsByPost($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$DELETE_POST_SUCCESS,
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$DELETE_POST_FAILED,
                400,
                'Bad Request'
            );
        }
    }
}
