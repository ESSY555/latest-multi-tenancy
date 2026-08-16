@props(['books' => collect()])

<div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">E-Library</h2>
        <div class="flex w-full gap-3 sm:w-auto">
            <div class="relative flex w-full sm:w-96">
                <input id="elibrary-search-input" type="text" placeholder="Search books..." class="flex-1 pl-12 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none h-5 w-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg> --}}
            </div>
        </div>
    </div>

    <!-- Categories Filter -->
    <div class="flex flex-wrap gap-2 mb-6">
        <button class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors text-sm">All</button>
        <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors text-sm">Mathematics</button>
        <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors text-sm">Science</button>
        <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors text-sm">Literature</button>
        <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors text-sm">History</button>
        <button class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors text-sm">Technology</button>
    </div>

    <!-- Books Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($books as $book)
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow elibrary-book-card">
            <!-- Book Cover -->
            <div class="h-48 bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
                @if($book->cover_image_url)
                    <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253"/>
                    </svg>
                @endif
            </div>
            
            <!-- Book Details -->
            <div class="p-4">
                <h3 class="font-semibold text-gray-900 mb-2 elibrary-book-title">{{ $book->title }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($book->description, 60) }}</p>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs text-gray-500 elibrary-book-author">Author: {{ $book->author }}</span>
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($book->status === 'available') bg-green-100 text-green-800
                        @elseif($book->status === 'borrowed') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($book->status) }}
                    </span>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-2">
                    @if($book->isAvailable())
                        <a href="{{ route('resources.elibrary.read', $book) }}" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm text-center">Read</a>
                    @else
                        <button class="flex-1 px-3 py-2 bg-gray-400 text-white rounded cursor-not-allowed text-sm">Unavailable</button>
                    @endif
                    
                    <a href="{{ route('resources.elibrary.download', $book) }}" class="px-3 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm" aria-label="Download">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 5.477 5.754 5 7.5 5s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 19 16.5 19c-1.746 0-3.332-.523-4.5-1.253"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No books found</h3>
            <p class="text-gray-500">Try adjusting your search or check back later.</p>
        </div>
        @endforelse
    </div>

    <!-- Quick Stats -->
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <h4 class="font-semibold text-blue-900 mb-2">Total Books</h4>
            <p class="text-2xl font-bold text-blue-600">{{ $books->count() }}</p>
        </div>
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <h4 class="font-semibold text-green-900 mb-2">Available</h4>
            <p class="text-2xl font-bold text-green-600">{{ $books->where('status', 'available')->count() }}</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <h4 class="font-semibold text-yellow-900 mb-2">Borrowed</h4>
            <p class="text-2xl font-bold text-yellow-600">{{ $books->where('status', 'borrowed')->count() }}</p>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
            <h4 class="font-semibold text-purple-900 mb-2">Categories</h4>
            <p class="text-2xl font-bold text-purple-600">{{ $books->pluck('category')->filter()->unique()->count() }}</p>
        </div>
    </div>

    
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('elibrary-search-input');
    const bookCards = Array.from(document.querySelectorAll('.elibrary-book-card'));

    function filterBooks() {
        const query = searchInput.value.trim().toLowerCase();
        bookCards.forEach(card => {
            const title = card.querySelector('.elibrary-book-title')?.textContent.toLowerCase() || '';
            const author = card.querySelector('.elibrary-book-author')?.textContent.toLowerCase() || '';
            if (query === '' || title.includes(query) || author.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function debounce(fn, delay) {
        let t;
        return function(...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    const debouncedFilter = debounce(filterBooks, 200);

    searchInput?.addEventListener('input', debouncedFilter);

    searchInput?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            filterBooks();
        }
    });

    // Buttons now navigate to backend routes via <a> links; no JS needed here.
});
</script>

