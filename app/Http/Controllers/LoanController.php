<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->string('q')->trim();
        $status = $request->string('status')->lower();

        $loansQuery = Loan::with(['member', 'book'])
            ->when($query->isNotEmpty(), function ($builder) use ($query) {
                $builder->where(function ($searchQuery) use ($query) {
                    $searchQuery->whereHas('member', function ($memberQuery) use ($query) {
                        $memberQuery->where('name', 'like', '%' . $query . '%')
                            ->orWhere('member_code', 'like', '%' . $query . '%');
                    })->orWhereHas('book', function ($bookQuery) use ($query) {
                        $bookQuery->where('title', 'like', '%' . $query . '%')
                            ->orWhere('author', 'like', '%' . $query . '%');
                    });
                });
            })
            ->when($status->isNotEmpty(), function ($builder) use ($status) {
                if ($status->value() === 'active') {
                    $builder->whereNull('return_date');
                } elseif ($status->value() === 'returned') {
                    $builder->whereNotNull('return_date');
                } elseif ($status->value() === 'overdue') {
                    $builder->whereNull('return_date')
                        ->whereDate('due_date', '<', now()->toDateString());
                }
            });

        $loans = $loansQuery->orderByDesc('loan_date')->get();

        $activeCount = Loan::whereNull('return_date')->count();
        $overdueCount = Loan::whereNull('return_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->count();
        $overdueLoans = Loan::with(['member', 'book'])
            ->whereNull('return_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        return view('loans.index', compact('loans', 'query', 'status', 'activeCount', 'overdueCount', 'overdueLoans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::orderBy('name')->get();
        $books = Book::orderBy('title')->get();

        return view('loans.loan', compact('members', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'book_id' => ['required', 'exists:books,id'],
            'loan_date' => ['nullable', 'date'],
        ]);

        $book = Book::findOrFail($validated['book_id']);

        if ($book->stock < 1) {
            return back()
                ->withInput()
                ->withErrors(['book_id' => 'Stok buku habis.']);
        }

        $loanDate = Carbon::parse($validated['loan_date'] ?? now());
        $dueDate = $loanDate->copy()->addDays(7);

        Loan::create([
            'member_id' => $validated['member_id'],
            'book_id' => $validated['book_id'],
            'loan_date' => $loanDate->toDateString(),
            'due_date' => $dueDate->toDateString(),
        ]);

        $book->decrement('stock');

        return redirect()->route('loans.index')
            ->with('success', 'Peminjaman berhasil dicatat.');
    }

    public function update(Loan $loan)
    {
        if ($loan->return_date) {
            return redirect()->route('loans.index')
                ->withErrors(['loan' => 'Peminjaman ini sudah dikembalikan.']);
        }

        $validated = request()->validate([
            'return_date' => ['nullable', 'date'],
        ]);

        $returnDate = isset($validated['return_date'])
            ? Carbon::parse($validated['return_date'])
            : now();

        if ($returnDate->lt(Carbon::parse($loan->loan_date))) {
            return redirect()->route('loans.index')
                ->withErrors(['return_date' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.']);
        }

        $loan->update([
            'return_date' => $returnDate->toDateString(),
        ]);

        $loan->book->increment('stock');

        return redirect()->route('loans.index')
            ->with('success', 'Pengembalian berhasil dicatat.');
    }

    public function report()
    {
        $activeLoans = Loan::with(['member', 'book'])
            ->whereNull('return_date')
            ->orderBy('due_date')
            ->get();

        $overdueLoans = Loan::with(['member', 'book'])
            ->whereNull('return_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->orderBy('due_date')
            ->get();

        return view('loans.report', compact('activeLoans', 'overdueLoans'));
    }
}
