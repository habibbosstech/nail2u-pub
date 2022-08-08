<?php

namespace App\Services;

use App\Helper\Helper;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\AdminSetting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingService extends BaseService
{
    public function generalEmail($request)
    {
        try {
            DB::beginTransaction();
            $email_setting_update = User::find(Auth::id());
            $email_setting_update->email = $request->email;
            $email_setting_update->password = Hash::make($request->password);
            $email_setting_update->save();
            DB::commit();
            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_UPDATED['outcomeCode'], $email_setting_update->toArray());
        } catch (\Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("SettingService: add", $error);
            return false;
        }
    }

    public function notificationsSetting($request)
    {
        try {
            DB::beginTransaction();

            $notification = AdminSetting::where('user_id', Auth::id())->first();

            switch ($request->key) {
                case 'dashboard_notification';
                    $notification->dashboard_notification = $request->value;
                    break;

                case 'sound';
                    $notification->sound = $request->value;
                    break;

                case 'english_usa';
                    $notification->english_usa = $request->value;
                    break;

                case 'english_uk';
                    $notification->english_uk = $request->value;
                    break;
            }

            $notification->save();

            DB::commit();
            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_UPDATED['outcomeCode']
                , [
                    'dashboard_notification' => $notification->dashboard_notification,
                    'sound' => $notification->sound,
                    'english_usa' => $notification->english_usa,
                    'english_uk' => $notification->english_uk,
                    'chat_settings' => $notification->chat_settings
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("SettingService: notificationsSetting", $error);
            return false;
        }
    }

    public function profileSetting($request)
    {
        try {
            DB::beginTransaction();

            $profile = User::find(Auth::id());
            $profile->username = $request->username;
            $profile->phone_no = $request->phone_no;
            $profile->address = $request->address;
            $profile->save();

            DB::commit();
            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_UPDATED['outcomeCode'],
                Auth::user()->fresh()->only('id', 'username', 'email', 'phone_no', 'absolute_image_url', 'address'));
        } catch (\Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("SettingService: profileSetting", $error);
            return false;
        }
    }
}
