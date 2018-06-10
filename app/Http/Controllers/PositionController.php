<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Position;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    public function store($data)
    {
        $toInsert = array();
        $perClient = array();

        foreach($data as $entry){
            if(
                strpos($entry["callsign"], 'GND') !== false ||
                strpos($entry["callsign"], 'TWR') !== false ||
                strpos($entry["callsign"], 'APP') !== false ||
                strpos($entry["callsign"], 'TIS') !== false ||
                strpos($entry["callsign"], 'OBS') !== false ||
                strpos($entry["callsign"], 'CTR') !== false
            ){ continue; }

            $toInsert[] = array(
                "client_id" => intval($entry["cid"]),
                "client_name" => $entry["realname"],
                "latitude" => floatval($entry["latitude"]),
                "longitude" => floatval($entry["longitude"]),
                "altitude" => intval($entry["altitude"]),
                "speed" => intval($entry["groundspeed"]),
                "heading" => intval($entry["heading"]),
                "created_at" => \Carbon\Carbon::now(),
                "updated_at" => \Carbon\Carbon::now()
            );
        }

        Position::insert($toInsert);
        $this->cleanup();
    }

    public function show($cid)
    {
        $results = Position::where('client_id', '=', $cid)->get();
        $positions = array();

        // Gotta get rid of the overlapping row ids
        foreach($results as $pos){
            $positions[] = $pos;
        }

        return json_encode($positions);
    }

    public function removeByClientId($cid)
    {
        Position::where('client_id', '=', $cid)->delete();
    }

    public function clear()
    {
        Position::truncate();
    }

    private function cleanup(){
        Position::where('created_at', '<=', \Carbon\Carbon::now()->subHours(6))->delete();
    }
}
