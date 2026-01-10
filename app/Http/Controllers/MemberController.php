<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Menampilkan daftar data pada halaman.
     */
    public function index(Request $request)
    {
        $query = $request->string('q')->trim();

        $members = User::query()
            ->where('role', 'anggota')
            ->when($query->isNotEmpty(), function ($builder) use ($query) {
                $builder->where('name', 'like', '%' . $query . '%')
                    ->orWhere('member_code', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->orderBy('name')
            ->get();

        return view('members.index', compact('members', 'query'));
    }

    /**
     * Menampilkan form untuk membuat data baru.
     */
    public function create()
    {
        return view('members.member');
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_code' => ['required', 'string', 'max:50', 'unique:users,member_code'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
        ]);

        // Password disimpan dalam bentuk hash.
        User::create([
            ...$validated,
            'role' => 'anggota',
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data tertentu.
     */
    public function edit(User $member)
    {
        if ($member->role !== 'anggota') {
            abort(404);
        }

        return view('members.member-edit', compact('member'));
    }

    /**
     * Memperbarui data tertentu di database.
     */
    public function update(Request $request, User $member)
    {
        if ($member->role !== 'anggota') {
            abort(404);
        }

        $validated = $request->validate([
            'member_code' => ['required', 'string', 'max:50', 'unique:users,member_code,' . $member->id],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,' . $member->id],
            'password' => ['nullable', 'string', 'min:6'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
        ]);

        // Jika password diisi, hash ulang; jika kosong, abaikan.
        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil diperbarui.');
    }

    /**
     * Menghapus data tertentu dari database.
     */
    public function destroy(User $member)
    {
        if ($member->role !== 'anggota') {
            abort(404);
        }

        try {
            $member->delete();
        } catch (\Throwable $exception) {
            return redirect()->route('members.index')
                ->withErrors(['member' => 'Anggota tidak bisa dihapus karena masih dipakai pada peminjaman.']);
        }

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }
}

