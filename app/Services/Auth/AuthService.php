<?php

namespace App\Services\Auth;

use App\Constants\GlobalConstant;
use App\Constants\MessageConstant;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\BaseHTTPResponse;
use App\Mail\OtpVerification;
use App\Repositories\Auth\IAuthRepository;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AuthService extends BaseService implements IAuthService
{
    private static $authRepository;
    private static $filter;
    /**
     * Construct
     */
    public function __construct(IAuthRepository $authRepository)
    {
        self::$authRepository = $authRepository;
    }
    public static function me()
    {
        return self::$authRepository->me();
    }
    public static function login(LoginRequest $request)
    {
        $input = $request->input();
        $isLogin = Auth::attempt($input) ? true : false;
        // dd($isLogin);
        // nếu đăng nhập thành công thì
        // tạo RA 1 TOKEN để gửi về client thông qua jwt
        // khi người dùng đưa lên mà không đúng thì 400 -> BadRequest
        $user = null;
        $token = "";
        if ($isLogin) {
            $user = Auth::user();
            $token = JWTAuth::fromUser($user);
        }
        $data = [
            "isLogin" => $isLogin,
            "user" => $user,
            "token" => $token,
        ];
        return $data;
    }
    public static function register(RegisterRequest $request)
    {
        $image_name = null;
        if ($request->hasFile('avatar')) {
            $image_name = self::renameImage($request->file('avatar'), "posts");
            self::resizeImage($folder = "posts", $image_name);
        }
        $request->merge(['avatar' => $image_name]);
        return self::$authRepository->register($request->input());
    }
    // public static function sendOtpEmail(EmailVerificationRequest $request)
    // {
    //     $email = $request->email;
    //     $user = self::$authRepository->findOne([
    //         'where' => [
    //             'email' => $email
    //         ],
    //         'relations' => []
    //     ]);
    //     // Kiểm tra xác thực người dùng, ví dụ: kiểm tra email và mật khẩu
    //     if (empty($user)) {
    //         throw new \Exception(
    //             MessageConstant::$MEMBER_NOT_EXIST,
    //             BaseHTTPResponse::$NOT_FOUND
    //         );
    //     }
    //     // kiểm tra thành viên đã xác thực email chưa
    //     if ($user['email_verified_at'] !== null) {
    //         throw new \Exception(
    //             MessageConstant::$MEMBER_VERIFIED_EMAIL,
    //             BaseHTTPResponse::$BAD_REQUEST
    //         );
    //     }
    //     // Tạo mã OTP ngẫu nhiên
    //     $otp = mt_rand(100000, 999999);
    //     $data = [
    //         'user' => $user,
    //         'otp' => $otp
    //     ];

    //     $userEmail = $request->input('email');

    //     $mail = new PHPMailer(true);
    //     // Cấu hình SMTP
    //     $mail->isSMTP();
    //     $mail->Host = env('MAIL_HOST');
    //     $mail->Port = env('MAIL_PORT');
    //     $mail->SMTPSecure = env('MAIL_ENCRYPTION');
    //     $mail->SMTPAuth = true;
    //     $mail->Username = env('MAIL_USERNAME');
    //     $mail->Password = env('MAIL_PASSWORD');
    //     // Thiết lập thông tin email
    //     $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    //     $mail->addAddress($userEmail);
    //     $mail->Subject = 'Welcome to MyApp';
    //     // Render blade thành chuỗi HTML
    //     $htmlContent = view('mails.verify', $data)->render();
    //     $mail->msgHTML($htmlContent);
    //     // Gửi email
    //     $mail->send();
    //     // update otp code to db
    //     $request->merge(['otp_email_code' => $otp]);
    //     $request->merge(['otp_email_expired_at' => Carbon::now()]);
    //     self::$authRepository->update($request->input(), $user->id);
    //     return $data;
    // }
    // /**
    //  * Xác thực gmail bằng otp
    //  */
    // public static function emailVerification(EmailVerificationRequest $request)
    // {
    //     $email = $request->email;
    //     $otp = $request->otp;
    //     $user = self::$authRepository->findOne([
    //         'where' => [
    //             'email' => $email
    //         ],
    //         'relations' => []
    //     ]);
    //     // Kiểm tra xác thực người dùng, ví dụ: kiểm tra email và mật khẩu
    //     if (empty($user)) {
    //         throw new \Exception(
    //             MessageConstant::$MEMBER_NOT_EXIST,
    //             BaseHTTPResponse::$NOT_FOUND
    //         );
    //     }
    //     // kiểm tra thành viên đã xác thực email chưa
    //     if ($user['email_verified_at'] !== null) {
    //         throw new \Exception(
    //             MessageConstant::$MEMBER_VERIFIED_EMAIL,
    //             BaseHTTPResponse::$BAD_REQUEST
    //         );
    //     }
    //     $currentDateTime = Carbon::now();

    //     if (
    //         $otp == $user->otp_email_code
    //         && $currentDateTime->diffInMinutes($user->otp_email_expired_at) < 10
    //     ) {
    //         $request->merge(['email_verified_at' => now()]);
    //         self::$authRepository->update(['email_verified_at' => now()], $user->id);
    //     }
    //     return "success";
    // }
}
