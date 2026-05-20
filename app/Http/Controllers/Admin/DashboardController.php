<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'leads_count' => 12,
                'subscribers_count' => 148,
                'posts_count' => 24,
                'average_read_time' => '4.2m',
            ],
        ]);
    }
}
