@extends('layouts.app')

@section('content')
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold">Sistem Perpustakaan</h1>
        <p class="mt-2 text-slate-600">
            @if (auth()->check() && auth()->user()->role === 'anggota')
                Lihat katalog buku dan peminjaman Anda.
            @else
                Kelola katalog buku, data anggota, dan pencatatan peminjaman.
            @endif
        </p>
        @guest
            <p class="mt-4 text-sm text-slate-600">
                Silakan login sebagai petugas atau anggota untuk mengakses fitur.
            </p>
            <a class="mt-3 inline-block rounded border border-slate-300 px-4 py-2 text-sm" href="{{ route('login') }}">
                Login
            </a>
        @endguest
        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <a class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 hover:bg-slate-100" href="{{ route('books.index') }}">
                Lihat Buku
            </a>
            @auth
                @if (auth()->user()->role === 'petugas')
                    <a class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 hover:bg-slate-100" href="{{ route('members.index') }}">
                        Lihat Anggota
                    </a>
                @endif
            @endauth
            <a class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 hover:bg-slate-100" href="{{ route('loans.index') }}">
                Lihat Peminjaman
            </a>
        </div>
    </div>
@endsection
