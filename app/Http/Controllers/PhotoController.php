<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class PhotoController  extends Controller
{
    public function __construct(){}

    public function index()
    {
        try {


        } catch (\Exception $e) {
            return response()->json([
                'status' => $e->getCode(),
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'photos' => "photos will be here"
        ]);
    }
    public function photo(Request $request)
    {
    }

    public function  getBySize(){

    }
}
