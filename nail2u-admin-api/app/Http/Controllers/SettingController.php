<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\UpdateEmailSettingsRequest;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct(SettingService $AuthService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->services_service = $AuthService;
        $this->global_api_response = $GlobalApiResponse;
    }

    public function generalSetting(UpdateEmailSettingsRequest $request)
    {
        $update_settings = $this->services_service->generalEmail($request);

        if (!$update_settings)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Setting did not updated!", $update_settings));

        return ($this->global_api_response->success(1, "Setting updated successfully!", $update_settings['record']));
    }

    public function notificationsSetting(Request $request)
    {
        $notification_settings = $this->services_service->notificationsSetting($request);

        if (!$notification_settings)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Setting did not updated!", $notification_settings));

        return ($this->global_api_response->success(1, "Setting updated successfully!", $notification_settings['record']));
    }

    public function profileSetting(Request $request)
    {
        $notification_settings = $this->services_service->profileSetting($request);

        if (!$notification_settings)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Setting did not updated!", $notification_settings));

        return ($this->global_api_response->success(1, "Profile updated successfully!", $notification_settings['record']));
    }
}
