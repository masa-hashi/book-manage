<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $query = Book::with('category');

        // Search by title
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Search by author
        if ($request->filled('author')) {
            $query->where('author', 'like', '%' . $request->author . '%');
        }

        // Filter by genre
        if ($request->filled('genre')) {
            $query->where('genre', 'like', '%' . $request->genre . '%');
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $books = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        $categories = Category::orderBy('name')->get();

        $genres = Book::select('genre')
            ->whereNotNull('genre')
            ->distinct()
            ->orderBy('genre')
            ->pluck('genre');

        $stats = [
            'total' => Book::count(),
            'read' => Book::where('status', '読了')->count(),
            'reading' => Book::where('status', '読中')->count(),
            'unread' => Book::where('status', '未読')->count(),
        ];

        return view('books.index', compact('books', 'categories', 'genres', 'stats'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $genres = Book::select('genre')->whereNotNull('genre')->distinct()->orderBy('genre')->pluck('genre');
        return view('books.create', compact('categories', 'genres'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'author'          => 'required|string|max:255',
            'publisher'       => 'nullable|string|max:255',
            'published_year'  => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'isbn'            => 'nullable|string|max:20|unique:books,isbn',
            'genre'           => 'nullable|string|max:100',
            'description'     => 'nullable|string',
            'status'          => 'required|in:未読,読中,読了',
            'cover_image_url' => 'nullable|url|max:500',
            'category_id'     => 'nullable|exists:categories,id',
            'rating'          => 'nullable|integer|min:1|max:5',
            'read_at'         => 'nullable|date',
        ], [
            'title.required'  => 'タイトルは必須です。',
            'author.required' => '著者名は必須です。',
            'isbn.unique'     => 'このISBNはすでに登録されています。',
            'status.in'       => '読書状態が無効です。',
        ]);

        $book = Book::create($validated);

        return redirect()->route('books.show', $book)
            ->with('success', '「' . $book->title . '」を登録しました。');
    }

    public function show(Book $book): View
    {
        $book->load('category');
        return view('books.show', compact('book'));
    }

    public function edit(Book $book): View
    {
        $categories = Category::orderBy('name')->get();
        $genres = Book::select('genre')->whereNotNull('genre')->distinct()->orderBy('genre')->pluck('genre');
        return view('books.edit', compact('book', 'categories', 'genres'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'author'          => 'required|string|max:255',
            'publisher'       => 'nullable|string|max:255',
            'published_year'  => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'isbn'            => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
            'genre'           => 'nullable|string|max:100',
            'description'     => 'nullable|string',
            'status'          => 'required|in:未読,読中,読了',
            'cover_image_url' => 'nullable|url|max:500',
            'category_id'     => 'nullable|exists:categories,id',
            'rating'          => 'nullable|integer|min:1|max:5',
            'read_at'         => 'nullable|date',
        ], [
            'title.required'  => 'タイトルは必須です。',
            'author.required' => '著者名は必須です。',
            'isbn.unique'     => 'このISBNはすでに登録されています。',
            'status.in'       => '読書状態が無効です。',
        ]);

        $book->update($validated);

        return redirect()->route('books.show', $book)
            ->with('success', '「' . $book->title . '」を更新しました。');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $title = $book->title;
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', '「' . $title . '」を削除しました。');
    }
}
