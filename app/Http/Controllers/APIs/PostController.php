<?php

namespace App\Http\Controllers\APIs;

use App\Constants\MessageConstant;
use App\Http\Controllers\Controller;
use App\Http\Filters\BaseFilter;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Responses\BaseResponse;
use App\Models\Post;
use App\Repositories\Post\PostRepository;
use App\Services\Post\PostService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    use BaseResponse;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        new PostService(new PostRepository());
    }
    public function getImageUrl($imageName)
    {
        $imageUrl = asset('media/posts/' . $imageName);
        return $imageUrl;
    }
    public function index(Request $request)
    {
        try {
            $data = PostService::getAllPosts($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_LIST_POSTS_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_LIST_POSTS_FAILED
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {
            $create = PostService::createPost($request);
            $data = PostService::getAllPosts($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$CREATE_POST_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$CREATE_POST_FAILED
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        try {
            $data = PostService::getPostById($request, $id);
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_DETAIL_POST_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_DETAIL_POST_FAILED
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id)
    {
        try {
            // $post = Post::findOrFail($id);
            // if (Auth::user()->id != $note->user_id)
            //     throw new Exception("User doesn't have permission to edit");
            $update_data = PostService::updatePost($request, $id);
            $data = PostService::getAllPosts($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$UPDATE_POST_SUCCESS,
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$UPDATE_POST_FAILED,
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
            $delete_data = PostService::deletePost($request, $id);
            $data = PostService::getAllPosts($request);
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
