<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->string('q')->trim();
        $status = $request->string('status')->lower();
        $user = $request->user();
        $userId = $user && $user->role === 'anggota'
            ? $user->id
            : null;

        // Filter peminjaman untuk anggota yang login, plus pencarian dan status.
        $loansQuery = Loan::with(['user', 'book'])
            ->when($userId, function ($builder) use ($userId) {
                $builder->where('user_id', $userId);
            })
            ->when($query->isNotEmpty(), function ($builder) use ($query) {
                $builder->where(function ($searchQuery) use ($query) {
                    $searchQuery->whereHas('user', function ($userQuery) use ($query) {
                        $userQuery->where('name', 'like', '%' . $query . '%')
                            ->orWhere('member_code', 'like', '%' . $query . '%')
                            ->orWhere('email', 'like', '%' . $query . '%');
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

        $activeCount = Loan::when($userId, function ($builder) use ($userId) {
                $builder->where('user_id', $userId);
            })
            ->whereNull('return_date')
            ->count();
        $overdueCount = Loan::when($userId, function ($builder) use ($userId) {
                $builder->where('user_id', $userId);
            })
            ->whereNull('return_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->count();
        $overdueLoans = Loan::with(['user', 'book'])
            ->when($userId, function ($builder) use ($userId) {
                $builder->where('user_id', $userId);
            })
            ->whereNull('return_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        return view('loans.index', compact('loans', 'query', 'status', 'activeCount', 'overdueCount', 'overdueLoans'));
    }

    public function create()
    {
        $users = User::where('role', 'anggota')->orderBy('name')->get();
        $books = Book::orderBy('title')->get();

        return view('loans.loan', compact('users', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'book_id' => ['required', 'exists:books,id'],
        ]);

        $borrower = User::where('id', $validated['user_id'])
            ->where('role', 'anggota')
            ->firstOrFail();

        $book = Book::findOrFail($validated['book_id']);

        if ($book->stock < 1) {
            return back()
                ->withInput()
                ->withErrors(['book_id' => 'Stok buku habis.']);
        }

        // Tanggal pinjam hari ini, jatuh tempo +7 hari.
        $loanDate = Carbon::now();
        $dueDate = $loanDate->copy()->addDays(7);

        Loan::create([
            'user_id' => $borrower->id,
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
        $activeLoans = Loan::with(['user', 'book'])
            ->whereNull('return_date')
            ->orderBy('due_date')
            ->get();

        $overdueLoans = Loan::with(['user', 'book'])
            ->whereNull('return_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->orderBy('due_date')
            ->get();

        return view('loans.report', compact('activeLoans', 'overdueLoans'));
    }
}
