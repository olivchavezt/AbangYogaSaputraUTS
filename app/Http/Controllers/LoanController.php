<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['user', 'book'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $loans
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'book_id' => 'required|exists:books,book_id',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after:loan_date',
        ]);

        // Check book stock
        $book = Book::find($request->book_id);
        if ($book->stock <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book out of stock'
            ], 400);
        }

        $loan = Loan::create($request->all());
        
        // Decrease book stock
        $book->decrement('stock');

        return response()->json([
            'status' => 'success',
            'message' => 'Loan created successfully',
            'data' => $loan->load(['user', 'book'])
        ], 201);
    }

    public function show($id)
    {
        $loan = Loan::with(['user', 'book'])->find($id);
        
        if (!$loan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Loan not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $loan
        ]);
    }

    public function returnBook(Request $request, $id)
    {
        $loan = Loan::find($id);
        
        if (!$loan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Loan not found'
            ], 404);
        }

        if ($loan->return_date) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book already returned'
            ], 400);
        }

        $loan->update([
            'return_date' => now()->toDateString()
        ]);

        // Increase book stock
        $loan->book->increment('stock');

        return response()->json([
            'status' => 'success',
            'message' => 'Book returned successfully',
            'data' => $loan->load(['user', 'book'])
        ]);
    }

    public function destroy($id)
    {
        $loan = Loan::find($id);
        
        if (!$loan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Loan not found'
            ], 404);
        }

        // If book not returned, increase stock
        if (!$loan->return_date) {
            $loan->book->increment('stock');
        }

        $loan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Loan deleted successfully'
        ]);
    }
}