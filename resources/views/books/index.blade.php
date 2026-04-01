@extends('layouts.app')

@section('title', '本の一覧')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        <div class="text-3xl font-bold text-gray-700">{{ $stats['total'] }}</div>
        <div class="text-sm text-gray-500 mt-1">全冊数</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        <div class="text-3xl font-bold text-green-600">{{ $stats['read'] }}</div>
        <div class="text-sm text-gray-500 mt-1">読了</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        <div class="text-3xl font-bold text-teal-600">{{ $stats['reading'] }}</div>
        <div class="text-sm text-gray-500 mt-1">読中</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
        <div class="text-3xl font-bold text-gray-400">{{ $stats['unread'] }}</div>
        <div class="text-sm text-gray-500 mt-1">未読</div>
    </div>
</div>

{{-- Search & Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
    <form method="GET" action="{{ route('books.index') }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">タイトル</label>
                <input type="text" name="title" value="{{ request('title') }}"
                    placeholder="タイトルで検索"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">著者</label>
                <input type="text" name="author" value="{{ request('author') }}"
                    placeholder="著者名で検索"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">ジャンル</label>
                <input type="text" name="genre" value="{{ request('genre') }}"
                    placeholder="ジャンルで検索"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">読書状態</label>
                <select name="status"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent bg-white">
                    <option value="">すべて</option>
                    <option value="未読" @selected(request('status') === '未読')>未読</option>
                    <option value="読中" @selected(request('status') === '読中')>読中</option>
                    <option value="読了" @selected(request('status') === '読了')>読了</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">あらすじ・メモ</label>
                <input type="text" name="description" value="{{ request('description') }}"
                    placeholder="あらすじ・メモで検索"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">カテゴリ</label>
                <select name="category_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent bg-white">
                    <option value="">すべて</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex gap-2 mt-3">
            <button type="submit"
                class="bg-green-700 hover:bg-green-800 text-white text-sm font-medium px-5 py-2 rounded-lg transition shadow-sm">
                検索
            </button>
            <a href="{{ route('books.index') }}"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2 rounded-lg transition">
                リセット
            </a>
        </div>
    </form>
</div>

{{-- Results Header --}}
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-gray-500">
        {{ $books->total() }} 件の本が見つかりました
        @if(request()->hasAny(['title', 'author', 'genre', 'status', 'category_id', 'description']))
            <span class="text-green-800 ml-1">(フィルター適用中)</span>
        @endif
    </p>
    <a href="{{ route('books.create') }}"
        class="text-sm text-green-800 hover:text-blue-800 font-medium flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新しく追加
    </a>
</div>

{{-- Book Grid --}}
@if($books->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        @foreach($books as $book)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                {{-- Cover Image --}}
                <a href="{{ route('books.show', $book) }}" class="block">
                    <div class="h-48 bg-gradient-to-br from-green-200 to-emerald-300 flex items-center justify-center overflow-hidden relative">
                        @if($book->cover_image_url)
                            <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}"
                                class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
                                onerror="this.parentElement.innerHTML='<div class=\'flex flex-col items-center justify-center h-full text-green-300\'><svg class=\'w-16 h-16\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1\' d=\'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253\'/></svg></div>'">
                        @else
                            <div class="flex flex-col items-center justify-center text-green-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        @endif
                        {{-- Status badge --}}
                        <div class="absolute top-2 right-2">
                            @php
                                $badgeColor = match($book->status) {
                                    '読了' => 'bg-green-100 text-green-800 border-green-200',
                                    '読中' => 'bg-teal-100 text-teal-700 border-teal-200',
                                    default => 'bg-gray-100 text-gray-600 border-gray-200',
                                };
                            @endphp
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full border {{ $badgeColor }}">
                                {{ $book->status }}
                            </span>
                        </div>
                    </div>
                </a>

                {{-- Book Info --}}
                <div class="p-4">
                    <a href="{{ route('books.show', $book) }}" class="block">
                        <h3 class="font-semibold text-gray-800 line-clamp-2 hover:text-green-800 transition leading-snug">
                            {{ $book->title }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $book->author }}</p>
                    </a>

                    @if($book->genre)
                        <span class="inline-block mt-2 text-xs bg-green-200 text-green-900 px-2 py-0.5 rounded-full">
                            {{ $book->genre }}
                        </span>
                    @endif

                    @if($book->rating)
                        <div class="flex items-center gap-0.5 mt-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3.5 h-3.5 {{ $i <= $book->rating ? 'text-yellow-400' : 'text-gray-200' }}"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="flex gap-2 mt-3 pt-3 border-t border-gray-100">
                        <a href="{{ route('books.edit', $book) }}"
                            class="flex-1 text-center text-xs text-gray-600 hover:text-green-800 hover:bg-green-200 py-1.5 rounded-lg transition">
                            編集
                        </a>
                        <form action="{{ route('books.destroy', $book) }}" method="POST" class="flex-1"
                            onsubmit="return confirm('「{{ addslashes($book->title) }}」を削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full text-xs text-gray-600 hover:text-red-700 hover:bg-red-50 py-1.5 rounded-lg transition">
                                削除
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $books->links() }}
    </div>

@else
    <div class="bg-white rounded-xl border border-dashed border-gray-300 p-16 text-center">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <p class="text-gray-500 text-lg font-medium">本が見つかりませんでした</p>
        <p class="text-gray-400 text-sm mt-1">条件を変えて検索してみてください</p>
        <a href="{{ route('books.create') }}"
            class="inline-block mt-4 bg-green-700 hover:bg-green-800 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition shadow-sm">
            最初の本を追加する
        </a>
    </div>
@endif

@endsection
