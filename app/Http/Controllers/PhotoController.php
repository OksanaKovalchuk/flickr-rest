<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Entities\FlickrEntity;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    /**
     * function to get info for main route
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $flickr = new FlickrEntity();
        try {
            $result = $flickr->getRecent();

        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'photos' => $result
        ]);
    }

    /**
     * function for route with picture and it's available sizes
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function photo($id)
    {

        $valid = Validator::make(
            [
                'id' => $id
            ], [
            'id' => 'required|min:6'
        ]);

        if ($valid->fails()) {
            return response()->json([
                'status' => 400,
                'text' => 'Please, check data you\'ve entered'
            ]);
        };
        $flickr = new FlickrEntity();
        try {
            $result = $flickr->getSizes($id);

        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => 200,
            'sizes' => $result
        ]);
    }

    /**
     * function for getting image due to choosed  size
     * @param $id
     * @param $size
     * @param $index
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBySize($id, $size, $index)
    {
        $valid = Validator::make([
            'id' => $id,
            'index' => $index
        ], [
            'id' => 'required|min:6',
            'index' => 'required|integer'
        ]);

        if ($valid->fails()) {
            return response()->json([
                'status' => 400,
                'text' => 'Please, check the data you\'ve entered'
            ]);
        };

        $flickr = new FlickrEntity();

        try {
            $result = $flickr->getSizes($id);
            $mid = json_decode($result, true);
            $link = $mid["sizes"]["size"][$index]["source"];
        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'url' => $link
        ]);
    }
}
