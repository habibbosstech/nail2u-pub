<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Deal;
use Carbon\Carbon;
use Exception;
use App\Helper\Helper;
use Illuminate\Support\Facades\Auth;
use App\Libs\Response\GlobalApiResponseCodeBook;
use Illuminate\Support\Facades\DB;

class DealService extends BaseService
{
    public function all()
    {
        try {
            $deals = Deal::with('Services', 'Artist:id,username,cv_url,image_url')->get()->toArray();

            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORDS_FOUND['outcomeCode'], $deals);
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("BookingService: getJobHistory", $error);
            return Helper::returnRecord(false, []);
        }
    }
}
