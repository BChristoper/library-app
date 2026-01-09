@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold">Catat Peminjaman</h1>

    <form class="mt-6 space-y-4 rounded-lg border border-slate-200 bg-white p-6" method="POST" action="{{ route('loans.store') }}">
        @csrf

        <div>
            <label class="block text-sm font-medium">Anggota</label>
            <select class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="member_id" required>
                <option value="">Pilih anggota</option>
                @foreach ($members as $member)
                    <option value="{{ $member->id }}" @selected(old('member_id') == $member->id)>
                        {{ $member->member_code }} - {{ $member->name }}
                    </option>
                @endforeach
            </select>
            @error('member_id')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
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

        <div>
            <label class="block text-sm font-medium">Tanggal Pinjam</label>
            <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="loan_date" type="date" value="{{ old('loan_date') }}">
            @error('loan_date')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <p class="text-sm text-slate-600">
            Tanggal jatuh tempo otomatis +7 hari dari tanggal pinjam.
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
