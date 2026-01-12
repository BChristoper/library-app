<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(private BookService $bookService)
    {
    }

    /**
     * Menampilkan daftar data pada halaman.
     */
    public function index(Request $request)
    {
        $query = $request->string('q')->trim();

        $jatuhtempo = now()->addDays(7)->toDateString();

        $books = Book::query()
            ->when($query->isNotEmpty(), function ($builder) use ($query) {
                $builder->where('title', 'like', '%' . $query . '%')
                    ->orWhere('author', 'like', '%' . $query . '%')
                    ->orWhere('isbn', 'like', '%' . $query . '%');
            })
            ->orderBy('title')
            ->get();
        
        
    
        return view('books.index', compact('books', 'query'));
    }

    /**
     * Menampilkan form untuk membuat data baru.
     */
    public function create()
    {
        return view('books.book');
    }

    /**
     * Menyimpan data baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:32', 'unique:books,isbn'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:9999'],
            'stock' => ['required', 'numeric', 'min:0'],
        ]);

        $this->bookService->createBook($validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data tertentu.
     */
    public function edit(Book $book)
    {
        return view('books.book-edit', compact('book'));
    }

    /**
     * Memperbarui data tertentu di database.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:32', 'unique:books,isbn,' . $book->id],
            'publisher' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:9999'],
            'stock' => ['required', 'numeric', 'min:0'],
        ]);

        $this->bookService->updateBook($book, $validated);

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Menghapus data tertentu dari database.
     */
    public function destroy(Book $book)
    {
        // Hapus buku hanya jika tidak ada peminjaman aktif.
        $hasActiveLoan = $book->loans()
            ->whereNull('return_date')
            ->exists();

        if ($hasActiveLoan) {
            return redirect()->route('books.index')
                ->withErrors(['book' => 'Buku tidak bisa dihapus karena masih dipakai pada peminjaman.']);
        }

        $book->loans()->delete();
        $this->bookService->deleteBook($book);

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}

