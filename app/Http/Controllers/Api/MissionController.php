<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendMissionDetails;
use App\Mail\TestEmail;
use Illuminate\Http\Request;
use App\Models\Mission;
use App\Models\User;
use App\Notifications\MissionOutcomeUpdated;
use Illuminate\Support\Facades\Mail;
use Auth;
use Illuminate\Support\Facades\Notification;

class MissionController extends Controller
{
    public function index()
    {
        $missions = Mission::with('people')->get();

        return $missions;
    }

    public function show($mission_id)
    {
        $mission = Mission::with('people')->findOrFail($mission_id);

        return $mission;
    }

    public function store(Request $request)
    {
        $mission = Mission::findOrFail($request->input('id'));

        $originalOutcome = $mission->outcome;

        $mission->name = $request->input('name');
        $mission->year = $request->input('year');
        $mission->outcome = $request->input('outcome');

        $mission->save();

        $admins = User::where('role', 'admin')->get();
        if ($originalOutcome != $request->input('outcome')) {
            Notification::send($admins, new MissionOutcomeUpdated($mission));
        }

        return ['message' => 'Succesfully saved'];
    }

    public function sendMissionEmail($mission_id)
    {
        $mission = Mission::findOrFail($mission_id);
        $user = Auth::user();

        MAIL::to($user ? $user->email : 'test@test.com')->send(new SendMissionDetails($mission));
        // return redirect()->back();
        return [
            'success-message' => 'details sent!'
        ];
    }
}
