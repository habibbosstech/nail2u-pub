<?php

namespace App\Services;

use App\Libs\Response\GlobalApiResponseCodeBook;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use App\Helper\Helper;
use App\Jobs\SendEmailVerificationMail;
use App\Jobs\SendPasswordResetMail;
use App\Models\EmailVerify;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Spatie\Permission\Models\Role;

class AuthService extends BaseService
{
    public function addAdmin($request)
    {
        try {
            DB::beginTransaction();
            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone_no = $request->phone_no;
            $user->image_url = 'storage/profileImages/default-profile-image.png';
            $user->cv_url = '';
            $user->save();

            $setting = new Setting();
            $setting->user_id = $user->id;
            $setting->private_account = 0;
            $setting->secure_payment = 1;
            $setting->sync_contact_no = 0;
            $setting->app_notification = 1;
            $setting->save();

            $admin_role = Role::findByName('admin');
            $admin_role->users()->attach($user->id);

            $verify_email_token = Str::random(140);
            $email_verify = new EmailVerify;
            $email_verify->email = $request->email;
            $email_verify->token = $verify_email_token;
            $email_verify->save();

            $mail_data = [
                'email' => $request->email,
                'token' => $verify_email_token
            ];
            SendEmailVerificationMail::dispatch($mail_data);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: register", $error);
            return false;
        }
    }

    public function login($request)
    {
        try {
            $credentials = $request->only('email', 'password');

            $user = User::whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            })
                ->where('email', '=', $credentials['email'])
                ->first();

            if (
                Hash::check($credentials['password'], isset($user->password) ? $user->password : null)
                &&
                $token = $this->guard()->attempt($credentials)
            ) {
                $roles = Auth::user()->roles->pluck('name');
                $setting = Auth::user()->setting;
                $data = Auth::user()->toArray();
                unset($data['roles']);
                
                $data = [
                    'token' => $token,
                    'user' => Auth::user()->only('id', 'username', 'email', 'phone_no', 'absolute_image_url', 'address'),
                    'roles' => $roles,
                    'setting' => [
                        'dashboard_notification' => $setting->dashboard_notification,
                        'sound' => $setting->sound,
                        'english_usa' => $setting->english_usa,
                        'english_uk' => $setting->english_uk,
                        'chat_settings' => $setting->chat_settings
                    ]
                ];
                return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $data);
            }
            return Helper::returnRecord(GlobalApiResponseCodeBook::INVALID_CREDENTIALS['outcomeCode'], []);
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: login", $error);
            return false;
        }
    }

    public function forgotPassword($request)
    {
        try {
            DB::beginTransaction();
            $password_reset_token = Str::random(140);
            $password_reset = new PasswordReset();
            $password_reset->email = $request->email;
            $password_reset->token = $password_reset_token;
            $password_reset->save();
            $mail_data = [
                "token" => $password_reset_token,
                "email" => $request->email
            ];
            SendPasswordResetMail::dispatch($mail_data);
            $response = [
                "message" => "Email for resetting password has been sent!",
                "token" => $password_reset_token,
                "email" => $request->email
            ];
            DB::commit();
            return $response;
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: forgotPassword", $error);
            return false;
        }
    }

    public function resetPassword($request, $token, $email)
    {
        try {
            DB::beginTransaction();
            $record = PasswordReset::where('token', $token)
                ->where('email', $email)->latest()->first();
            if ($record) {
                $user = User::where('email', $email)->first();
                $user->password = Hash::make($request->password);
                $user->save();

                PasswordReset::where('token', $token)
                    ->where('email', $email)->delete();

                $response = [
                    'message' => 'Password has been resetted!',
                ];
                DB::commit();
                return $response;
            }
            return intval(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode']);
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: resetPassword", $error);
            return false;
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return true;
        } catch (Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: logout", $error);
            return false;
        }
    }

    public function verifyEmail($token, $email)
    {
        try {
            DB::beginTransaction();
            $record = EmailVerify::where('token', $token)
                ->where('email', $email)->latest()->first();
            if ($record) {
                $user = User::where('email', $email)->first();
                $user->user_verified_at = now();
                $user->save();

                EmailVerify::where('token', $token)
                    ->where('email', $email)->delete();

                $response = [
                    'message' => 'Email has been verified!',
                ];
                DB::commit();
                return $response;
            }
            return intval(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode']);
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: verifyEmail", $error);
            return false;
        }
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
