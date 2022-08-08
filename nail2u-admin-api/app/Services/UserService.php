<?php

namespace App\Services;

use Exception;
use App\Helper\Helper;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Libs\Response\GlobalApiResponseCodeBook;

class UserService extends BaseService
{
    public function listAll($request)
    {
        try {
            $items_per_page = 10;
            if ($request->has('items_per_page'))
                $items_per_page = $request->items_per_page;
            $users = User::select('id', 'username', 'created_at', 'email', 'address', 'cv_url', 'image_url')
                ->whereHas("roles", function ($q) {
                    $q->where("name", "user");
                })
                ->where(function ($query) use ($request) {
                    $query->where('username', 'like', '%' . $request->search . '%');
                    $query->orWhere('email', 'like', '%' . $request->search . '%');
                })
                ->paginate($items_per_page);
            if ($users->isEmpty()) {
                return GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'];
            } else {
                return $users;
            }
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("UserService: listAll", $error);
            return Helper::returnRecord(false, []);
        }
    }

    public function getUserDetail($request)
    {
        try {
            $user = User::with(['reviews.user', 'services'])
                ->where('id', $request->id)
                ->first(['id', 'username', 'email', 'address', 'phone_no', 'total_balance','created_at'])
                ->makeHidden(['absolute_cv_url', 'absolute_image_url', 'avg_rating']);
            return $user;
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("UserService: delete", $error);
            return Helper::returnRecord(false, []);
        }
    }

    public function delete($request)
    {
        try {
            DB::beginTransaction();
            $user = User::where('id', $request->id)
                ->whereHas("roles", function ($q) {
                    $q->where("name", "user");
                })->first();
            // $user->roles()->detach();
            $user->delete();
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("UserService: delete", $error);
            return Helper::returnRecord(false, []);
        }
    }
}
