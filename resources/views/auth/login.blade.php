@extends('layouts.app')

@section('content')
    <div class="min-h-[70vh] flex items-center justify-center">
        <div class="w-full max-w-md rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h1 class="text-xl font-semibold">Login</h1>
            <form class="mt-6 space-y-4" method="POST" action="{{ route('login.submit') }}">
            @csrf

            <div>
                <label class="block text-sm font-medium">Email</label>
                <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="email" type="email" value="{{ old('email') }}" required>
                @error('email')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <input class="mt-1 w-full rounded border border-slate-300 px-3 py-2" name="password" type="password" required>
                @error('password')<p class="text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-2 text-sm text-slate-600">
                <input id="remember" name="remember" type="checkbox">
                <label for="remember">Ingat saya</label>
            </div>

                <button class="w-full rounded bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800" type="submit">
                    Masuk
                </button>
            </form>
        </div>
    </div>
@endsection
