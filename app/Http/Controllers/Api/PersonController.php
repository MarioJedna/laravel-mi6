<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\TestEmail;
use App\Models\Person;
use App\Models\User;
use App\Notifications\TestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use Illuminate\Notifications\Notification as NotificationsNotification;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Illuminate\Support\Testing\Fakes\NotificationFake;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        $page = max(1, intval($request->input('page') ?? 1));

        $search = $request->input('search');
        $status = $request->input('status');

        $on_page = 20;

        // start building the query
        $builder = Person::query()
            ->with([
                'image',
                'status',
                'aliases'
            ]);

        if ($search) {
            $builder->where('name', 'like', "%{$search}%");
        }
        if ($status) {
            $builder->where('status_id', $status);
        }

        // make a separate query to calculate the total
        $total = $builder->count();
        $last_page = max(1, ceil($total / $on_page));

        $people = $builder->limit($on_page)
            ->offset(($page - 1) * $on_page)
            ->get();

        return compact('people', 'total', 'last_page');
    }

    public function sendTestEmail()
    {
        $temp_var = "Hello random guy";
        MAIL::to('test@test.com')->send(new TestEmail($temp_var));
    }

    public function sendTestNotification()
    {

        $user = User::get();
        // $user->notify(new TestNotification('hi'));
        FacadesNotification::send($user, new TestNotification('hi'));
    }
}
