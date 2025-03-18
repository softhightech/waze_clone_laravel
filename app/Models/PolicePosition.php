<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PolicePosition extends Model
{
    use HasFactory;
    protected $fillable = ['longitude', 'latitude', 'type_risk'];

    public static function get($request)
    {
        $range = 500;
        $radial = $range / 6378137;
        
        $longitude = $request->longitude;
        $longitude_plus_rad = $longitude+$radial;
        $longitude_minus_rad = $longitude-$radial;

        $latitude = $request->latitude;
        $latitude_plus_rad = $latitude+$radial;
        $latitude_minus_rad = $latitude-$radial;
        
        //$latitude  + 2000 && $latitude - 2000
        $long = PolicePosition::whereBetween('longitude', [$longitude_minus_rad, $longitude_plus_rad])->count();
        //$latitude  + 2000 && $latitude - 2000
        $lat = PolicePosition::whereBetween('latitude', [$latitude_minus_rad, $latitude_plus_rad ])->count();
        return [$long, $lat];
    }
    public static function getNearByPolicemans($request)
    {
        $range = 50000;
        $radial = $range / 6378137;
        
        $longitude = $request->longitude;
        $longitude_plus_rad = $longitude+$radial;
        $longitude_minus_rad = $longitude-$radial;

        $latitude = $request->latitude;
        $latitude_plus_rad = $latitude+$radial;
        $latitude_minus_rad = $latitude-$radial;
        
        //$latitude  + 2000 && $latitude - 2000
        $long = PolicePosition::whereBetween('longitude', [$longitude_minus_rad, $longitude_plus_rad])->get();
        //$latitude  + 2000 && $latitude - 2000
        $lat = PolicePosition::whereBetween('latitude', [$latitude_minus_rad, $latitude_plus_rad ])->get();
        
        return [$long, $lat];
    }
    public static function set($request)
    {
        if(is_null($request->longitude) && is_null($request->latitude))
        {
            return Response::json(array('error'=>true,'result'=>'data not ready'));
        }
        // dd($request->request);
        if($request->action == 'get')
        {
            return self::get($request);
        }elseif($request->action == 'nearbypoliceman')
        {
            return self::getNearByPolicemans($request);
        }
        $range = 500;
        $radial = $range / 6378137;
        
        

        $longitude = $request->longitude;
        $longitude_plus_rad = $longitude+$radial;
        $longitude_minus_rad = $longitude-$radial;

        $latitude = $request->latitude;
        $latitude_plus_rad = $latitude+$radial;
        $latitude_minus_rad = $latitude-$radial;

        //$latitude  + 2000 && $latitude - 2000
        $long = PolicePosition::whereBetween('longitude', [$longitude_minus_rad, $longitude_plus_rad])->count();
        //$latitude  + 2000 && $latitude - 2000
        $lat = PolicePosition::whereBetween('latitude', [$latitude_minus_rad, $latitude_plus_rad ])->count();

        if($long == 0 && $long == 0)
        {
            PolicePosition::firstOrCreate([
                'longitude' => $longitude,
                'latitude' => $latitude,
                'type_risk' => 'police'
            ]);
        }
        return [$long, $lat];
    }


}
