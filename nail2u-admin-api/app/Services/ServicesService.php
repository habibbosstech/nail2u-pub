<?php

namespace App\Services;

use App\Helper\Helper;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\Service;
use Exception;
use Auth;

class ServicesService extends BaseService
{

    public function allServices($request)
    {
        try {
            $item_per_page = ($request->item_per_page) ? $request->item_per_page : 12;
            $all = Service::paginate($item_per_page);

            return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $all);
        } catch (Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ServicesService: allServices", $error);
            return false;
        }

    }

    public function editServices($request)
    {
        try {
            $services = Service::find($request->services_id);

            if ($request->name)
                $services->name = $request->name;

            if ($request->price)
                $services->price = $request->price;

            $services->save();

            return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $services);
        } catch (Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ServicesService: allServices", $error);
            return false;
        }
    }

    public function createServices($request)
    {
        try {
            $services = new Service();
            $services->name = $request->name;
            $services->save();

            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_CREATED['outcomeCode'], $services->toArray());
        } catch (Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ServicesService: createServices", $error);
            return false;
        }
    }

    public function deleteServices($request)
    {
        try {
            Service::find($request->services_id)->delete();
            return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], []);
        } catch (Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ServicesService: allServices", $error);
            return false;
        }
    }
}
