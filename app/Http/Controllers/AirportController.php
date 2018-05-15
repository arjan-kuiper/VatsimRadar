<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AirportController extends Controller
{
    public function getAirport($airport, $datatype = null){
        $handle = fopen("airports.dat", "r") or die("Couldn't get handle");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $buffer = str_replace('"', '', $buffer);

                $parsedAirport = explode(",", $buffer);
                if($parsedAirport[5] == $airport){
                    if($datatype == null){
                        return json_encode([$parsedAirport[6], $parsedAirport[7]]);
                    }else{
                        switch($datatype){
                            case "IATA":
                                return json_encode($parsedAirport[4]);
                                break;
                            default:
                                return json_encode(404);
                                break;
                        }
                    }
                }
            }
            fclose($handle);
            return json_encode(404);
        }
    }
}
