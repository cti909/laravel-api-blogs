<?php

namespace App\Http\Controllers\APIs;

use App\Constants\MessageConstant;
use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResponse;
use App\Repositories\Category\CategoryRepository;
use App\Services\Category\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use BaseResponse;
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        new CategoryService(new CategoryRepository());
    }
    public function index(Request $request)
    {
        try {
            $data = CategoryService::getAllCategories($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$GET_LIST_CATEGOGIES_SUCCESS
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$GET_LIST_CATEGOGIES_FAILED
            );
        }
    }
}
