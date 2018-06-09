<?php

namespace App\Http\Controllers;

use App\PositionStats;
use Illuminate\Http\Request;

class PositionStatsController extends Controller
{
    public function incrementCycles(){
        $stats = PositionStats::find(1);
        $stats->cycles = $stats->cycles + 1;

        if($stats->cycles >= 500){
            app('App\Http\Controllers\PositionController')->clear();
            $stats->cycles = 0;
        }

        $stats->save();
    }

    public function resetCycles(){
        $stats = PositionStats::find(1);
        $stats->cycles = 0;

        $stats->save();
    }
}
