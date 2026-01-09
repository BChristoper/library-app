<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Loan::count() > 0) {
            return;
        }

        $books = Book::orderBy('id')->take(5)->get();
        $members = Member::orderBy('id')->take(5)->get();

        if ($books->count() < 3 || $members->count() < 3) {
            return;
        }

        $loans = [
            [
                'member' => $members[0],
                'book' => $books[0],
                'loan_date' => Carbon::now()->subDays(10),
                'return_date' => null,
            ],
            [
                'member' => $members[1],
                'book' => $books[1],
                'loan_date' => Carbon::now()->subDays(2),
                'return_date' => null,
            ],
            [
                'member' => $members[2],
                'book' => $books[2],
                'loan_date' => Carbon::now()->subDays(12),
                'return_date' => Carbon::now()->subDays(4),
            ],
        ];

        foreach ($loans as $loanData) {
            $loanDate = $loanData['loan_date'];
            $dueDate = $loanDate->copy()->addDays(7);

            $loan = Loan::create([
                'member_id' => $loanData['member']->id,
                'book_id' => $loanData['book']->id,
                'loan_date' => $loanDate->toDateString(),
                'due_date' => $dueDate->toDateString(),
                'return_date' => $loanData['return_date']
                    ? $loanData['return_date']->toDateString()
                    : null,
            ]);

            if (is_null($loan->return_date)) {
                $loanData['book']->decrement('stock');
            }
        }
    }
}
