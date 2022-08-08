<?php

namespace App\Services;

use App\Helper\Helper;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\Deal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DealService extends BaseService
{
    public function all()
    {
        try {
            $get_deals = Deal::all(['id','name','discount_percentage']);
            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_CREATED['outcomeCode'], $get_deals);
        } catch (\Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("Artist " . __CLASS__ . " : " . __FUNCTION__, $error);
            return false;
        }
    }
}
