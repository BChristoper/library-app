@extends('layouts.app')

@section('content')
    <h1 class="text-xl font-semibold">Edit Anggota</h1>

    <form class="mt-6 space-y-4 rounded-lg border border-slate-200 bg-white p-6" method="POST" action="{{ route('members.update', $member) }}">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-sm font-medium">Kode Anggota</label>
            <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="member_code" type="text" value="{{ old('member_code', $member->member_code) }}" required>
            @error('member_code')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Nama</label>
            <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="name" type="text" value="{{ old('name', $member->name) }}" required>
            @error('name')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium">Email</label>
                <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="email" type="email" value="{{ old('email', $member->email) }}">
                @error('email')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Telepon</label>
                <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="phone" type="text" value="{{ old('phone', $member->phone) }}">
                @error('phone')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium">Password (opsional)</label>
            <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="password" type="password">
            @error('password')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
            <p class="mt-1 text-xs text-slate-500">Kosongkan jika tidak ingin mengganti.</p>
        </div>

        <div>
            <label class="block text-sm font-medium">Alamat</label>
            <textarea class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="address" rows="3">{{ old('address', $member->address) }}</textarea>
            @error('address')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3">
            <button class="rounded bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800" type="submit">
                Simpan Perubahan
            </button>
            <a class="rounded border border-slate-300 px-4 py-2 text-sm" href="{{ route('members.index') }}">
                Batal
            </a>
        </div>
    </form>
@endsection
