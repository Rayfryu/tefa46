<?php
// app/Http/Controllers/Admin/InvoiceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['order.client', 'project', 'issuedBy'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('order.client', fn($q) =>
                      $q->where('name', 'like', '%' . $request->search . '%')
                  );
            });
        }

        $invoices = $query->paginate(10)->withQueryString();

        $stats = [
            'total'                => Invoice::count(),
            'unpaid'               => Invoice::where('status', 'unpaid')->count(),
            'pending_confirmation' => Invoice::where('status', 'pending_confirmation')->count(),
            'paid'                 => Invoice::where('status', 'paid')->count(),
        ];

        return view('admin.invoices.index', compact('invoices', 'stats'));
    }

    public function store(Request $request, Project $project)
    {
        // Cek apakah project sudah punya invoice aktif
        $existing = Invoice::where('project_id', $project->id)
                           ->whereIn('status', ['unpaid', 'pending_confirmation'])
                           ->first();

        if ($existing) {
            return back()->with('error', 'Project ini sudah memiliki invoice yang belum lunas.');
        }

        $request->validate([
            'amount'   => 'required|numeric|min:1000',
            'tax'      => 'nullable|numeric|min:0|max:100',
            'due_date' => 'required|date|after:today',
        ]);

        $amount = $request->amount;
        $tax    = $request->tax ?? 0;
        $total  = $amount + ($amount * $tax / 100);

        Invoice::create([
            'order_id'       => $project->order_id,
            'project_id'     => $project->id,
            'invoice_number' => Invoice::generateNumber(),
            'amount'         => $amount,
            'tax'            => $tax,
            'total'          => $total,
            'due_date'       => $request->due_date,
            'status'         => 'unpaid',
            'issued_by'      => auth()->id(),
        ]);

        return back()->with('success', 'Invoice berhasil dibuat dan dikirim ke client.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['order.client', 'project', 'issuedBy', 'payments.client']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Invoice yang sudah lunas tidak bisa dihapus.');
        }
        $invoice->delete();
        return back()->with('success', 'Invoice berhasil dihapus.');
    }
}