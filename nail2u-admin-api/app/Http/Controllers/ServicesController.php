<?php

namespace App\Http\Controllers;

use App\Http\Requests\Services\CreateServicesRequest;
use App\Http\Requests\Services\EditServicesRequest;
use Illuminate\Http\Request;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Services\ServicesService;

class ServicesController extends Controller
{
    public function __construct(ServicesService $services_service, GlobalApiResponse $GlobalApiResponse)
    {
        $this->services_service = $services_service;
        $this->global_api_response = $GlobalApiResponse;
    }

    public function allServices(Request $request)
    {
        $all_services = $this->services_service->allServices($request);

        if (!$all_services)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "No services displayed successfully!", $all_services));

        return ($this->global_api_response->success(1, "All services displayed successfully!", $all_services['record']));
    }

    public function createServices(CreateServicesRequest $request)
    {
        $all_services = $this->services_service->createServices($request);

        if (!$all_services)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "No services displayed successfully!", $all_services));

        return ($this->global_api_response->success(1, "Services updated successfully!", $all_services['record']));
    }

    public function editServices(EditServicesRequest $request)
    {
        $all_services = $this->services_service->editServices($request);

        if (!$all_services)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "No services displayed successfully!", $all_services));

        return ($this->global_api_response->success(1, "Services updated successfully!", $all_services['record']));
    }

    public function deleteServices(EditServicesRequest $request)
    {
        $all_services = $this->services_service->deleteServices($request);

        if (!$all_services)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "No services displayed successfully!", $all_services));

        return ($this->global_api_response->success(1, "Services delete successfully!", $all_services['record']));
    }
}
