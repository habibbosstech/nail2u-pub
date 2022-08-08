<?php

namespace App\Services;

use App\Helper\Helper;
use App\Jobs\SendEmailVerificationMail;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\EmailVerify;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminService extends BaseService
{

    public function allAdmins($request)
    {
        try {
            $item_per_page = ($request->item_per_page) ? $request->item_per_page : 12;
            $all = User::whereHas("roles", function ($q) {
                $q->where("name", "admin");
            })->paginate($item_per_page);

            return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $all);
        }
        catch (Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ServicesService: allServices", $error);
            return false;
        }

    }

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
            //SendEmailVerificationMail::dispatch($mail_data);
            DB::commit();
            return $user;
        }
        catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: register", $error);
            return false;
        }
    }

    public function deleteServices($request)
    {
        try {
            Service::find($request->services_id)->delete();
            return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], []);
        }
        catch (Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ServicesService: allServices", $error);
            return false;
        }
    }

    public function getDetails($id)
    {
        try {
            $admin_details = User::whereHas(
                'roles', function ($q) {
                $q->where('name', 'Admin');
            })
                ->with('adminAddedArtist:id,approved_by,username,email,phone_no,image_url,cv_url', 'adminAddedServices', 'adminApprovePayment', 'task')
                ->where('id', $id)->get(['id', 'username', 'email', 'phone_no', 'address', 'image_url'])
                ->makeHidden(['absolute_cv_url', 'avg_rating']);

            if (!$admin_details)
                return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'], []);

            return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $admin_details->toArray());
        }
        catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ServicesService: allServices", $error);
            return false;
        }
    }
}