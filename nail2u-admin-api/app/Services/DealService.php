<?php

namespace App\Services;

use Exception;
use App\Helper\Helper;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\Deal;
use Illuminate\Support\Facades\DB;

class DealService extends BaseService
{
    public function addNew($request)
    {
        try {
            DB::beginTransaction();
            $deal = new Deal();
            $deal->start_date = date($request->start_date);
            $deal->end_date = $request->end_date;
            $deal->image_url = Helper::storeImageUrl($request, null, 'storage/dealImages');
            $deal->discount_percentage = $request->discount;
            $deal->save();
            DB::commit();
            return $deal;
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("DealService: addNew", $error);
            return false;
        }
    }

    public function listOngoing()
    {
        try {
            $deals = Deal::where('is_published', 1)->paginate();
            if ($deals->isEmpty()) {
                return GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'];
            }
            return $deals;
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("DealService: listOngoing", $error);
            return false;
        }
    }

    public function listAll()
    {
        try {
            $deals = Deal::paginate();
            if ($deals->isEmpty()) {
                return GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'];
            }
            return $deals;
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("DealService: listAll", $error);
            return false;
        }
    }

    public function delete($request)
    {
        try {
            DB::beginTransaction();
            $deal = Deal::where('id', $request->id)->first();
            $deal->delete();
            DB::commit();
            return $deal;
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("DealService: delete", $error);
            return false;
        }
    }

    public function edit($request)
    {
        $deal = Deal::find($request->id);
        $deal->start_date = date($request->start_date);
        $deal->end_date = $request->end_date;
        $deal->image_url = Helper::storeImageUrl($request, null, 'storage/dealImages');
        $deal->discount_percentage = $request->discount;
        $deal->save();
    }
}
