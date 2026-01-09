<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Perpustakaan</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900">
        <header class="bg-white border-b border-slate-200">
            <div class="max-w-5xl mx-auto px-4 py-4 flex flex-wrap items-center gap-4">
                <a class="text-lg font-semibold tracking-wide" href="{{ route('home') }}">Perpustakaan</a>
                <nav class="flex flex-wrap gap-5 text-sm text-slate-600">
                    <a class="hover:text-slate-900" href="{{ route('books.index') }}">Buku</a>
                    <a class="hover:text-slate-900" href="{{ route('members.index') }}">Anggota</a>
                    <a class="hover:text-slate-900" href="{{ route('loans.index') }}">Peminjaman</a>
                    <a class="hover:text-slate-900" href="{{ route('loans.report') }}">Laporan</a>
                </nav>
            </div>
        </header>

        <main class="max-w-5xl mx-auto px-4 py-8">
            @if (session('success'))
                <div class="mb-6 rounded border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </body>
</html>
