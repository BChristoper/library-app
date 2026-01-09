<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '9780132350884',
                'publisher' => 'Prentice Hall',
                'year' => 2008,
                'stock' => 5,
            ],
            [
                'title' => 'Refactoring',
                'author' => 'Martin Fowler',
                'isbn' => '9780201485677',
                'publisher' => 'Addison-Wesley',
                'year' => 1999,
                'stock' => 4,
            ],
            [
                'title' => 'Design Patterns',
                'author' => 'Erich Gamma',
                'isbn' => '9780201633610',
                'publisher' => 'Addison-Wesley',
                'year' => 1994,
                'stock' => 3,
            ],
            [
                'title' => 'Laravel Up & Running',
                'author' => 'Matt Stauffer',
                'isbn' => '9781492041211',
                'publisher' => "O'Reilly Media",
                'year' => 2019,
                'stock' => 4,
            ],
            [
                'title' => 'Eloquent JavaScript',
                'author' => 'Marijn Haverbeke',
                'isbn' => '9781593279509',
                'publisher' => 'No Starch Press',
                'year' => 2018,
                'stock' => 6,
            ],
        ];

        foreach ($books as $book) {
            Book::updateOrCreate(['isbn' => $book['isbn']], $book);
        }
    }
}
