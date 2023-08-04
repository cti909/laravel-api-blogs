<?php

namespace App\Http\Filters;

use Illuminate\Http\Request;

class BaseFilter
{
    /**
     * Định nghĩa thuộc tính cho phép các trường và phép lọc
     */
    protected $allowColumns = [];
    /**
     * Định nghĩa thuộc tính ánh xạ với các trường trong CSDL
     */
    protected $columnsMap = [];
    /**
     * Định nghĩa phép toán so sánh trong CSDL
     */
    protected $operatorsMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
        'neq' => '!=',
        'like' => 'like',
    ];
    public function transformColumn(Request $request, $table, $col = null)
    {
        $column = $col;
        if ($request->has('column')) {
            $column = $col ?? $request->column;
            $newColumn = $column;
            for ($i = 0; $i < strlen($column); $i++) {
                if (ctype_upper($column[$i])) {
                    $newColumn = str_replace($column[$i], "_" . $column[$i], $newColumn);
                }
            }
            $column = $newColumn;
            $column = strtolower($column);
        } else {
            $column = 'id';
        }
        return $table . $column;
    }
    public function transformWhere(Request $request, $table)
    {
        $operatorArray = [];
        if ($request->has('where')) {
            $operatorSplice = explode(',', $request->where);
            for ($i = 0; $i < count($operatorSplice); $i++) {
                $parts1 = explode("[", $operatorSplice[$i]);
                $parts2 = explode("]", $parts1[1]);
                $column = $this->transformColumn($request, "", $parts1[0]); // Tên cột là phần tử đầu tiên
                $operator_str = trim($parts2[0]);
                $value = ($parts2[1]);
                // kiểm tra tên cột có đúng không
                // if ($model->isColumn($column)) {
                //     return null;
                // }
                // operator mặc đinh là dấu =
                $operator = '=';
                foreach ($this->operatorsMap as $k => $v) {
                    if ($operator_str === 'like') {
                        $operator = 'like';
                        $value = '%' . $value . '%';
                        break;
                    }
                    if ($operator_str === $k) {
                        $operator = $v;
                    }
                }
                array_push($operatorArray, [$table . $column, $operator, $value]);
            }
            // dd($operatorArray);
        } else {
            $operatorArray = [];
        }
        return $operatorArray;
    }
    public function transformRelations(Request $request)
    {
        if ($request->has('relations')) {
            $relations = explode(",", $request->relations);
        } else {
            $relations = [];
        }
        return $relations;
    }
}
