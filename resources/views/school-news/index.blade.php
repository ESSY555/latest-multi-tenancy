@extends('layouts.app')

@section('title', 'School News - Bezaleel')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 py-16">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">School News</h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                Stay updated with the latest happenings, achievements, and announcements from our school community
            </p>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-8 bg-gray-50 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('school-news.index') }}"
                class="flex flex-col sm:flex-row gap-4 items-center justify-center">
                <div class="flex-1 max-w-md">
                    <select name="branch_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Filter
                </button>
                @if(request('branch_id'))
                    <a href="{{ route('school-news.index') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                        Clear Filter
                    </a>
                @endif
            </form>
        </div>
    </section>

    <!-- News Grid -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($news->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($news as $article)
                        <article
                            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            @if($article->image_path)
                                <div class="aspect-w-16 aspect-h-9">
                                    <img src="{{ Storage::disk('public')->url($article->image_path) }}" alt="{{ $article->title }}"
                                        class="w-full h-48 object-cover">
                                </div>
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <div class="p-6">
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $article->branch->name }}
                                    </span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $article->formatted_published_at }}</span>
                                </div>

                                <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                                    <a href="{{ route('school-news.show', $article) }}"
                                        class="hover:text-blue-600 transition-colors">
                                        {{ $article->title }}
                                    </a>
                                </h2>

                                @if($article->excerpt)
                                    <p class="text-gray-600 mb-4 line-clamp-3">{{ $article->excerpt }}</p>
                                @else
                                    <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit(strip_tags($article->content), 150) }}</p>
                                @endif

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $article->author->name }}
                                    </div>
                                    <a href="{{ route('school-news.show', $article) }}"
                                        class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                        Read More →
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $news->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No News Articles Found</h3>
                    <p class="text-gray-600 mb-6">
                        @if(request('branch_id'))
                            No news articles found for the selected branch.
                        @else
                            No news articles have been published yet.
                        @endif
                    </p>
                    @auth
                        @can('super-admin')
                            <a href="{{ route('school-news.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create First Article
                            </a>
                        @endcan
                    @endauth
                </div>
            @endif
        </div>
    </section>

    <!-- Admin Actions (Super Admin Only) -->
    @auth
        @can('super-admin')
            <section class="py-8 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">News Management</h2>
                        <p class="text-gray-600 mb-6">Manage all school news articles and publications</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('school-news.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create News Article
                            </a>
                            <a href="{{ route('school-news.admin') }}"
                                class="inline-flex items-center px-6 py-3 border-2 border-blue-600 text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                Manage All News
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endcan
    @endauth
@endsection
