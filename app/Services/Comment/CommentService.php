<?php

namespace App\Services\Comment;

use App\Http\Filters\BaseFilter;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Repositories\Comment\ICommentRepository;
use App\Repositories\Like\LikeRepository;
use Illuminate\Http\Request;

class CommentService implements ICommentService
{
    private static $commentRepository;
    private static $filter;
    /**
     * Construct
     */
    public function __construct(ICommentRepository $commentRepository)
    {
        self::$commentRepository = $commentRepository;
        self::$filter = new BaseFilter;
    }
    /**
     * Lấy tất cả các record của note có phân trang và lọc
     */
    public static function getAllCommentsByPost(Request $request)
    {
        //url http://127.0.0.1:8000/api/comments?page=1&column=updatedAt&sortType=asc&where=content[like]a,postId[eq]1
        // Xử  lý định dạng cột
        $column = self::$filter->transformColumn($request, "comments.");
        // Xử lý điều kiện trong where
        $where = self::$filter->transformWhere($request, "comments.");
        // dd($where);
        // Xử lý quan hệ trong relations
        $relations = self::$filter->transformRelations($request);
        // Xử lý các trường không có giá trị
        $page = $request->page ?? 1;
        $page = $request->page ?? 1;
        $sortType = $request->sortType ?? 'asc';
        $limit = intval($request->limit ?? 10);
        $userId = $request->input("userId") ?? 0;
        // dd($column );
        return self::$commentRepository->findAllCommentsByPost([
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
     * Tạo mới note
     */
    public static function createComment(StoreCommentRequest $request)
    {
        $postId = $request->input("post_id");
        $pathComment = $request->input("path"); // path_comment=0000 -> create new
        $content = $request->input("content");
        $userId = $request->input("user_id");

        $query = self::$commentRepository->inforCommentByPost($postId);
        $isExistComment = $query[0]->comments_count == 0 ? false : true;
        $pathRootLastest = $query[0]->comments_path;
        $pathCurrent = "";
        $pathLengthCurrent = 1;
        // dd($query);

        if (!$isExistComment) {
            // chua co comment
            $pathCurrent = "0001";
            $pathLengthCurrent = 1;
        } else {
            // ton tai comment
            if ($pathComment == "0000") {
                // -> lay 4 ki tu dau cua comment cuoi + 1
                $arrayPath = explode(".", $pathRootLastest);
                $pathCurrentNumber = intval($arrayPath[0]) + 1;
                $pathCurrent = sprintf("%04d", $pathCurrentNumber); // so thanh chuoi
                // dd($pathCurrent);
            } else {
                // ton tai comment cha
                $arrayPath = explode(".", $pathComment);
                // $pathRoot = $arrayPath[0];
                $pathLengthCurrent = count($arrayPath) + 1;
                $querySub = self::$commentRepository->inforCommentByPost($postId, $pathComment, $pathLengthCurrent + 1);
                $isExistLastestComment = $query[0]->comments_count == 0 ? false : true;
                $pathLastestByPathLength = $querySub[0]->comments_path; // lay lastest path cua path length hien tai
                if ($isExistLastestComment) {
                    // -> xu ly phan cuoi path cuoi
                    $pathTempNumber = substr($pathLastestByPathLength, -4);
                    $pathCurrentNumber = intval($pathTempNumber) + 1;
                    $pathCurrent = $pathComment . "." . sprintf("%04d", $pathCurrentNumber);
                    // dd($pathCurrent);
                } else {
                    $pathCurrent = $pathComment . ".0001";
                }
            }
        }
        // dd("result " . $pathCurrent);
        $request_data = [
            "content" => $content,
            "post_id" => $postId,
            "creator_id" => $userId,
            "path" => $pathCurrent,
            "path_length" => $pathLengthCurrent
        ];

        return self::$commentRepository->create($request_data);
    }
    /**
     * Lấy chi tiết record
     */
    public static function getCommentById($id)
    {
        return self::$commentRepository->findById($id);
    }
    /**
     * Cập nhật lại record bởi id
     */
    public static function updateComment(UpdateCommentRequest $request, $id)
    {
        return self::$commentRepository->update($request->input(), $id);
    }
    /**
     * Xóa record bởi id
     */
    public static function deleteComment(Request $request, $id)
    {
        // url http://127.0.0.1:8000/api/comments/1?path=0001&postId=1
        $likeRepository = new LikeRepository();
        $listCommentID = self::$commentRepository->findIdCommentByPath($request->input("postId"), $request->input("path"));
        // dd($request->input("path"));
        foreach ($listCommentID as $item) {
            $listLikeId = $likeRepository->findLikeIdByObject($item->id, 2);
            // dd($listCommentID);
            for ($i = 0; $i < count($listLikeId); $i++) {
                $likeRepository->destroy($listLikeId[$i]);
            }
            $data = self::$commentRepository->destroy($item->id);
        }
        return "success";
    }
}
