<?php
// app/Http/Controllers/Admin/OrderController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Invoice;

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
                    ->orWhereHas(
                        'client',
                        fn($q) =>
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
        abort_if($order->status->value !== 'pending', 403);

        $order->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Redirect ke halaman buat invoice langsung
        return redirect()
            ->route('admin.orders.invoice', $order)
            ->with('success', 'Order disetujui! Silakan buat invoice untuk client.');
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

    public function createInvoice(Order $order)
{
    abort_if($order->status->value !== 'approved', 403);

    // Cek sudah ada invoice belum
    $existing = Invoice::where('order_id', $order->id)
                       ->whereIn('status', ['unpaid', 'pending_confirmation', 'paid'])
                       ->first();

    if ($existing) {
        return redirect()->route('admin.invoices.show', $existing)
            ->with('error', 'Order ini sudah memiliki invoice aktif.');
    }

    $order->load('client', 'service');

    return view('admin.orders.invoice', compact('order'));
}

public function storeInvoice(Request $request, Order $order)
{
    abort_if($order->status->value !== 'approved', 403);

    $request->validate([
        'amount'   => 'required|numeric|min:1000',
        'tax'      => 'nullable|numeric|min:0|max:100',
        'due_date' => 'required|date|after:today',
        'note'     => 'nullable|string|max:500',
    ]);

    $amount = $request->amount;
    $tax    = $request->tax ?? 0;
    $total  = $amount + ($amount * $tax / 100);

    Invoice::create([
        'order_id'       => $order->id,
        'project_id'     => null, // project belum ada, dibuat setelah bayar
        'invoice_number' => Invoice::generateNumber(),
        'amount'         => $amount,
        'tax'            => $tax,
        'total'          => $total,
        'due_date'       => $request->due_date,
        'status'         => 'unpaid',
        'issued_by'      => auth()->id(),
    ]);

    // Update order status ke waiting_payment
    $order->update(['status' => 'waiting_payment']);

    return redirect()->route('admin.orders.index')
        ->with('success', 'Invoice berhasil dikirim ke client! Menunggu pembayaran.');
}
}
