<?php

namespace App\Services;

use App\Libs\Response\GlobalApiResponseCodeBook;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use App\Helper\Helper;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class AuthService extends BaseService
{
    public function register($request)
    {
        try {
            DB::beginTransaction();
            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone_no = $request->phone_no;
            // $user->address = $request->address;
            // $user->experience = $request->experience;
            $user->image_url = 'storage/artist/images/default-profile-image.png';
            // $store_cv_url = Helper::storeCvUrl($request);
            // if ($store_cv_url)
            //     $user->cv_url = $store_cv_url;
            $user->save();

            $setting = new Setting();
            $setting->user_id = $user->id;
            $setting->private_account = 0;
            $setting->secure_payment = 1;
            $setting->sync_contact_no = 0;
            $setting->app_notification = 1;
            $setting->save();

            $artist_role = Role::findByName('artist');
            $artist_role->users()->attach($user->id);

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist:AuthService: register", $error);
            return false;
        }
    }

    public function login($request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $user = User::whereHas('roles', function ($q) {
                $q->where('name', 'artist');
            })
                ->where('email', '=', $credentials['email'])
                ->with('setting')
                ->first();
            if (
                Hash::check($credentials['password'], isset($user->password) ? $user->password : null)
                &&
                $token = $this->guard()->attempt($credentials)
            ) {

                $roles = Auth::user()->roles->pluck('name');
                $data = Auth::user()->toArray();
                unset($data['roles']);

                $data = [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => $this->guard()->factory()->getTTL() * 60,
                    'user' => Auth::user()->only('id', 'username', 'email', 'phone_no', 'address', 'experience', 'cv_url', 'image_url', 'total_balance', 'absolute_cv_url', 'absolute_image_url'),
                    'roles' => $roles,
                    'settings' => Auth::user()->setting->only('user_id', 'private_account', 'secure_payment', 'sync_contact_no', 'app_notification', 'language')
                ];
                return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $data);
            }
            return Helper::returnRecord(GlobalApiResponseCodeBook::INVALID_CREDENTIALS['outcomeCode'], []);
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist:AuthService: login", $error);
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
            $password_reset->created_at = Carbon::now();
            $password_reset->save();

            $mail_data = [
                "token" => $password_reset_token,
                "email" => $request->email
            ];
            Mail::to($request->email)->send(new ForgotPassword($mail_data));

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
            Helper::errorLogs("Artist:AuthService: forgotPassword", $error);
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
                    ->where('email', $email)->latest()->delete();

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
            Helper::errorLogs("Artist:AuthService: resetPassword", $error);
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
            Helper::errorLogs("Artist:AuthService: logout", $error);
            return false;
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
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
