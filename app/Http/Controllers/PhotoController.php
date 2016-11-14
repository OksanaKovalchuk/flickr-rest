<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
class PhotoController  extends Controller
{
    /**
     * function to get info for main route
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $apiKey = 'cd51c35deb0b194c8c3ccbf6e18954c5';

            $method = 'flickr.photos.getRecent';
            $url = "https://api.flickr.com/services/rest/?method=".$method."&format=json&nojsoncallback=1&api_key=".$apiKey;

            $new = curl_init();
            curl_setopt($new, CURLOPT_URL, $url);
            curl_setopt($new, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt($new,CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
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
    public function photo($id){
        try {
            $apiKey = 'cd51c35deb0b194c8c3ccbf6e18954c5';
            $method = 'flickr.photos.getSizes';
            $url = "https://api.flickr.com/services/rest/?method=".
                $method."&photo_id=".$id."&format=json&nojsoncallback=1&api_key=".$apiKey;

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
    public function  getBySize($id,$size, $index){

        try {
            $apiKey = 'cd51c35deb0b194c8c3ccbf6e18954c5';
            $method = 'flickr.photos.getSizes';
            $url = "https://api.flickr.com/services/rest/?method=".
                $method."&photo_id=".$id."&format=json&nojsoncallback=1&api_key=".$apiKey;
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
