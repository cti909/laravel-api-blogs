<?php

namespace App\Services\User;

use App\Http\Controllers\APIs\UserController;
use App\Http\Filters\User\UserFilter;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Repositories\User\IUserRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class UserService extends BaseService implements IUserService
{
    private static $userRepository;
    private static $filter;
    /**
     * Construct
     */
    public function __construct(IUserRepository $userRepository)
    {
        self::$userRepository = $userRepository;
        self::$filter = new UserFilter;
    }
    /**
     * Lấy tất cả các record của note có phân trang và lọc
     */
    public static function getAllUsers(Request $request)
    {
        // url: {route root}/api/users?where=id[gt]5,userId[eq]2&page=1&column=createdAt&sortType=desc&limit=2
        // Xử  lý định dạng cột
        $column = self::$filter->transformColumn($request);
        // Xử lý điều kiện trong where
        $where = self::$filter->transformWhere($request);
        // Xử lý quan hệ trong relations
        $relations = self::$filter->transformRelations($request);
        // Xử lý các trường không có giá trị
        $page = $request->page ?? 1;
        $sortType = $request->sortType ?? 'asc';
        $limit = $request->limit ?? 10;

        return self::$userRepository->findAll([
            'where' => $where, // điều kiện
            'relations' => $relations, // bảng truy vấn
            'column' => $column, // cột để sort
            'orderBy' => $sortType,
            'limit' => $limit,  // giới hạn record/page
            'page' => $page // page cần lấy
        ]);
    }
    /**
     * Lấy chi tiết record
     */
    public static function getUserById($id)
    {
        return self::$userRepository->findById($id);
    }
    /**
     * Cập nhật lại record bởi id
     */
    public static function updateUser(UpdateUserRequest $request, $id)
    {
        $image_name = null;
        if ($request->hasFile('image')) {
            $image_name = self::renameImage($request->file('image'), "notes");
            self::resizeImage($folder = "notes", $image_name);
        }
        $request->mergeWith(['image' => $image_name]);
        return self::$userRepository->updateUser($request->input(), $id);
    }
    /**
     * Xóa record bởi id
     */
    public static function deleteUser($id)
    {
        return self::$userRepository->destroy($id);
    }
    
}
