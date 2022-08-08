<?php

namespace App\Http\Controllers;

use App\Http\Requests\Carousel\DeleteRequest;
use App\Http\Requests\Carousel\UpdateRequest;
use App\Http\Requests\Carousel\UploadRequest;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Services\CarouselService;
class CarouselController extends Controller
{
    public function __construct(CarouselService $carousel_service, GlobalApiResponse $GlobalApiResponse)
    {
        $this->carousel_service = $carousel_service;
        $this->global_api_response = $GlobalApiResponse;
    }

    public function upload(UploadRequest $request)
    {
        $upload = $this->carousel_service->upload($request);
        if (!$upload)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Carousel image didn't uploaded!", $upload));
        return ($this->global_api_response->success(1, "Carousel image uploaded successfully!", $upload['record']));
    }

    public function update(UpdateRequest $request)
    {
        $updated = $this->carousel_service->update($request);
        if (!$updated)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Carousel image didn't updated!", $updated));
        return ($this->global_api_response->success(1, "Carousel image updated successfully!", $updated));
    }

    public function getAll()
    {
        $all_images = $this->carousel_service->getAll();
        if ($all_images === GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'])
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS, "Carousel images not found!", []));
        if (!$all_images)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Carousel image didn't fetched!", $all_images));
        return ($this->global_api_response->success(1, "Carousel image fetched successfully!", $all_images));
    }

    public function delete(DeleteRequest $request)
    {
        $delete = $this->carousel_service->delete($request);
        if (!$delete)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Carousel image did not deleted!", $delete));
        return ($this->global_api_response->success(1, "Carousel image deleted successfully!", $delete));
    }
}
