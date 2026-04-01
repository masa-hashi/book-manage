@extends('layouts.app')

@section('title', '「' . $book->title . '」を編集')

@section('content')

<div class="mb-4">
    <a href="{{ route('books.show', $book) }}" class="text-sm text-green-800 hover:text-green-950 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        詳細に戻る
    </a>
</div>

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-green-700 to-teal-600 px-6 py-5">
            <h1 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                本の情報を編集
            </h1>
            <p class="text-green-100 text-sm mt-0.5 truncate">{{ $book->title }}</p>
        </div>

        <div class="p-6">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-5">
                    <p class="text-sm font-medium text-red-700 mb-2">入力内容を確認してください:</p>
                    <ul class="text-sm text-red-600 list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('books.update', $book) }}" method="POST">
                @csrf
                @method('PUT')
                @include('books._form')
                <div class="flex gap-3 mt-6 pt-5 border-t border-gray-100">
                    <button type="submit"
                        class="bg-green-700 hover:bg-green-800 text-white font-medium px-6 py-2.5 rounded-lg transition shadow-sm">
                        更新する
                    </button>
                    <a href="{{ route('books.show', $book) }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-6 py-2.5 rounded-lg transition">
                        キャンセル
                    </a>
                    <div class="flex-1"></div>
                    <form action="{{ route('books.destroy', $book) }}" method="POST"
                        onsubmit="return confirm('「{{ addslashes($book->title) }}」を削除しますか？')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-red-500 hover:text-red-700 hover:bg-red-50 border border-transparent hover:border-red-200 font-medium px-4 py-2.5 rounded-lg transition text-sm">
                            この本を削除
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
