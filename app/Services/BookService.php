<?php

namespace App\Services;

use App\Models\Book;

class BookService
{
    public function createBook(array $data): Book
    {
        return Book::create($data);
    }

    public function updateBook(Book $book, array $data): Book
    {
        $book->update($data);

        return $book;
    }

    public function deleteBook(Book $book): void
    {
        $book->delete();
    }
}
