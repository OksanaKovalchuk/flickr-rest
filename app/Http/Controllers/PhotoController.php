<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PhotoController  extends Controller
{
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

    public function photo(Request $request){
        try {
            $apiKey = 'cd51c35deb0b194c8c3ccbf6e18954c5';
            $method = 'flickr.photos.getSizes';
            $id = explode('/', $request->decodedPath());
            $url = "https://api.flickr.com/services/rest/?method=".
                $method."&photo_id=".$id[1]."&format=json&nojsoncallback=1&api_key=".$apiKey;

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
    public function  getBySize(Request $request){
        try {
            $apiKey = 'cd51c35deb0b194c8c3ccbf6e18954c5';
            $method = 'flickr.photos.getSizes';
            $id = explode('/', $request->decodedPath());
            $url = "https://api.flickr.com/services/rest/?method=".
                $method."&photo_id=".$id[1]."&format=json&nojsoncallback=1&api_key=".$apiKey;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = (curl_exec($ch));
            $mid = json_decode($result, true);
            $link = $mid["sizes"]["size"][$id[4]]["source"];
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
