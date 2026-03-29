<div class="min-h-screen bg-gray-100">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Browse Courses</h1>
                <p class="mt-2 text-gray-600">Explore and enroll in courses to expand your knowledge.</p>
            </div>

            <!-- Search and Filters -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Courses</label>
                            <input type="text" wire:model.live="search" id="search" placeholder="Search by title, instructor..." class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900 placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select wire:model.live="category" id="category" class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900 placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="sortBy" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select wire:model.live="sortBy" id="sortBy" class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900 placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500">
                                <option value="newest">Newest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="title">Title A-Z</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @forelse($courses as $course)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                        <!-- Course Header -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-32"></div>

                        <!-- Course Content -->
                        <div class="p-6 -mt-12 relative z-10">
                            <div class="mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $course->title }}</h3>
                                <p class="text-sm text-gray-600 mt-1">by {{ $course->instructor }}</p>
                            </div>

                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $course->description }}</p>

                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Modules:</span>
                                    <span class="font-semibold text-gray-900">{{ $course->modules_count }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Lessons:</span>
                                    <span class="font-semibold text-gray-900">{{ $course->lessons_count }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Duration:</span>
                                    <span class="font-semibold text-gray-900">{{ $course->duration }}</span>
                                </div>
                                @if($course->category)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Category:</span>
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $course->category }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-2">
                                @if($course->is_enrolled)
                                    <a href="{{ route('learner.courses.detail', $course) }}" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Continue Learning
                                    </a>
                                @else
                                    @livewire('enroll-button', ['course' => $course], key($course->id))
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12 bg-white rounded-lg shadow-sm">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No courses found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</div>
