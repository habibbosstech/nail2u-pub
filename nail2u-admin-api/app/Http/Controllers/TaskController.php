<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\AddTaskRequest;
use App\Http\Requests\Task\GetTaskDetailsRequest;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(TaskService $TaskService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->deal_service = $TaskService;
        $this->global_api_response = $GlobalApiResponse;
    }

    public function add(AddTaskRequest $request)
    {
        $teak_details = $this->deal_service->add($request);
        if (!$teak_details)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Task did not added!", $teak_details));
        return ($this->global_api_response->success(1, "Task added successfully!", $teak_details['record']));
    }

    public function getDetails(GetTaskDetailsRequest $request)
    {
        $teak_details = $this->deal_service->getDetails($request);

        if (!$teak_details)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "No task available!", $teak_details));
        return ($this->global_api_response->success(1, "Task details!", $teak_details['record']));
    }
}
