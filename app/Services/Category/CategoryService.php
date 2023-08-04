<?php

namespace App\Services\Category;

use App\Http\Filters\BaseFilter;
use App\Repositories\Category\ICategoryRepository;
use Illuminate\Http\Request;

class CategoryService implements ICategoryService
{
    private static $categoryRepository;
    private static $filter;
    /**
     * Construct
     */
    public function __construct(ICategoryRepository $categoryRepository)
    {
        self::$categoryRepository = $categoryRepository;
        self::$filter = new BaseFilter;
    }
    /**
     * Lấy tất cả các record của note có phân trang và lọc
     */
    public static function getAllCategories(Request $request)
    {
        // Xử  lý định dạng cột
        $column = self::$filter->transformColumn($request, "categories.");
        // Xử lý điều kiện trong where
        $where = self::$filter->transformWhere($request, "categories.");
        // dd($where);
        // Xử lý quan hệ trong relations
        $relations = self::$filter->transformRelations($request);
        // Xử lý các trường không có giá trị
        $page = $request->page ?? 1;
        $page = $request->page ?? 1;
        $sortType = $request->sortType ?? 'asc';
        $limit = intval($request->limit ?? 10);
        // dd($column );
        return self::$categoryRepository->findAll([
            'where' => $where, // điều kiện
            'relations' => $relations, // bảng truy vấn
            'column' => $column, // cột để sort
            'orderBy' => $sortType,
            'limit' => null,  // giới hạn record/page
        ]);
    }
}
