<?php

namespace App\Http\Responses;

use App\Constants\GlobalConstant;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

trait BaseResponse {
    /**
     * Hàm định nghĩa cấu trúc trả về của API khi ở trạng thái thành công
     */
    public function success(Request $request, mixed $data = null, $message = null,
                            $statusCode = 200, $messageCode = "OK"): JsonResponse {
        $result = [
            'statusMessage' => BaseHTTPResponse::$HTTP[$statusCode] ?? $messageCode,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => empty($data['data']) ? $data : $data['data'],
            'meta' => empty($data['meta']) ? null : $data['meta'],
            'time' => Carbon::now()->format(GlobalConstant::$FORMAT_DATETIME),
            'path' => $request->getRequestUri()
        ];
        // Có phân trang
        if ($data instanceof LengthAwarePaginator || $data instanceof ResourceCollection) {
            try {
                $result = [
                    'statusMessage' => BaseHTTPResponse::$HTTP[$statusCode] ?? $messageCode,
                    'statusCode' => $statusCode,
                    'message' => $message,
                    'data' => $data->items(),
                    'meta' => [
                        'currentPage' => $data->currentPage(), // Trang hiện tại
                        'perPage' => $data->perPage(), // Số bản ghi trên 1 trang
                        'totalPages' => $data->lastPage(), // Tổng số trang
                        'totalRecords' => $data->total(), // Tổng số bản ghi
                        'from' => $data->firstItem(), // Bản ghi đầu tiên trên trang hiện tại
                        'to' => $data->lastItem() // Bản ghi cuối cùng trên trang hiện tại
                    ],
                    'time' => Carbon::now()->format(GlobalConstant::$FORMAT_DATETIME),
                    'path' => $request->getRequestUri()
                ];
            } catch (\Throwable $th) {
                return response()->json($result, $statusCode);
            }
        }
        return response()->json($result, $statusCode);
    }
    /**
     * Hàm định nghĩa cấu trúc trả về của API khi ở trạng thái thất bại
     */
    public function error(Request $request, mixed $error = null, $message = null,
                            $statusCode = 500, $messageCode = "Internal Server Error"): JsonResponse {

        $statusCode = (empty(BaseHTTPResponse::$HTTP[$error->getCode()]))? $statusCode: $error->getCode();
        $message = (empty($error->getMessage()))? $message : $error->getMessage();
        return response()->json([
            'statusMessage' => BaseHTTPResponse::$HTTP[$statusCode] ?? $messageCode,
            'statusCode' => $statusCode,
            'message' => $message,
            'time' => Carbon::now()->format(GlobalConstant::$FORMAT_DATETIME),
            'path' => $request->getRequestUri()
        ], $statusCode);
    }
}
