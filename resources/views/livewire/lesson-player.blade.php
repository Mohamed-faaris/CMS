<div class="min-h-screen bg-gray-100">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <a href="{{ route('courses.show', $course) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    ← Back to {{ $course->title }}
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Lesson Header -->
                            <div class="mb-6">
                                <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                                    <span>{{ $lesson->module->title }}</span>
                                    <span>•</span>
                                    <span>{{ ucfirst($lesson->type) }}</span>
                                    @if($lesson->duration)
                                        <span>•</span>
                                        <span>{{ $lesson->duration }} min</span>
                                    @endif
                                </div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $lesson->title }}</h1>
                            </div>

                            <!-- Lesson Content -->
                            <div class="mb-6">
                                @if($lesson->type === 'video' && $lesson->video_url)
                                    <div class="aspect-w-16 aspect-h-9 mb-4">
                                        <iframe 
                                            src="{{ $lesson->video_url }}" 
                                            class="w-full h-96 rounded-lg"
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                @elseif($lesson->type === 'video')
                                    <div class="bg-gray-200 h-96 rounded-lg flex items-center justify-center mb-4">
                                        <div class="text-center">
                                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-500">Video content</p>
                                        </div>
                                    </div>
                                @endif

                                @if($lesson->content)
                                    <div class="prose max-w-none">
                                        {!! nl2br(e($lesson->content)) !!}
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-6 border-t">
                                <div>
                                    @if($prevLesson)
                                        <a 
                                            href="{{ route('lessons.player', ['course' => $course, 'lesson' => $prevLesson]) }}"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            ← Previous Lesson
                                        </a>
                                    @endif
                                </div>

                                <div class="flex items-center gap-4">
                                    @if($isEnrolled)
                                        @if($isCompleted)
                                            <span class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 bg-green-100">
                                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Completed
                                            </span>
                                        @else
                                            <button 
                                                wire:click="markComplete"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                            >
                                                Mark as Complete
                                            </button>
                                        @endif
                                    @endif

                                    @if($nextLesson)
                                        <a 
                                            href="{{ route('lessons.player', ['course' => $course, 'lesson' => $nextLesson]) }}"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            Next Lesson →
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Course Content</h3>
                            <div class="space-y-2">
                                @foreach($course->modules as $module)
                                    <div class="mb-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">{{ $module->title }}</h4>
                                        <ul class="space-y-1">
                                            @foreach($module->lessons as $moduleLesson)
                                                <li>
                                                    <a 
                                                        href="{{ route('lessons.player', ['course' => $course, 'lesson' => $moduleLesson]) }}"
                                                        class="flex items-center text-sm {{ $moduleLesson->id === $lesson->id ? 'text-blue-600 font-medium' : 'text-gray-600 hover:text-gray-900' }}"
                                                    >
                                                        @if($moduleLesson->id === $lesson->id)
                                                            <svg class="h-4 w-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        @endif
                                                        {{ $moduleLesson->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
