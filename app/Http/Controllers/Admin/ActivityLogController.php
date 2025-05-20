<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = Activity::with(['causer', 'subject'])
            ->latest()
            ->paginate(50);

        return view('admin.logs.index', [
            'logs' => $logs
        ]);
    }
}
