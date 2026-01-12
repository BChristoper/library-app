<?php

return [
    'required' => 'Kolom :attribute wajib diisi.',
    'string' => 'Kolom :attribute harus berupa teks.',
    'email' => 'Kolom :attribute harus berupa email yang valid.',
    'integer' => 'Kolom :attribute harus berupa angka.',
    'numeric' => 'Kolom :attribute harus berupa angka.',
    'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
    'min' => [
        'numeric' => 'Kolom :attribute minimal :min.',
        'string' => 'Kolom :attribute minimal :min karakter.',
    ],
    'max' => [
        'numeric' => 'Kolom :attribute maksimal :max.',
        'string' => 'Kolom :attribute maksimal :max karakter.',
    ],
    'unique' => 'Kolom :attribute sudah digunakan.',
    'exists' => 'Kolom :attribute tidak ditemukan.',
    'digits' => 'Kolom :attribute harus terdiri dari :digits digit.',

    'attributes' => [
        'name' => 'nama',
        'email' => 'email',
        'password' => 'password',
        'role' => 'peran',
        'member_code' => 'kode anggota',
        'phone' => 'telepon',
        'address' => 'alamat',
        'title' => 'judul',
        'author' => 'penulis',
        'isbn' => 'ISBN',
        'publisher' => 'penerbit',
        'year' => 'tahun',
        'stock' => 'stok',
        'user_id' => 'anggota',
        'book_id' => 'buku',
        'loan_date' => 'tanggal pinjam',
        'due_date' => 'tanggal jatuh tempo',
        'return_date' => 'tanggal kembali',
    ],
];
