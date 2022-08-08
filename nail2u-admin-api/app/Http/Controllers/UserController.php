<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequests\DeleteRequest;
use App\Http\Requests\UserRequests\ListAllRequest;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Libs\Response\GlobalApiResponse;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(UserService $UserService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->user_service = $UserService;
        $this->global_api_response = $GlobalApiResponse;
    }

    public function listAll(ListAllRequest $request)
    {
        $users = $this->user_service->listAll($request);
        if ($users === GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'])
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS, "Users not found!", []));
        if (!$users)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Users did not fetched!", $users));
        return ($this->global_api_response->success(count($users), "Users fetched successfully!", $users));
    }

    public function getUserDetail(Request $request)
    {
        $user_detail = $this->user_service->getUserDetail($request);
        if (!$user_detail)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "User detail did not fetched!", $user_detail));
        return ($this->global_api_response->success(1, "User detail fetched successfully!", $user_detail));
    }

    public function delete(DeleteRequest $request)
    {
        $deleted = $this->user_service->delete($request);
        if (!$deleted)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "User did not deleted!", $deleted));
        return ($this->global_api_response->success(1, "User deleted successfully!", $deleted));
    }
}
