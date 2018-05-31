<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($data)
    {
        $toInsert = array();
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
                "client_id" => $entry["cid"],
                "client_name" => $entry["realname"],
                "latitude" => floatval($entry["latitude"]),
                "longitude" => floatval($entry["longitude"]),
                "altitude" => intval($entry["altitude"]),
                "speed" => intval($entry["groundspeed"]),
                "heading" => intval($entry["heading"])
            );

        }

        Position::insert($toInsert);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($cid)
    {
        $results = Position::all()->where('client_id', '=', $cid)->toArray();
        $positions = array();

        // Gotta get rid of the overlapping row ids
        foreach($results as $pos){
            $positions[] = $pos;
        }

        return json_encode($positions);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}