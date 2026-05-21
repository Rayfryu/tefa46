<?php
// app/Http/Controllers/Client/DashboardController.php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $client = auth()->user();

        $stats = [
            'total_orders'    => Order::where('client_id', $client->id)->count(),
            'pending_orders'  => Order::where('client_id', $client->id)->where('status', 'pending')->count(),
            'active_orders'   => Order::where('client_id', $client->id)->where('status', 'in_progress')->count(),
            'done_orders'     => Order::where('client_id', $client->id)->where('status', 'done')->count(),
            'unpaid_invoices' => Invoice::whereHas('order', function ($q) use ($client) {
                                    $q->where('client_id', $client->id);
                                 })->where('status', 'unpaid')->count(),
        ];

        $my_orders = Order::where('client_id', $client->id)
            ->with('service')
            ->latest()
            ->take(5)
            ->get();

        $my_invoices = Invoice::whereHas('order', function ($q) use ($client) {
                            $q->where('client_id', $client->id);
                        })
                        ->where('status', '!=', 'paid')
                        ->with('order')
                        ->latest()
                        ->take(3)
                        ->get();

        return view('client.dashboard', compact('stats', 'my_orders', 'my_invoices'));
    }
}