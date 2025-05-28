<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('authors')->get();
        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'isbn' => 'required|string|unique:books',
            'publisher' => 'required|string',
            'year_published' => 'required|string',
            'stock' => 'required|integer|min:0',
            'author_ids' => 'required|array',
            'author_ids.*' => 'exists:authors,author_id'
        ]);

        $book = Book::create($request->except('author_ids'));
        $book->authors()->attach($request->author_ids);

        return response()->json([
            'status' => 'success',
            'message' => 'Book created successfully',
            'data' => $book->load('authors')
        ], 201);
    }

    public function show($id)
    {
        $book = Book::with('authors')->find($id);
        
        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $book
        ]);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        $request->validate([
            'title' => 'string',
            'isbn' => 'string|unique:books,isbn,' . $id . ',book_id',
            'publisher' => 'string',
            'year_published' => 'string',
            'stock' => 'integer|min:0',
            'author_ids' => 'array',
            'author_ids.*' => 'exists:authors,author_id'
        ]);

        $book->update($request->except('author_ids'));
        
        if ($request->has('author_ids')) {
            $book->authors()->sync($request->author_ids);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Book updated successfully',
            'data' => $book->load('authors')
        ]);
    }

    public function destroy($id)
    {
        $book = Book::find($id);
        
        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        $book->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Book deleted successfully'
        ]);
    }
}