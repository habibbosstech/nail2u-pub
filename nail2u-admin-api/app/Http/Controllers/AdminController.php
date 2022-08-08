<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequests\RegisterRequest;
use Illuminate\Http\Request;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Services\AdminService;

class AdminController extends Controller
{
    public function __construct(AdminService $admin_service, GlobalApiResponse $GlobalApiResponse)
    {
        $this->admin_service = $admin_service;
        $this->global_api_response = $GlobalApiResponse;
    }

    public function allAdmins(Request $request)
    {
        $all_admin = $this->admin_service->allAdmins($request);

        if (!$all_admin)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "No admin displayed successfully!", $all_admin));

        return ($this->global_api_response->success(1, "All admin displayed successfully!", $all_admin['record']));
    }

    public function addAdmin(RegisterRequest $request)
    {
        $all_admin = $this->admin_service->addAdmin($request);

        if (!$all_admin)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Admin not added!", $all_admin));

        return ($this->global_api_response->success(1, "Admin added successfully!", $all_admin['record']));
    }

    public function getDetails($id)
    {
        $specific_admin_details = $this->admin_service->getDetails($id);

        if (!$specific_admin_details)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Admin not displayed!", $specific_admin_details));

        if ($specific_admin_details['outcomeCode'] == GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'])
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS, "Admin not found!", $specific_admin_details['record']));

        return ($this->global_api_response->success(1, "Admin details successfully!", $specific_admin_details['record']));
    }

    public function deleteAdmin(Request $request)
    {
        $specific_admin_details = $this->admin_service->deleteAdmin($request);

        if (!$specific_admin_details)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Admin not displayed!", $specific_admin_details));

        return ($this->global_api_response->success(1, "Admin delete successfully!", $specific_admin_details['record']));
    }
}
