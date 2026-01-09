@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold">Laporan Peminjaman</h1>

    <div class="mt-6 rounded-lg border border-slate-200 bg-white p-6">
        <h2 class="text-lg font-semibold">Peminjaman Aktif</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-100 text-left text-slate-600">
                    <tr>
                        <th class="px-4 py-3">Anggota</th>
                        <th class="px-4 py-3">Buku</th>
                        <th class="px-4 py-3">Tanggal Pinjam</th>
                        <th class="px-4 py-3">Jatuh Tempo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($activeLoans as $loan)
                        <tr>
                            <td class="px-4 py-3">{{ $loan->member->name }}</td>
                            <td class="px-4 py-3">{{ $loan->book->title }}</td>
                            <td class="px-4 py-3">{{ $loan->loan_date }}</td>
                            <td class="px-4 py-3">{{ $loan->due_date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-slate-500" colspan="4">Tidak ada peminjaman aktif.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-8 rounded-lg border border-rose-200 bg-rose-50 p-6">
        <h2 class="text-lg font-semibold text-rose-700">Peminjaman Jatuh Tempo</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-rose-100 text-left text-rose-700">
                    <tr>
                        <th class="px-4 py-3">Anggota</th>
                        <th class="px-4 py-3">Buku</th>
                        <th class="px-4 py-3">Tanggal Pinjam</th>
                        <th class="px-4 py-3">Jatuh Tempo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-rose-200">
                    @forelse ($overdueLoans as $loan)
                        <tr>
                            <td class="px-4 py-3">{{ $loan->member->name }}</td>
                            <td class="px-4 py-3">{{ $loan->book->title }}</td>
                            <td class="px-4 py-3">{{ $loan->loan_date }}</td>
                            <td class="px-4 py-3">{{ $loan->due_date }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-rose-600" colspan="4">Tidak ada peminjaman jatuh tempo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
