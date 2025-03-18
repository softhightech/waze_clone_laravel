<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolicePosition;
use Illuminate\Support\Facades\Response;

class PolicePositionController extends Controller
{
    
    public static function store(Request $request)
    {
        $long_lat = PolicePosition::set($request);

        if($request->action == 'nearbypoliceman')
        {
            if(count($long_lat[0]) > 0)
            {
                return Response::json(array($long_lat[0]));
            }elseif(count($long_lat[1]) > 0)
            {
                return Response::json(array($long_lat[1]));
            }
            return Response::json(array('success'=>true,'result'=>'no data found'));
        }

        if($long_lat[0] == 0 && $long_lat[1] == 0)
        {
            return Response::json(array('success'=>true,'result'=>'No police'));
        }else{
            return Response::json(array('success'=>true,'result'=>'Kaynin police'));
        }
    }
    public static function index()
    {
        // dd('ok');
        return view('welcome');
    }
    /*
    public function check(Request $request)
    {
        $long_lat = PolicePosition::get($request);
        if($long_lat[0] == 0 && $long_lat[1] == 0)
        {
            return Response::json(array('success'=>true,'result'=>'data saved'));
        }else{
            return Response::json(array('error'=>true,'result'=>'Kaynin police'));
        }
    }
    */
}
