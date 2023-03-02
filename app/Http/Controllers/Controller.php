<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function rooda()
    {
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

        return view('rooda', compact('movements', 'distance'));
    }
}
