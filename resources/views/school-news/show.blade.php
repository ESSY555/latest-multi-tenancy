@extends('layouts.app')

@section('title', $schoolNews->title . ' - School News - Bezaleel')

@section('content')
    <!-- Article Header -->
    <section class="relative bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 py-16">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="mb-6">
                <span class="bg-white/20 text-white px-4 py-2 rounded-full text-sm font-medium backdrop-blur-sm">
                    {{ $schoolNews->branch->name }}
                </span>
            </div>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                {{ $schoolNews->title }}
            </h1>
            <div class="flex items-center justify-center text-blue-100 text-sm space-x-4">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    {{ $schoolNews->author->name }}
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    {{ $schoolNews->formatted_published_at }}
                </div>
            </div>
        </div>
    </section>

    <!-- Article Content -->
    <section class="py-12 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="prose prose-lg max-w-none">
                @if($schoolNews->image_path)
                    <div class="mb-8">
                        <img src="{{ Storage::disk('public')->url($schoolNews->image_path) }}" alt="{{ $schoolNews->title }}"
                            class="w-full h-96 object-cover rounded-xl shadow-lg">
                    </div>
                @endif

                @if($schoolNews->excerpt)
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-6 mb-8 rounded-r-lg">
                        <p class="text-lg text-blue-800 font-medium italic">
                            "{{ $schoolNews->excerpt }}"
                        </p>
                    </div>
                @endif

                <div class="text-gray-700 leading-relaxed">
                    {!! $schoolNews->content !!}
                </div>
            </article>

            <!-- Article Footer -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row items-center justify-between">
                    <div class="flex items-center text-sm text-gray-500 mb-4 sm:mb-0">
                        <span>Published in {{ $schoolNews->branch->name }}</span>
                        <span class="mx-2">•</span>
                        <span>By {{ $schoolNews->author->name }}</span>
                    </div>

                    @auth
                        @can('super-admin')
                            <div class="flex space-x-3">
                                <a href="{{ route('school-news.edit', $schoolNews) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit Article
                                </a>
                                <form method="POST" action="{{ route('school-news.destroy', $schoolNews) }}" class="inline"
                                    onsubmit="return confirm('Are you sure you want to delete this article?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Delete Article
                                    </button>
                                </form>
                            </div>
                        @endcan
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Related Articles -->
    @if($relatedNews->count() > 0)
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Related Articles</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($relatedNews as $article)
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

                                <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2">
                                    <a href="{{ route('school-news.show', $article) }}"
                                        class="hover:text-blue-600 transition-colors">
                                        {{ $article->title }}
                                    </a>
                                </h3>

                                @if($article->excerpt)
                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $article->excerpt }}</p>
                                @else
                                    <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit(strip_tags($article->content), 100) }}</p>
                                @endif

                                <a href="{{ route('school-news.show', $article) }}"
                                    class="text-blue-600 hover:text-blue-700 font-medium text-sm">
                                    Read More →
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Back to News -->
    <section class="py-8 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <a href="{{ route('school-news.index') }}"
                class="inline-flex items-center px-6 py-3 border-2 border-blue-600 text-blue-600 font-medium rounded-lg hover:bg-blue-600 hover:text-white transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Back to All News
            </a>
        </div>
    </section>
@endsection
