<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $projects = Project::where('user_id', Auth::user()->id)->get();
        $movementFiles = storage_path('movements/johanna.txt');
        $lines = file($movementFiles, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $movements = collect([]);
        foreach ($lines as $line) {
            $lineValues = explode(',' ,$line);
            $movement = new Movement();
            $movement->timestamp = $lineValues[0];
            $movement->device_id = $lineValues[3];
            $movement->x = $lineValues[4];
            $movement->y = $lineValues[5];
            $movement->z = $lineValues[6];
            $movements->add($movement);
        }
        $x1 = $movements->first()->x;
        $y1 = $movements->first()->y;

        $x2 = 0;
        $y2 = 0;
        $distance = sqrt(pow($x2 - $x1, 2) + pow($y2 - $y1, 2));

        return view('projects.index', compact('projects','movements', 'distance'));
    }
}
