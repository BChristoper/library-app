@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold">Catat Peminjaman</h1>

    <form class="mt-6 space-y-4 rounded-lg border border-slate-200 bg-white p-6" method="POST" action="{{ route('loans.store') }}">
        @csrf

        <div>
            <label class="block text-sm font-medium">Anggota</label>
            <select class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="user_id" required>
                <option value="">Pilih anggota</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                        {{ $user->member_code ?? '-' }} - {{ $user->name }}
                    </option>
                @endforeach
            </select>
            @error('user_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Buku</label>
            <select class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="book_id" required>
                <option value="">Pilih buku</option>
                @foreach ($books as $book)
                    <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>
                        {{ $book->title }} (Stok: {{ $book->stock }})
                    </option>
                @endforeach
            </select>
            @error('book_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <p class="text-sm text-slate-600">
            Tanggal pinjam otomatis hari ini, jatuh tempo +7 hari.
        </p>

        <div class="flex gap-3">
            <button class="rounded bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800" type="submit">
                Simpan
            </button>
            <a class="rounded border border-slate-300 px-4 py-2 text-sm" href="{{ route('loans.index') }}">
                Batal
            </a>
        </div>
    </form>
@endsection
