<?php

namespace App\Console\Commands;

use App\Models\Movement;
use Illuminate\Console\Command;

class LoadMovementsCommand extends Command
{
    protected $signature = 'load';
    protected $description = 'Command description';

    public function handle(): void
    {
        $movementFiles = storage_path('movements/johanna.txt');
        $lines = file($movementFiles, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $lineValues = explode(',' ,$line);
            $movement = new Movement();
            $movement->timestamp = $lineValues[0];
            $movement->device_id = $lineValues[3];
            $movement->x = $lineValues[4];
            $movement->y = $lineValues[5];
            $movement->z = $lineValues[6];
            $this->info($movement);
        }
    }
}
