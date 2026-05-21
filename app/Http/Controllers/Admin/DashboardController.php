<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Project;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'     => User::count(),
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'active_projects' => Project::where('status', 'active')->count(),
        ];

        $recent_orders = Order::with('client')
            ->latest()
            ->take(5)
            ->get();

        $recent_projects = Project::with(['order', 'picGuru'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'recent_projects'));
    }
}