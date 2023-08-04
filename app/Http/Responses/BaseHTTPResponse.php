<?php

namespace App\Http\Responses;

class BaseHTTPResponse {
    /**
     * Message trạng thái của response
     */
    static public array $HTTP = [
        200 => "OK",
        201 => "Created",
        202 => "Accepted",
        204 => "No Content",
        400 => "Bad Request",
        401 => "Unauthorized",
        403 => "Forbidden",
        404 => "Not Found",
        402 => "Payment Required",
        405 => "Method Not Allowed",
        422 => "Unprocessable Entity",
        500 => "Internal Server Error",
    ];
    /**
     * Code trạng thái trả về của HTTP
     */
    static public int $OK = 200;
    static public int $CREATED = 201;
    static public int $ACCEPTED = 202;
    static public int $NO_CONTENT = 204;
    static public int $BAD_REQUEST = 400;
    static public int $UNAUTHORIZED = 401;
    static public int $FORBIDDEN = 403;
    static public int $NOT_FOUND = 404;
    static public int $PAYMENT_REQUIRED = 402;
    static public int $METHOD_NOT_ALLOWED = 405;
    static public int $UNPROCESSABLE_ENTITY = 422;
    static public int $INTERNAL_SERVER_ERROR = 500;
}
