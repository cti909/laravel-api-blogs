<?php

namespace App\Services\Category;

use Illuminate\Http\Request;

interface ICategoryService
{
    public static function getAllCategories(Request $request);
}
