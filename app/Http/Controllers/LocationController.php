<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location as Kota;
use App\Toko;
use App\User;
use JWTAuth;

class LocationController extends Controller
{

    public function search($value){

    	$error = 0;
    	
    	$keyword = explode('+', $value);

        $result = Kota::where('kota','LIKE', '%'. $value. '%')->get();
    	
        if (!empty($keyword)) {
    
    		return $result->count() ? response()
    					  ->json(["status" => "OK", "data" => $result], 200) : $error;
    	}

    }

    public function getProfile(){

        $idUser = JWTAuth::parseToken()->authenticate()->id;
        $profil = User::with('profile')->where('id',$idUser)->first();

        return response()->json(['status' => "ok", "data" => $profil],200);
    }

    public function getJasaPrint(){
        $kertas = Toko::where('status','kertas')->get();
        $digital = Toko::where('status','digital')->get();


        return response()->json([
            "Showplace" => $kertas,
            "Park" => $digital,
        ],200);
    }
}