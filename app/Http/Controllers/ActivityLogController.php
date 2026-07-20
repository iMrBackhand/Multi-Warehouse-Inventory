<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = Activity::latest()->paginate(20);

        return view('admin.activitylog.activitylog',compact('logs'));
    }
}
