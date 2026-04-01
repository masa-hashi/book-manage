@extends('layouts.app')

@section('title', $book->title)

@section('content')

<div class="mb-4">
    <a href="{{ route('books.index') }}" class="text-sm text-green-800 hover:text-green-950 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        一覧に戻る
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="md:flex">
        {{-- Cover --}}
        <div class="md:w-64 md:flex-shrink-0 bg-gradient-to-br from-green-200 to-emerald-300 flex items-center justify-center min-h-64 p-8">
            @if($book->cover_image_url)
                <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}"
                    class="max-h-72 w-auto rounded-lg shadow-md object-cover"
                    onerror="this.parentElement.innerHTML='<svg class=\'w-24 h-24 text-green-300\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1\' d=\'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253\'/></svg>'">
            @else
                <svg class="w-24 h-24 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            @endif
        </div>

        {{-- Details --}}
        <div class="flex-1 p-6 md:p-8">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 leading-tight">{{ $book->title }}</h1>
                    <p class="text-lg text-gray-600 mt-1">{{ $book->author }}</p>
                </div>
                @php
                    $badgeColor = match($book->status) {
                        '読了' => 'bg-green-100 text-green-700 border-green-200',
                        '読中' => 'bg-teal-100 text-teal-700 border-teal-200',
                        default => 'bg-gray-100 text-gray-600 border-gray-200',
                    };
                @endphp
                <span class="text-sm font-semibold px-3 py-1.5 rounded-full border {{ $badgeColor }}">
                    {{ $book->status }}
                </span>
            </div>

            {{-- Rating --}}
            @if($book->rating)
                <div class="flex items-center gap-1 mt-3">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= $book->rating ? 'text-yellow-400' : 'text-gray-200' }}"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                    <span class="text-sm text-gray-500 ml-1">{{ $book->rating }}/5</span>
                </div>
            @endif

            {{-- Book details table --}}
            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                @if($book->publisher)
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wide w-20 flex-shrink-0">出版社</span>
                    <span class="text-sm text-gray-700">{{ $book->publisher }}</span>
                </div>
                @endif
                @if($book->published_year)
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wide w-20 flex-shrink-0">出版年</span>
                    <span class="text-sm text-gray-700">{{ $book->published_year }}年</span>
                </div>
                @endif
                @if($book->isbn)
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wide w-20 flex-shrink-0">ISBN</span>
                    <span class="text-sm text-gray-700 font-mono">{{ $book->isbn }}</span>
                </div>
                @endif
                @if($book->genre)
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wide w-20 flex-shrink-0">ジャンル</span>
                    <span class="text-sm bg-green-200 text-green-900 px-2 py-0.5 rounded-full">{{ $book->genre }}</span>
                </div>
                @endif
                @if($book->category)
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wide w-20 flex-shrink-0">カテゴリ</span>
                    <span class="text-sm text-gray-700">{{ $book->category->name }}</span>
                </div>
                @endif
                @if($book->read_at)
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-400 uppercase tracking-wide w-20 flex-shrink-0">読了日</span>
                    <span class="text-sm text-gray-700">{{ $book->read_at->format('Y年m月d日') }}</span>
                </div>
                @endif
            </div>

            {{-- Description --}}
            @if($book->description)
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">あらすじ・メモ</h3>
                    <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $book->description }}</p>
                </div>
            @endif

            {{-- Action buttons --}}
            <div class="flex gap-3 mt-6 pt-5 border-t border-gray-100">
                <a href="{{ route('books.edit', $book) }}"
                    class="bg-green-700 hover:bg-green-800 text-white text-sm font-medium px-5 py-2 rounded-lg transition shadow-sm flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    編集
                </a>
                <form action="{{ route('books.destroy', $book) }}" method="POST"
                    onsubmit="return confirm('「{{ addslashes($book->title) }}」を削除しますか？この操作は取り消せません。')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-white hover:bg-red-50 border border-gray-200 hover:border-red-200 text-gray-600 hover:text-red-600 text-sm font-medium px-5 py-2 rounded-lg transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        削除
                    </button>
                </form>
            </div>

            <p class="text-xs text-gray-400 mt-4">
                登録日: {{ $book->created_at->format('Y年m月d日') }}
                @if($book->updated_at != $book->created_at)
                    ・更新日: {{ $book->updated_at->format('Y年m月d日') }}
                @endif
            </p>
        </div>
    </div>
</div>

@endsection
