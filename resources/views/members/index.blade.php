@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-xl font-semibold">Daftar Anggota</h1>
        <a class="rounded bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800" href="{{ route('members.create') }}">
            Tambah Anggota
        </a>
    </div>

    <form class="mt-4 flex flex-wrap gap-2" method="GET" action="{{ route('members.index') }}">
        <input class="w-full rounded border border-slate-300 px-3 py-2 sm:max-w-xs" name="q" type="text" placeholder="Cari nama/kode/email" value="{{ $query }}">
        <button class="rounded border border-slate-300 px-4 py-2 text-sm" type="submit">Cari</button>
        <a class="rounded border border-slate-300 px-4 py-2 text-sm" href="{{ route('members.index') }}">Reset</a>
    </form>

    <div class="mt-6 overflow-x-auto rounded-lg border border-slate-200 bg-white">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-100 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Telepon</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($members as $member)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $member->member_code }}</td>
                        <td class="px-4 py-3">{{ $member->name }}</td>
                        <td class="px-4 py-3">{{ $member->email ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $member->phone ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <a class="rounded border border-slate-300 px-3 py-1 text-xs" href="{{ route('members.edit', $member) }}">Edit</a>
                                <form method="POST" action="{{ route('members.destroy', $member) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded border border-rose-300 px-3 py-1 text-xs text-rose-700" type="submit">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-4 py-6 text-center text-slate-500" colspan="5">Belum ada data anggota.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
