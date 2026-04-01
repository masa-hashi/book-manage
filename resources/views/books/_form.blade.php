{{-- Shared form partial for create and edit --}}

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    {{-- Title --}}
    <div class="md:col-span-2">
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
            タイトル <span class="text-red-500">*</span>
        </label>
        <input type="text" id="title" name="title" value="{{ old('title', $book->title ?? '') }}"
            required placeholder="例：吾輩は猫である"
            class="w-full border @error('title') border-red-400 bg-red-50 @else border-gray-300 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
        @error('title')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Author --}}
    <div>
        <label for="author" class="block text-sm font-medium text-gray-700 mb-1">
            著者 <span class="text-red-500">*</span>
        </label>
        <input type="text" id="author" name="author" value="{{ old('author', $book->author ?? '') }}"
            required placeholder="例：夏目漱石"
            class="w-full border @error('author') border-red-400 bg-red-50 @else border-gray-300 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
        @error('author')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Publisher --}}
    <div>
        <label for="publisher" class="block text-sm font-medium text-gray-700 mb-1">出版社</label>
        <input type="text" id="publisher" name="publisher" value="{{ old('publisher', $book->publisher ?? '') }}"
            placeholder="例：新潮社"
            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
    </div>

    {{-- Published Year --}}
    <div>
        <label for="published_year" class="block text-sm font-medium text-gray-700 mb-1">出版年</label>
        <input type="number" id="published_year" name="published_year"
            value="{{ old('published_year', $book->published_year ?? '') }}"
            placeholder="例：2023" min="1000" max="{{ date('Y') + 1 }}"
            class="w-full border @error('published_year') border-red-400 bg-red-50 @else border-gray-300 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
        @error('published_year')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- ISBN --}}
    <div>
        <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
        <input type="text" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}"
            placeholder="例：978-4-10-109001-7"
            class="w-full border @error('isbn') border-red-400 bg-red-50 @else border-gray-300 @enderror rounded-lg px-3 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
        @error('isbn')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Genre --}}
    <div>
        <label for="genre" class="block text-sm font-medium text-gray-700 mb-1">ジャンル</label>
        <input type="text" id="genre" name="genre" value="{{ old('genre', $book->genre ?? '') }}"
            placeholder="例：小説、ビジネス、技術書"
            list="genre-list"
            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
        <datalist id="genre-list">
            @foreach($genres as $g)
                <option value="{{ $g }}">
            @endforeach
        </datalist>
    </div>

    {{-- Category --}}
    <div>
        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">カテゴリ</label>
        <select id="category_id" name="category_id"
            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
            <option value="">カテゴリなし</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id ?? '') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Status --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            読書状態 <span class="text-red-500">*</span>
        </label>
        <div class="flex gap-3">
            @foreach(['未読', '読中', '読了'] as $statusOption)
                @php
                    $isSelected = old('status', $book->status ?? '未読') === $statusOption;
                    $colors = match($statusOption) {
                        '読了' => 'border-green-400 bg-green-50 text-green-700',
                        '読中' => 'border-teal-400 bg-teal-50 text-teal-700',
                        default => 'border-gray-300 bg-gray-50 text-gray-700',
                    };
                @endphp
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="status" value="{{ $statusOption }}" class="sr-only"
                        @checked($isSelected)>
                    <div class="text-center border-2 rounded-lg py-2.5 text-sm font-medium transition cursor-pointer
                        {{ $isSelected ? $colors . ' ring-2 ring-offset-1' : 'border-gray-200 bg-white text-gray-500 hover:border-gray-300' }}"
                        onclick="selectStatus(this, '{{ $statusOption }}')">
                        {{ $statusOption }}
                    </div>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Rating --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">評価</label>
        <div class="flex items-center gap-2">
            @for($i = 1; $i <= 5; $i++)
                <label class="cursor-pointer">
                    <input type="radio" name="rating" value="{{ $i }}" class="sr-only"
                        @checked(old('rating', $book->rating ?? 0) == $i)>
                    <svg class="w-7 h-7 rating-star transition-colors cursor-pointer
                        {{ old('rating', $book->rating ?? 0) >= $i ? 'text-yellow-400' : 'text-gray-300' }}"
                        fill="currentColor" viewBox="0 0 20 20"
                        onclick="setRating({{ $i }})">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </label>
            @endfor
            <button type="button" onclick="clearRating()" class="text-xs text-gray-400 hover:text-gray-600 ml-1">クリア</button>
        </div>
    </div>

    {{-- Read At --}}
    <div>
        <label for="read_at" class="block text-sm font-medium text-gray-700 mb-1">読了日</label>
        <input type="date" id="read_at" name="read_at"
            value="{{ old('read_at', isset($book) && $book->read_at ? $book->read_at->format('Y-m-d') : '') }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
    </div>

    {{-- Cover Image URL --}}
    <div class="md:col-span-2">
        <label for="cover_image_url" class="block text-sm font-medium text-gray-700 mb-1">表紙画像URL</label>
        <input type="url" id="cover_image_url" name="cover_image_url"
            value="{{ old('cover_image_url', $book->cover_image_url ?? '') }}"
            placeholder="https://example.com/cover.jpg"
            class="w-full border @error('cover_image_url') border-red-400 bg-red-50 @else border-gray-300 @enderror rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent">
        @error('cover_image_url')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Description --}}
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">あらすじ・メモ</label>
        <textarea id="description" name="description" rows="4"
            placeholder="あらすじや読書メモを入力してください..."
            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent resize-y">{{ old('description', $book->description ?? '') }}</textarea>
    </div>

</div>

<script>
function selectStatus(el, value) {
    document.querySelectorAll('input[name="status"]').forEach(r => {
        r.checked = r.value === value;
        const div = r.nextElementSibling;
        const colors = {
            '読了': 'border-green-400 bg-green-50 text-green-700 ring-2 ring-offset-1',
            '読中': 'border-teal-400 bg-teal-50 text-teal-700 ring-2 ring-offset-1',
            '未読': 'border-gray-300 bg-gray-50 text-gray-700 ring-2 ring-offset-1',
        };
        if (r.value === value) {
            div.className = 'text-center border-2 rounded-lg py-2.5 text-sm font-medium transition cursor-pointer ' + colors[value];
        } else {
            div.className = 'text-center border-2 rounded-lg py-2.5 text-sm font-medium transition cursor-pointer border-gray-200 bg-white text-gray-500 hover:border-gray-300';
        }
    });
}

function setRating(value) {
    const radios = document.querySelectorAll('input[name="rating"]');
    const stars = document.querySelectorAll('.rating-star');
    radios.forEach((r, i) => {
        r.checked = (i + 1) === value;
        stars[i].className = stars[i].className.replace(/text-(yellow|gray)-\d+/, (i + 1) <= value ? 'text-yellow-400' : 'text-gray-300');
    });
}

function clearRating() {
    document.querySelectorAll('input[name="rating"]').forEach(r => r.checked = false);
    document.querySelectorAll('.rating-star').forEach(s => {
        s.className = s.className.replace('text-yellow-400', 'text-gray-300');
    });
}
</script>
