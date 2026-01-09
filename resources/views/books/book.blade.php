@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold">Tambah Buku</h1>

    <form class="mt-6 space-y-4 rounded-lg border border-slate-200 bg-white p-6" method="POST" action="{{ route('books.store') }}">
        @csrf

        <div>
            <label class="block text-sm font-medium">Judul</label>
            <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="title" type="text" value="{{ old('title') }}" required>
            @error('title')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Penulis</label>
            <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="author" type="text" value="{{ old('author') }}" required>
            @error('author')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium">ISBN</label>
                <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="isbn" type="text" value="{{ old('isbn') }}">
                @error('isbn')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Penerbit</label>
                <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="publisher" type="text" value="{{ old('publisher') }}">
                @error('publisher')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium">Tahun</label>
                <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="year" type="number" value="{{ old('year') }}">
                @error('year')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Stok</label>
                <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="stock" type="number" min="0" value="{{ old('stock', 0) }}" required>
                @error('stock')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex gap-3">
            <button class="rounded bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800" type="submit">
                Simpan
            </button>
            <a class="rounded border border-slate-300 px-4 py-2 text-sm" href="{{ route('books.index') }}">
                Batal
            </a>
        </div>
    </form>
@endsection
