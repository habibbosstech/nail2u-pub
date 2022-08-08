<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequests\EditProfileRequest;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Libs\Response\GlobalApiResponse;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(UserService $UserService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->user_service = $UserService;
        $this->global_api_response = $GlobalApiResponse;
    }

    public function getProfileDetails()
    {
        $profile_details = $this->user_service->getProfileDetails();

        if (!$profile_details)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "User profile details did not fetched!", $profile_details));

        return ($this->global_api_response->success(1, "User profile details fetched successfully!", $profile_details));
    }

    public function editProfile(EditProfileRequest $request)
    {
        $edit_profile = $this->user_service->editProfile($request);

        if (!$edit_profile)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "User profile did not edited!", $edit_profile));

        return ($this->global_api_response->success(1, "User profile edited successfully!", $edit_profile));
    }
}
