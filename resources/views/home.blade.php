@extends('layouts.app')

@section('content')
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold">Sistem Perpustakaan</h1>
        <p class="mt-2 text-slate-600">
            Kelola katalog buku, data anggota, dan pencatatan peminjaman.
        </p>
        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <a class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 hover:bg-slate-100" href="{{ route('books.index') }}">
                Lihat Buku
            </a>
            <a class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 hover:bg-slate-100" href="{{ route('members.index') }}">
                Lihat Anggota
            </a>
            <a class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 hover:bg-slate-100" href="{{ route('loans.index') }}">
                Lihat Peminjaman
            </a>
        </div>
    </div>
@endsection

@section('sidebar')
    <h2 class="text-sm font-semibold text-slate-700">Ringkasan</h2>
    <p class="mt-2 text-sm text-slate-600">
        Sidebar ini hanya muncul di halaman yang mendefinisikan
        <code>@section('sidebar')</code>.
    </p>
@endsection
