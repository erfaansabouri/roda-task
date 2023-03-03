<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => ['required'],
            'device_id' => ['required','numeric'],
            'movement_file' => ['required', 'mimes:txt,csv'],
            'plan_image' => ['required', 'mimes:png,jpeg,jpg'],
        ]);

        $movementFileName = Str::uuid() . '.' . $request->movement_file->extension();
        $request->movement_file->move(public_path('user-data'), $movementFileName);

        $planImageFileName = Str::uuid() . '.' . $request->plan_image->extension();
        $request->plan_image->move(public_path('user-data'), $planImageFileName);

        $project = Project::query()
            ->create([
                'user_id' => Auth::user()->id,
                'name' => $request->project_name,
                'device_id' => $request->device_id,
                'movement_file' => $movementFileName,
                'plan_image' => $planImageFileName,
            ]);

        $movementFile = public_path('user-data/'. $movementFileName);
        $lines = file($movementFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $lineValues = explode(',' ,$line);
            if ($lineValues[3] === $project->device_id)
            {
                $movement = new Movement();
                $movement->timestamp = $lineValues[0];
                $movement->project_id = $project->id;
                $movement->x = $lineValues[4];
                $movement->y = $lineValues[5];
                $movement->z = $lineValues[6];
                $movement->save();
            }
        }
        return redirect()->route('project.show', $project->refresh()->uuid);
    }

    public function show($uuid)
    {
        $project = Project::where('uuid', $uuid)->where('user_id', Auth::user()->id)->with(['movements'])->firstOrFail();
        $movements = $project->movements;
        if (collect($project->movements)->isEmpty()) {
            return "There is no movement for device " . $project->device_id . " in this plan!";
        }
        $x1 = $movements->first()->x;
        $y1 = $movements->first()->y;
        $x2 = 0;
        $y2 = 0;
        $distance_in_meter = sqrt(pow($x2 - $x1, 2) + pow($y2 - $y1, 2));
        return view('projects.show', compact('project', 'distance_in_meter', 'movements'));
    }
}
