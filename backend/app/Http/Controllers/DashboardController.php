<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                ['name' => 'Total Students', 'value' => '1,234', 'change' => '+12%', 'trend' => 'up'],
                ['name' => 'Total Teachers', 'value' => '86', 'change' => '+3%', 'trend' => 'up'],
                ['name' => 'Attendance Rate', 'value' => '94.2%', 'change' => '-1.5%', 'trend' => 'down'],
                ['name' => 'Average Grade', 'value' => 'B+', 'change' => '+2.4%', 'trend' => 'up'],
            ]
        ]);
    }
}
