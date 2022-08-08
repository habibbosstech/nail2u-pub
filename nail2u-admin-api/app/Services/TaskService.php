<?php

namespace App\Services;

use App\Helper\Helper;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\{User,Task};
use Exception;
use Auth;

class TaskService extends BaseService
{

    public function allAdmins($request)
    {
        try {
            $item_per_page = ($request->item_per_page) ? $request->item_per_page : 12;
            $all = User::whereHas("roles", function ($q) {
                $q->where("name", "admin");
            })->paginate($item_per_page);

            return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $all);
        } catch (Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ServicesService: allServices", $error);
            return false;
        }

    }

    public function add($request)
    {
        try {
            $add_new_task = new Task();
            $add_new_task->user_id = Auth::id();
            $add_new_task->name = $request->due_date;
            $add_new_task->save();

            return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $add_new_task->toArray());
        } catch (Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs(__CLASS__ . " : " . __FUNCTION__, $error);
            return false;
        }
    }

    public function getDetails($date)
    {
        try {

            if ($date->date)
                $get_tasks = Task::where([['due_date', '=', $date->date], ['user_id', Auth::id()]])->get();
            else
                $get_tasks = Task::where('user_id', Auth::id())->get();

            return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $get_tasks->toArray());
        } catch (Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs(__CLASS__ . " : " . __FUNCTION__, $error);
            return false;
        }
    }
}
