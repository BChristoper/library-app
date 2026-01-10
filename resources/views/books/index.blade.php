@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-xl font-semibold">Daftar Buku</h1>
        @if (auth()->user()->role === 'petugas')
            <a class="rounded bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800" href="{{ route('books.create') }}">
                Tambah Buku
            </a>
        @endif
    </div>

    <form class="mt-4 flex flex-wrap gap-2" method="GET" action="{{ route('books.index') }}">
        <input class="w-full rounded border border-slate-300 px-3 py-2 sm:max-w-xs" name="q" type="text" placeholder="Cari judul/penulis/ISBN" value="{{ $query }}">
        <button class="rounded border border-slate-300 px-4 py-2 text-sm" type="submit">Cari</button>
        <a class="rounded border border-slate-300 px-4 py-2 text-sm" href="{{ route('books.index') }}">Reset</a>
    </form>

    <div class="mt-6 overflow-x-auto rounded-lg border border-slate-200 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Penulis</th>
                    <th class="px-4 py-3">ISBN</th>
                    <th class="px-4 py-3">Stok</th>
                    @if (auth()->user()->role === 'petugas')
                        <th class="px-4 py-3">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($books as $book)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $book->title }}</td>
                        <td class="px-4 py-3">{{ $book->author }}</td>
                        <td class="px-4 py-3">{{ $book->isbn ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $book->stock }}</td>
                        @if (auth()->user()->role === 'petugas')
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <a class="rounded border border-slate-300 px-3 py-1 text-xs" href="{{ route('books.edit', $book) }}">Edit</a>
                                    <form method="POST" action="{{ route('books.destroy', $book) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded border border-rose-300 px-3 py-1 text-xs text-rose-700" type="submit">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-slate-500" colspan="{{ auth()->user()->role === 'petugas' ? 5 : 4 }}">Belum ada data buku.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
