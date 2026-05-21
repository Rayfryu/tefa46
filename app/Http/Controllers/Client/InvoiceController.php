<?php
// app/Http/Controllers/Client/InvoiceController.php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::whereHas('order', function ($q) {
                        $q->where('client_id', auth()->id());
                    })
                    ->with(['project', 'order', 'latestPayment'])
                    ->latest()
                    ->paginate(10);

        return view('client.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        // Pastikan invoice milik client ini
        abort_if($invoice->order->client_id !== auth()->id(), 403);

        $invoice->load(['order', 'project', 'issuedBy', 'payments']);

        return view('client.invoices.show', compact('invoice'));
    }

    public function pay(Request $request, Invoice $invoice)
    {
        abort_if($invoice->order->client_id !== auth()->id(), 403);
        abort_if($invoice->status === 'paid', 403, 'Invoice ini sudah lunas.');
        abort_if($invoice->status === 'pending_confirmation', 403, 'Pembayaran sedang dikonfirmasi.');

        $request->validate([
            'payment_method' => 'required|string|max:100',
            'payment_date'   => 'required|date|before_or_equal:today',
            'proof_file'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $path = $request->file('proof_file')->store('payment-proofs', 'public');

        Payment::create([
            'invoice_id'     => $invoice->id,
            'client_id'      => auth()->id(),
            'amount_paid'    => $invoice->total,
            'payment_method' => $request->payment_method,
            'proof_file'     => $path,
            'payment_date'   => $request->payment_date,
            'status'         => 'pending',
        ]);

        $invoice->update(['status' => 'pending_confirmation']);

        return back()->with('success', 'Bukti pembayaran berhasil dikirim! Menunggu konfirmasi admin.');
    }
}