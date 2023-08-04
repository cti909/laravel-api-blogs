<?php

namespace App\Services\Post;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use Illuminate\Http\Request;

interface IPostService
{
    public static function getAllPosts(Request $request);
    public static function createPost(StorePostRequest $request);
    public static function getPostById(Request $request, $id);
    public static function updatePost(UpdatePostRequest $request, $id);
    public static function deletePost(Request $request, $id);
}
