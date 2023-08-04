<?php

namespace App\Services\Post;

use App\Http\Filters\BaseFilter;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Repositories\Like\LikeRepository;
use App\Repositories\Post\IPostRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class PostService implements IPostService
{
    private static $postRepository;
    private static $filter;
    /**
     * Construct
     */
    public function __construct(IPostRepository $postRepository)
    {
        self::$postRepository = $postRepository;
        self::$filter = new BaseFilter;
    }
    /**
     * Lấy tất cả các record của post có phân trang và lọc
     */
    public static function getAllPosts(Request $request)
    {
        // url: {route root}/api/posts?where=id[gt]5,userId[eq]2&page=1&column=createdAt&sortType=desc&limit=2
        // Xử  lý định dạng cột
        $column = self::$filter->transformColumn($request, "posts.");
        // Xử lý điều kiện trong where
        $where = self::$filter->transformWhere($request, "posts.");
        // dd($where);
        // Xử lý quan hệ trong relations
        $relations = self::$filter->transformRelations($request);
        // Xử lý các trường không có giá trị
        $page = $request->page ?? 1;
        $sortType = $request->sortType ?? 'asc';
        $limit = intval($request->limit ?? 10);
        $userId = $request->input("userId") ?? 0;
        // dd($column );
        return self::$postRepository->findAll([
            'where' => $where, // điều kiện
            'relations' => $relations, // bảng truy vấn
            'column' => $column, // cột để sort
            'orderBy' => $sortType,
            'limit' => $limit,  // giới hạn record/page
            'page' => $page, // page cần lấy
            'userId' => $userId
        ]);
    }
    /**
     * Tạo mới post
     */
    public static function createPost(StorePostRequest $request)
    {
        // dd($request->input());
        $image_name = null;
        // param1 image, param2 folder in public/media to save image; return image name
        if ($request->hasFile('photo')) {
            $image_name = BaseService::renameImage($request->file('photo'), "posts");
            // get url image, resize this and save
            BaseService::resizeImage("posts", $image_name);
            // $image_name = 'media/posts/' . $image_name;
        }
        $data = [
            'content' => $request->input('content'),
            'photo' => $image_name,
            'creator_id' => $request->input("creator_id"),
            'category_id' => $request->input('category_id'),
        ];
        // dd($data);
        return self::$postRepository->create($data);
    }
    /**
     * Lấy chi tiết record
     */
    public static function getPostById(Request $request, $id)
    {
        $userId = $request->input("userId") ?? 0;
        return self::$postRepository->findAll([
            'where' => [["posts.id", "=", $id]], // điều kiện
            'column' => "posts.id", // cột để sort
            'orderBy' => "asc",
            'limit' => 10,  // giới hạn record/page
            'page' => 1, // page cần lấy
            'userId' => $userId
        ]);
        // return self::$postRepository->findById($id);
    }
    /**
     * Cập nhật lại record bởi id
     */
    public static function updatePost(UpdatePostRequest $request, $id)
    {
        // param1 image, param2 folder in public/media to save image; return image name
        $data = [];
        if ($request->hasFile('photo')) {
            $image_name = BaseService::renameImage($request->file('photo'), "posts");
            // get url image, resize this and save
            BaseService::resizeImage("posts", $image_name);
            // $image_name = asset('media/posts/' . $image_name);
            $data = [
                'content' => $request->input('content'),
                'photo' => $image_name
            ];
        } else {
            $data = [
                'content' => $request->input('content')
            ];
        }
        // dd($data);
        return self::$postRepository->update($data, $id);
    }
    /**
     * Xóa record bởi id
     */
    public static function deletePost($request, $id)
    {
        // $userId = $request->input("userId") ?? 0;
        // return self::$postRepository->findAll([
        //     'where' => [["posts.id", "=", $id]], // điều kiện
        //     'column' => "posts.id", // cột để sort
        //     'orderBy' => "asc",
        //     'limit' => 10,  // giới hạn record/page
        //     'page' => 1, // page cần lấy
        //     'userId' => $userId
        // ]);
        $likeRepository = new LikeRepository();
        $listLikeId = $likeRepository->findLikeIdByObject($id, 1);
        for ($i = 0; $i < count($listLikeId); $i++) {
            $likeRepository->destroy($listLikeId[$i]);
        }
        return self::$postRepository->destroy($id);
    }
}
