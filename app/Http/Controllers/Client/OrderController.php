<?php
// app/Http/Controllers/Client/OrderController.php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('client_id', auth()->id())
            ->with('service')
            ->latest()
            ->paginate(10);

        return view('client.orders.index', compact('orders'));
    }

    public function create()
    {
        $services = Service::where('is_active', true)->with('division')->get();
        return view('client.orders.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'service_id'         => 'nullable|exists:services,id',
            'description'        => 'required|string|min:20',
            'requirements'       => 'nullable|string',
            'budget'             => 'nullable|numeric|min:0',
            'deadline_requested' => 'nullable|date|after:today',
        ]);

        $validated['client_id'] = auth()->id();
        $validated['status']    = 'pending';

        Order::create($validated);

        return redirect()->route('client.orders.index')
            ->with('success', 'Order berhasil dikirim! Tunggu konfirmasi dari admin.');
    }

    public function show(Order $order)
    {
        // Pastikan client hanya bisa lihat order miliknya
        abort_if($order->client_id !== auth()->id(), 403);

        $order->load(['service.division', 'project.tasks']);

        return view('client.orders.show', compact('order'));
    }
}