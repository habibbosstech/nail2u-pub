<?php

namespace App\Services;

use App\Helper\Helper;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\AppImage;
use Illuminate\Support\Facades\DB;
use Exception;

class CarouselService extends BaseService
{
    public function upload($request)
    {
        try {
            $response = Helper::storeImage($request, 'storage/carouselImages');
            $carousel_image = new AppImage;
            $carousel_image->carousal_images = $response;
            $carousel_image->save();
            return $carousel_image;
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("CarouselService: upload", $error);
            return false;
        }
    }

    public function update($request)
    {
        try {
            $carousel_image = AppImage::find($request->id);
            $response = Helper::storeImage($request, 'storage/carouselImages');
            $carousel_image->carousal_images = $response;
            $carousel_image->save();
            return $carousel_image;
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("CarouselService: upload", $error);
            return false;
        }
    }

    public function getAll()
    {
        try {
            $images = AppImage::get(['id', 'carousal_images']);
            if ($images->isEmpty()) {
                return GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'];
            }
            return $images;
            
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("CarouselService: getAll", $error);
            return false;
        }
    }

    public function delete($request)
    {
        try {
            DB::beginTransaction();
            $deleted = AppImage::find($request->id)->delete();
            DB::commit();
            return $deleted;
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("CarouselService: delete", $error);
            return false;
        }
    }
}