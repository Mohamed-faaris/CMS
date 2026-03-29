<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Course Header -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="md:flex">
                <div class="md:flex-shrink-0">
                    @if($course->thumbnail)
                        <img class="h-48 w-full object-cover md:h-full md:w-48" src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}">
                    @else
                        <div class="h-48 w-full md:h-full md:w-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="p-8 flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $course->title }}</h1>
                            <p class="mt-2 text-gray-600">By {{ $course->instructor }}</p>
                        </div>
                        <livewire:enroll-button :course="$course" />
                    </div>
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $course->duration }}
                        <span class="mx-2">•</span>
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        {{ $course->modules->count() }} modules
                        <span class="mx-2">•</span>
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        {{ $course->modules->flatMap->lessons->count() }} lessons
                    </div>
                    <p class="mt-4 text-gray-700">{{ $course->description }}</p>
                </div>
            </div>
        </div>

        <!-- Course Content -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Course Content</h2>
                
                @if($course->modules->isEmpty())
                    <p class="text-gray-500">No content available yet.</p>
                @else
                    <div class="space-y-4">
                        @foreach($course->modules as $module)
                            <div class="border border-gray-200 rounded-lg">
                                <button class="w-full px-4 py-3 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition" onclick="this.nextElementSibling.classList.toggle('hidden')">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                        </svg>
                                        <span class="font-medium text-gray-900">{{ $module->title }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-500 mr-2">{{ $module->lessons->count() }} lessons</span>
                                        <svg class="w-5 h-5 text-gray-400 transform rotate-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </button>
                                <div class="hidden px-4 py-3 border-t border-gray-200">
                                    @if($module->description)
                                        <p class="text-sm text-gray-600 mb-3">{{ $module->description }}</p>
                                    @endif
                                    <ul class="space-y-2">
                                        @foreach($module->lessons as $lesson)
                                            <li class="flex items-center text-sm">
                                                <a href="{{ route('lessons.detail', $lesson) }}" class="flex items-center text-gray-700 hover:text-blue-600">
                                                    @if($lesson->type === 'video')
                                                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    @endif
                                                    <span>{{ $lesson->title }}</span>
                                                </a>
                                                @if($lesson->duration)
                                                    <span class="ml-auto text-gray-400">{{ $lesson->duration }} min</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
