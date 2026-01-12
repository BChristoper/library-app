@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-xl font-semibold">Daftar Peminjaman</h1>
        @if (auth()->user()->role === 'petugas')
            <a class="rounded bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800" href="{{ route('loans.create') }}">
                Catat Peminjaman
            </a>
        @endif
    </div>

    <div class="mt-4 grid gap-3 sm:grid-cols-2">
        <div class="rounded-lg border border-slate-200 bg-white p-4">
            <div class="text-sm text-slate-500">Peminjaman Aktif</div>
            <div class="text-2xl font-semibold">{{ $activeCount }}</div>
        </div>
        <div class="rounded-lg border border-rose-200 bg-rose-50 p-4">
            <div class="text-sm text-rose-600">Jatuh Tempo</div>
            <div class="text-2xl font-semibold text-rose-700">{{ $overdueCount }}</div>
        </div>
    </div>

    <form class="mt-4 flex flex-wrap gap-2" method="GET" action="{{ route('loans.index') }}">
        <input class="w-full rounded border border-slate-300 px-3 py-2 sm:max-w-xs" name="q" type="text" placeholder="Cari anggota/buku" value="{{ $query }}">
        @if (auth()->user()->role === 'petugas')
            <select class="rounded border border-slate-300 px-3 py-2 text-sm" name="status">
                <option value="">Semua status</option>
                <option value="active" @selected($status->value() === 'active')>Aktif</option>
                <option value="returned" @selected($status->value() === 'returned')>Selesai</option>
                <option value="overdue" @selected($status->value() === 'overdue')>Jatuh tempo</option>
            </select>
        @endif
        <button class="rounded border border-slate-300 px-4 py-2 text-sm" type="submit">Filter</button>
        <a class="rounded border border-slate-300 px-4 py-2 text-sm" href="{{ route('loans.index') }}">Reset</a>
    </form>

    <div class="mt-6 overflow-x-auto rounded-lg border border-slate-200 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-3">Anggota</th>
                    <th class="px-4 py-3">Buku</th>
                    <th class="px-4 py-3">Tanggal Pinjam</th>
                    <th class="px-4 py-3">Jatuh Tempo</th>
                    <th class="px-4 py-3">Tanggal Kembali</th>
                    <th class="px-4 py-3">Status</th>
                    @if (auth()->user()->role === 'petugas')
                        <th class="px-4 py-3">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($loans as $loan)
                    <tr>
                        <td class="px-4 py-3">
                            {{ $loan->user->name }}
                            @if ($loan->user->member_code)
                                <span class="text-xs text-slate-500">({{ $loan->user->member_code }})</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $loan->book->title }}</td>
                        <td class="px-4 py-3">{{ $loan->loan_date }}</td>
                        <td class="px-4 py-3">{{ $loan->due_date }}</td>
                        <td class="px-4 py-3">{{ $loan->return_date ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $isOverdue = ! $loan->return_date && $loan->due_date < now()->toDateString();
                            @endphp
                            {{ $loan->return_date ? 'Selesai' : ($isOverdue ? 'Terlambat' : 'Aktif') }}
                        </td>
                        @if (auth()->user()->role === 'petugas')
                            <td class="px-4 py-3">
                                @if (! $loan->return_date)
                                    <form class="flex flex-wrap items-center gap-2" method="POST" action="{{ route('loans.update', $loan) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input class="rounded border border-slate-300 px-2 py-1 text-xs" name="return_date" type="date" value="{{ now()->toDateString() }}">
                                        <button class="rounded bg-emerald-600 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-500" type="submit">
                                            Kembalikan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-500">-</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-slate-500" colspan="{{ auth()->user()->role === 'petugas' ? 7 : 6 }}">Belum ada data peminjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8 rounded-lg border border-slate-200 bg-white p-6">
        <h2 class="text-lg font-semibold">Peminjaman Jatuh Tempo Terdekat</h2>
        <ul class="mt-3 space-y-2 text-sm text-slate-700">
            @forelse ($overdueLoans as $loan)
                <li>
                    {{ $loan->user->name }} - {{ $loan->book->title }} (Jatuh tempo: {{ $loan->due_date }})
                </li>
            @empty
                <li class="text-slate-500">Tidak ada peminjaman jatuh tempo.</li>
            @endforelse
        </ul>
    </div>
@endsection
