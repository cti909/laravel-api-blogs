<?php

namespace App\Constants;

class GlobalConstant
{
    /**
     * Tên middleware xác thực
     */
    static string $AUTH_MIDDLEWARE = "auth:api";
    /**
     * Tên token xác thực
     */
    static string $AUTH_TOKEN = "authToken";
    /**
     * Thời gian chuẩn UTC
     */
    static string $TIMEZONE_UTC = "UTC";
    /**
     * Định dạng chuyển từ timestamp sang datetime
     */
    static string $FORMAT_TIMESTAMP = "Y-m-d\TH:i:s.u\Z";
    /**
     * Định dạng ngày tháng năm
     */
    static string $FORMAT_DATETIME = "d/m/Y H:i:s A";
    /**
     * Định dạng ngày tháng năm trong CSDL
     */
    static string $FORMAT_DATETIME_DB = "Y-m-d H:i:s";
    /**
     * Định dạng múi giờ
     */
    static string $FORMAT_TIMEZONE = "Asia/Ho_Chi_Minh";
    /**
     * Đặt giá trị mặc định cho guard trong phân quyền
     */
    static string $GUARD_API = "api";
}
