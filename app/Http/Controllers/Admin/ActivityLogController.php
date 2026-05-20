<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __invoke(): Response
    {
        $logs = Activity::with('causer')
            ->latest()
            ->limit(50)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'log_name' => $activity->log_name,
                    'description' => $activity->description,
                    'causer_name' => $activity->causer ? $activity->causer->name : 'System',
                    'created_at' => $activity->created_at->toDateTimeString(),
                ];
            });

        return Inertia::render('Admin/ActivityLogs', [
            'logs' => $logs,
        ]);
    }
}
