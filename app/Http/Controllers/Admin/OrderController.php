<?php
// app/Http/Controllers/Admin/OrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['client', 'service'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('client', fn($q) =>
                        $q->where('name', 'like', '%' . $request->search . '%')
                  );
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        $stats = [
            'all'         => Order::count(),
            'pending'     => Order::where('status', 'pending')->count(),
            'in_progress' => Order::where('status', 'in_progress')->count(),
            'done'        => Order::where('status', 'done')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['client', 'service.division', 'approvedBy', 'project']);
        return view('admin.orders.show', compact('order'));
    }

    public function approve(Order $order)
    {
        abort_if(!in_array($order->status->value, ['pending']), 403, 'Order tidak bisa diapprove.');

        $order->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Order berhasil disetujui.');
    }

    public function reject(Request $request, Order $order)
    {
        abort_if($order->status->value !== 'pending', 403, 'Order tidak bisa ditolak.');

        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $order->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Order berhasil ditolak.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Order berhasil dihapus.');
    }
}