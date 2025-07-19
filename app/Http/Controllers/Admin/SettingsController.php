<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class SettingsController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(20);
        return view('admin.settings', compact('logs'));
    }

    public function clearLogs()
    {
        ActivityLog::truncate();
        return redirect()->back()->with('success', 'Activity logs cleared successfully.');
    }
}
