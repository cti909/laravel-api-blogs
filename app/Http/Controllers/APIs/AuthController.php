<?php

namespace App\Http\Controllers\APIs;

use App\Constants\GlobalConstant;
use App\Constants\MessageConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\BaseHTTPResponse;
use App\Http\Responses\BaseResponse;
use App\Models\User;
use App\Repositories\Auth\AuthRepository;
use App\Services\Auth\AuthService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;


class AuthController extends Controller
{
    use BaseResponse;
    public function __construct()
    {
        new AuthService(new AuthRepository());
        // except la loai tru ra
        $this->middleware('auth:api', ['except' => ['login', 'register', 'me']]);
    }
    // public function me(Request $request): JsonResponse
    // {
    //     try {
    //         $user = null;
    //         $data = null;
    //         // Lấy giá trị của header "Authorization"
    //         $authHeader = $request->header('Authorization');
    //         // Kiểm tra xem header có chứa từ "Bearer " không
    //         if (strpos($authHeader, 'Bearer ') === 0) {
    //             // Lấy JWT token bằng cách loại bỏ phần "Bearer " khỏi header
    //             $jwtToken = substr($authHeader, 7);
    //             try {
    //                 $user = JWTAuth::parseToken()->authenticate($jwtToken);
    //                 // ...
    //             } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
    //                 // ...
    //             } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
    //                 // ...
    //             } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
    //                 // ...
    //             }
    //         }
    //         if ($user) {
    //             $data = User::findOrFail($user->id);
    //         }
    //         return $this->success(
    //             $request,
    //             $data,
    //             "Get auth sucess"
    //         );
    //     } catch (\Throwable $th) {
    //         return $this->error(
    //             $request,
    //             $th,
    //             "Get auth failed"
    //         );
    //     }
    // }
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = AuthService::login($request);
            if (!$data["isLogin"]) {
                $statusCode = 400;
                return response()->json([
                    'statusMessage' => BaseHTTPResponse::$HTTP[$statusCode],
                    'statusCode' => $statusCode,
                    'message' => 'Email or password is wrong',
                    'time' => Carbon::now()->format(GlobalConstant::$FORMAT_DATETIME),
                    'path' => $request->getRequestUri()
                ], $statusCode);
            } else {
                $statusCode = 200;
                return response()->json([
                    'statusMessage' => BaseHTTPResponse::$HTTP[$statusCode],
                    'statusCode' => $statusCode,
                    'message' => 'Login success',
                    'data' => $data["user"],
                    'meta' => $data["token"],
                    'time' => Carbon::now()->format(GlobalConstant::$FORMAT_DATETIME),
                    'path' => $request->getRequestUri()
                ], $statusCode);
            }
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$REGISTER_FAILED
            );
        }
    }
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $data = AuthService::register($request);
            return $this->success(
                $request,
                $data,
                MessageConstant::$REGISTER_SUCCESS,
                201,
                BaseHTTPResponse::$HTTP[201]
            );
        } catch (\Throwable $th) {
            return $this->error(
                $request,
                $th,
                MessageConstant::$REGISTER_FAILED
            );
        }
    }
    // public function sendOtpEmail(EmailVerificationRequest $request): JsonResponse
    // {
    //     try {
    //         $data = AuthService::sendOtpEmail($request);
    //         return $this->success(
    //             $request,
    //             $data,
    //             MessageConstant::$SEND_OTP_SUCCESS,
    //             201,
    //             BaseHTTPResponse::$HTTP[201]
    //         );
    //     } catch (\Throwable $th) {
    //         return $this->error(
    //             $request,
    //             $th,
    //             MessageConstant::$SEND_OTP_FAILED
    //         );
    //     }
    // }
    // public function emailVerification(EmailVerificationRequest $request): JsonResponse
    // {
    //     try {
    //         $data = AuthService::emailVerification($request);
    //         return $this->success(
    //             $request,
    //             $data,
    //             MessageConstant::$EMAIL_VERIFICATION_SUCCESS,
    //             201,
    //             BaseHTTPResponse::$HTTP[201]
    //         );
    //     } catch (\Throwable $th) {
    //         return $this->error(
    //             $request,
    //             $th,
    //             MessageConstant::$EMAIL_VERIFICATION_FAILED
    //         );
    //     }
    // }
}
