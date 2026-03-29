<div class="min-h-screen bg-gray-100">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Course Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <div class="md:w-1/3">
                            <div class="h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                @if($course->thumbnail)
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <svg class="h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="md:w-2/3">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-sm font-medium text-blue-600 bg-blue-100 px-2 py-1 rounded">{{ $course->category ?? 'General' }}</span>
                                <span class="text-sm text-gray-500">{{ $course->modules->count() }} modules • {{ $course->modules->flatMap->lessons->count() }} lessons</span>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $course->title }}</h1>
                            <p class="text-gray-600 mb-4">{{ $course->description }}</p>
                            <div class="flex items-center gap-4 mb-6">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-600">By {{ $course->instructor }}</span>
                                </div>
                                @if($course->duration)
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">{{ $course->duration }}</span>
                                    </div>
                                @endif
                            </div>

                            @if(!$isEnrolled)
                                <button 
                                    wire:click="enroll"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    Enroll Now
                                </button>
                            @else
                                <div class="mb-4">
                                    <div class="flex items-center justify-between text-sm mb-2">
                                        <span class="text-gray-600">Your Progress</span>
                                        <span class="font-medium text-gray-900">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                                @if($currentLesson)
                                    <a 
                                        href="#lesson-{{ $currentLesson->id }}"
                                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                                    >
                                        Continue Learning
                                    </a>
                                @else
                                    <div class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-gray-400">
                                        Course Completed!
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Course Content</h2>
                            
                            @if($course->modules->isEmpty())
                                <p class="text-gray-500">No content available yet.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($course->modules as $moduleIndex => $module)
                                        <div class="border rounded-lg overflow-hidden">
                                            <div class="bg-gray-50 px-4 py-3 border-b">
                                                <h3 class="text-lg font-medium text-gray-900">
                                                    Module {{ $moduleIndex + 1 }}: {{ $module->title }}
                                                </h3>
                                                @if($module->description)
                                                    <p class="text-sm text-gray-600 mt-1">{{ $module->description }}</p>
                                                @endif
                                            </div>
                                            <div class="divide-y">
                                                @foreach($module->lessons as $lessonIndex => $lesson)
                                                    <div 
                                                        id="lesson-{{ $lesson->id }}"
                                                        class="px-4 py-3 flex items-center justify-between hover:bg-gray-50"
                                                    >
                                                        <div class="flex items-center">
                                                            @if(in_array($lesson->id, $completedLessons))
                                                                <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                            @else
                                                                <svg class="h-5 w-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                            @endif
                                                            <div>
                                                                <p class="text-sm font-medium text-gray-900">
                                                                    {{ $lessonIndex + 1 }}. {{ $lesson->title }}
                                                                </p>
                                                                <p class="text-xs text-gray-500">
                                                                    {{ ucfirst($lesson->type) }} @if($lesson->duration) • {{ $lesson->duration }} min @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                        @if($isEnrolled)
                                                            @if(in_array($lesson->id, $completedLessons))
                                                                <span class="text-sm text-green-600 font-medium">Completed</span>
                                                            @else
                                                                <button 
                                                                    wire:click="markLessonComplete({{ $lesson->id }})"
                                                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                                                                >
                                                                    Mark Complete
                                                                </button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Course Info</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Instructor</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $course->instructor }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Modules</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $course->modules->count() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Lessons</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $course->modules->flatMap->lessons->count() }}</dd>
                                </div>
                                @if($course->duration)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Duration</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $course->duration }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $course->category ?? 'General' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
