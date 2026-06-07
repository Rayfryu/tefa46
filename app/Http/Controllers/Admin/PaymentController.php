<?php
// app/Http/Controllers/Admin/PaymentController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['invoice', 'client'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->paginate(10)->withQueryString();

        $stats = [
            'pending'   => Payment::where('status', 'pending')->count(),
            'confirmed' => Payment::where('status', 'confirmed')->count(),
            'rejected'  => Payment::where('status', 'rejected')->count(),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    public function confirm(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini tidak bisa dikonfirmasi.');
        }

        $payment->update([
            'status'       => 'confirmed',
            'confirmed_by' => auth()->id(),
            'confirmed_at' => now(),
        ]);

        // Update invoice jadi paid
        $payment->invoice->update(['status' => 'paid']);

        // Update order status jadi paid — siap dikerjakan
        if ($payment->invoice->order) {
            $payment->invoice->order->update(['status' => 'paid']);
        }

        return redirect()
            ->route('admin.projects.create', ['order_id' => $payment->invoice->order_id])
            ->with('success', 'Pembayaran dikonfirmasi! Silakan buat project untuk order ini. ✅');
    }

    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string|min:5',
        ]);

        if ($payment->status !== 'pending') {
            return back()->with('error', 'Pembayaran ini tidak bisa ditolak.');
        }

        $payment->update(['status' => 'rejected']);

        // Kembalikan invoice ke unpaid
        $payment->invoice->update(['status' => 'unpaid']);

        return back()->with('success', 'Pembayaran ditolak. Client perlu upload ulang.');
    }
}
