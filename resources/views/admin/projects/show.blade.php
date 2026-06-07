@extends('layouts.app')
@section('title', 'Detail Project')

@section('header')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.projects.index') }}"
            class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-surface-800 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold font-display text-white">{{ $project->title }}</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $project->status->badgeClass() }}">
                    {{ $project->status->label() }}
                </span>
                @if ($project->division)
                    <span class="text-xs text-slate-500">{{ $project->division->name }}</span>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">

        {{-- Progress Bar --}}
        <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm font-semibold text-white">Progress Keseluruhan</p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ $project->tasks->where('status', 'done')->count() }} /
                        {{ $project->tasks->count() }} task selesai
                    </p>
                </div>
                <span
                    class="text-3xl font-bold font-display
                             {{ $project->progress >= 100 ? 'text-emerald-400' : 'text-brand-400' }}">
                    {{ $project->progress }}%
                </span>
            </div>
            <div class="w-full bg-surface-700 rounded-full h-3">
                <div class="h-3 rounded-full transition-all duration-700
                            {{ $project->progress >= 100 ? 'bg-emerald-500' : 'bg-brand-500' }}"
                    style="width: {{ $project->progress }}%"></div>
            </div>
            <div class="flex flex-wrap gap-4 mt-4 text-xs text-slate-500">
                @if ($project->start_date)
                    <span>Mulai: {{ $project->start_date->format('d M Y') }}</span>
                @endif
                @if ($project->end_date)
                    <span>Deadline: {{ $project->end_date->format('d M Y') }}</span>
                @endif
                <span>PIC: {{ $project->picGuru->name ?? '—' }}</span>
                @if ($project->order)
                    <span>Order dari: {{ $project->order->client->name ?? '—' }}</span>
                @endif
            </div>
        </div>

        {{-- Tambahkan di admin/projects/show.blade.php setelah progress bar --}}

        @if ($project->status->value === 'waiting_approval')
            <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-5" x-data="{ showRevision: false }">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-semibold text-amber-400 mb-1">⏳ Menunggu Persetujuan Kamu</p>
                        <p class="text-sm text-amber-300/70">
                            Guru telah mengupload hasil project dan menandainya selesai.
                            Review hasil di bawah, lalu setujui atau minta revisi.
                        </p>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <form method="POST" action="{{ route('admin.projects.finalize', $project) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="action" value="approve">
                            <button type="submit" onclick="return confirm('Setujui dan kirim hasil ke client?')"
                                class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm
                               font-semibold rounded-xl transition-colors">
                                ✓ Setujui & Kirim ke Client
                            </button>
                        </form>
                        <button @click="showRevision = true"
                            class="px-4 py-2 bg-orange-500/10 hover:bg-orange-500/20 text-orange-400
                           border border-orange-500/20 text-sm font-semibold rounded-xl transition-colors">
                            ↩ Minta Revisi
                        </button>
                    </div>
                </div>

                {{-- Modal Revisi --}}
                <div x-show="showRevision" x-transition
                    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                    <div class="bg-surface-800 border border-surface-700 rounded-2xl p-6 w-full max-w-md">
                        <h3 class="font-semibold font-display text-white mb-4">Minta Revisi</h3>
                        <form method="POST" action="{{ route('admin.projects.finalize', $project) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="action" value="revision">
                            <textarea name="note" rows="3" required
                                class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                                 rounded-xl placeholder-slate-500 focus:outline-none focus:border-orange-500 transition-colors resize-none mb-4"
                                placeholder="Jelaskan apa yang perlu direvisi..."></textarea>
                            <div class="flex gap-3">
                                <button type="submit"
                                    class="flex-1 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-sm
                                   font-semibold rounded-xl transition-colors">
                                    Kirim Revisi
                                </button>
                                <button type="button" @click="showRevision = false"
                                    class="flex-1 py-2.5 bg-surface-700 text-slate-300 text-sm rounded-xl transition-colors">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Preview Deliverables --}}
                @if ($project->finalDeliverables->isNotEmpty())
                    <div class="mt-4 pt-4 border-t border-amber-500/20">
                        <p class="text-xs text-amber-400 font-semibold mb-3">Hasil Final yang Diupload Guru:</p>
                        <div class="space-y-2">
                            @foreach ($project->finalDeliverables as $item)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-surface-800">
                                    <div class="flex items-center gap-3">
                                        <span class="text-xl">{{ $item->icon }}</span>
                                        <div>
                                            <p class="text-sm font-medium text-white">{{ $item->title }}</p>
                                            @if ($item->description)
                                                <p class="text-xs text-slate-500">{{ $item->description }}</p>
                                            @endif
                                            @if ($item->file_size_formatted)
                                                <p class="text-xs text-slate-600">{{ $item->file_size_formatted }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ $item->url }}" target="_blank"
                                        class="px-3 py-1.5 bg-brand-500/10 hover:bg-brand-500/20 text-brand-400
                          text-xs rounded-lg transition-colors border border-brand-500/20">
                                        Buka →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Tambahkan di admin/projects/show.blade.php, setelah div progress bar --}}
        @if ($project->order && $project->status->value !== 'cancelled')
            <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5" x-data="{ open: false }">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold font-display text-white">Invoice</h3>
                        @php
                            $activeInvoice = $project->order
                                ->invoices()
                                ->whereIn('status', ['unpaid', 'pending_confirmation', 'paid'])
                                ->first();
                        @endphp
                        @if ($activeInvoice)
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ $activeInvoice->invoice_number }} —
                                <span class="{{ $activeInvoice->statusBadge() }} px-2 py-0.5 rounded-full border text-xs">
                                    {{ $activeInvoice->statusLabel() }}
                                </span>
                            </p>
                        @else
                            <p class="text-xs text-slate-500 mt-0.5">Belum ada invoice untuk project ini</p>
                        @endif
                    </div>
                    @if (!$activeInvoice)
                        <button @click="open = !open"
                            class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white text-sm
                                   font-semibold rounded-xl transition-colors">
                            + Buat Invoice
                        </button>
                    @else
                        <a href="{{ route('admin.invoices.show', $activeInvoice) }}"
                            class="px-4 py-2 bg-surface-700 hover:bg-surface-600 text-slate-300 text-sm rounded-xl transition-colors">
                            Lihat Invoice →
                        </a>
                    @endif
                </div>

                {{-- Form Generate Invoice --}}
                <div x-show="open" x-transition class="mt-4 pt-4 border-t border-surface-700/50">
                    <form method="POST" action="{{ route('admin.projects.invoices.store', $project) }}"
                        class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @csrf
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">Jumlah (Rp) *</label>
                            <input type="number" name="amount" min="1000" required
                                value="{{ $project->order->budget ?? '' }}"
                                class="w-full px-3 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                                      rounded-xl focus:outline-none focus:border-brand-500 transition-colors"
                                placeholder="500000">
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">PPN (%)</label>
                            <input type="number" name="tax" min="0" max="100" value="11"
                                class="w-full px-3 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                                      rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">Jatuh Tempo *</label>
                            <input type="date" name="due_date" required min="{{ now()->addDay()->format('Y-m-d') }}"
                                value="{{ now()->addDays(7)->format('Y-m-d') }}"
                                class="w-full px-3 py-2.5 bg-surface-700 border border-surface-600 text-slate-300 text-sm
                                      rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                        </div>
                        <div class="sm:col-span-3">
                            <button type="submit"
                                class="px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm
                                       font-semibold rounded-xl transition-colors">
                                Generate Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kiri: Tasks --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Add Task Form --}}
                <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between text-sm font-semibold text-white">
                        <span>+ Tambah Task Baru</span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition class="mt-4">
                        <form method="POST" action="{{ route('admin.projects.tasks.store', $project) }}"
                            class="space-y-3">
                            @csrf
                            <input type="text" name="title" placeholder="Judul task..." required
                                class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                                          rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors">
                            <textarea name="description" rows="2" placeholder="Deskripsi (opsional)..."
                                class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                                             rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors resize-none"></textarea>
                            <div class="grid grid-cols-3 gap-3">
                                <select name="assigned_to"
                                    class="px-3 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                                               text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                                    <option value="">Assign ke...</option>
                                    @foreach ($project->members as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endforeach
                                </select>
                                <select name="priority"
                                    class="px-3 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                                               text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="low">Low</option>
                                </select>
                                <input type="date" name="deadline"
                                    class="px-3 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                                              text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                            </div>
                            <button type="submit"
                                class="px-4 py-2 bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold rounded-xl transition-colors">
                                Simpan Task
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Task List --}}
                <div class="bg-surface-800 border border-surface-700/50 rounded-2xl overflow-hidden">
                    <div class="px-5 py-4 border-b border-surface-700/50">
                        <h3 class="font-semibold font-display text-white">Daftar Task</h3>
                    </div>
                    <div class="divide-y divide-surface-700/30">
                        @forelse($project->tasks as $task)
                            @php
                                $priorityColor = match ($task->priority) {
                                    'high' => 'text-red-400 bg-red-500/10 border-red-500/20',
                                    'medium' => 'text-amber-400 bg-amber-500/10 border-amber-500/20',
                                    'low' => 'text-slate-400 bg-slate-500/10 border-slate-500/20',
                                };
                            @endphp
                            <div class="px-5 py-4 hover:bg-surface-700/30 transition-colors">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span
                                                class="text-xs font-medium px-2 py-0.5 rounded-full border {{ $task->status->badgeClass() }}">
                                                {{ $task->status->label() }}
                                            </span>
                                            <span
                                                class="text-xs font-medium px-2 py-0.5 rounded-full border {{ $priorityColor }}">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </div>
                                        <p class="font-medium text-white text-sm">{{ $task->title }}</p>
                                        @if ($task->description)
                                            <p class="text-slate-500 text-xs mt-0.5 line-clamp-1">{{ $task->description }}
                                            </p>
                                        @endif
                                        <div class="flex gap-3 mt-1.5 text-xs text-slate-500">
                                            <span>{{ $task->assignedTo->name ?? 'Belum di-assign' }}</span>
                                            @if ($task->deadline)
                                                <span
                                                    class="{{ $task->deadline->isPast() && $task->status->value !== 'done' ? 'text-red-400' : '' }}">
                                                    ⏰ {{ $task->deadline->format('d M Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Delete task --}}
                                    <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}"
                                        onsubmit="return confirm('Hapus task ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 rounded-lg text-slate-600 hover:text-red-400 hover:bg-red-500/10 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center text-slate-500 text-sm">
                                Belum ada task. Tambahkan task di atas.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Kanan: Anggota --}}
            <div class="space-y-4">

                {{-- Add Member --}}
                <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
                    <h3 class="font-semibold font-display text-white mb-4">Tambah Anggota</h3>
                    <form method="POST" action="{{ route('admin.projects.members.add', $project) }}" class="space-y-3">
                        @csrf
                        <select name="student_id" required
                            class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-slate-300
                                       text-sm rounded-xl focus:outline-none focus:border-brand-500 transition-colors">
                            <option value="">Pilih siswa...</option>
                            @foreach ($availableSiswa as $siswa)
                                <option value="{{ $siswa->id }}">{{ $siswa->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="role_in_project" placeholder="Role (contoh: Frontend Dev)"
                            class="w-full px-4 py-2.5 bg-surface-700 border border-surface-600 text-white text-sm
                                      rounded-xl placeholder-slate-500 focus:outline-none focus:border-brand-500 transition-colors">
                        <button type="submit"
                            class="w-full py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm
                                       font-semibold rounded-xl transition-colors">
                            Tambahkan
                        </button>
                    </form>
                </div>

                {{-- Member List --}}
                <div class="bg-surface-800 border border-surface-700/50 rounded-2xl p-5">
                    <h3 class="font-semibold font-display text-white mb-4">
                        Anggota Tim
                        <span class="text-slate-500 font-normal text-sm">({{ $project->members->count() }})</span>
                    </h3>
                    <div class="space-y-3">
                        @forelse($project->members as $member)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-emerald-500/20 border border-emerald-500/30
                                                flex items-center justify-center text-emerald-400 font-bold text-xs">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-white">{{ $member->name }}</p>
                                        @if ($member->pivot->role_in_project)
                                            <p class="text-xs text-slate-500">{{ $member->pivot->role_in_project }}</p>
                                        @endif
                                    </div>
                                </div>
                                <form method="POST"
                                    action="{{ route('admin.projects.members.remove', [$project, $member]) }}"
                                    onsubmit="return confirm('Keluarkan siswa ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-1.5 rounded-lg text-slate-600 hover:text-red-400 hover:bg-red-500/10 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-slate-500 text-sm text-center py-4">Belum ada anggota</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
