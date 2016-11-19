<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{

    /**
     * function to get info for main route
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $method = "flickr.photos.getRecent";
            $url = "https://api.flickr.com/services/rest/?method=" . $method .
                "&nojsoncallback=1&format=json&api_key=" . env('API_KEY');

            $new = curl_init();
            curl_setopt($new, CURLOPT_URL, $url);
            curl_setopt($new, CURLOPT_RETURNTRANSFER, 1);
            $result = (curl_exec($new));
            curl_setopt($new, CURLOPT_HEADER, true);
            curl_close($new);

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

        try {
            $method = 'flickr.photos.getSizes';
            $url = "https://api.flickr.com/services/rest/?method=" .
                $method . "&photo_id=" . $id . "&format=json&nojsoncallback=1&api_key=" . env('API_KEY');

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = (curl_exec($ch));
            curl_close($ch);

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

        try {
            $method = 'flickr.photos.getSizes';
            $url = "https://api.flickr.com/services/rest/?method=" .
                $method . "&photo_id=" . $id . "&format=json&nojsoncallback=1&api_key=" . env('API_KEY');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = (curl_exec($ch));
            $mid = json_decode($result, true);
            $link = $mid["sizes"]["size"][$index]["source"];
            curl_close($ch);

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
