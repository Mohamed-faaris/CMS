<div class="min-h-screen bg-gray-100">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('admin.courses') }}" class="text-blue-600 hover:text-blue-900 flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Courses
                </a>
            </div>

            <!-- Course Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $course->title }}</h1>
                    <p class="mt-2 text-gray-600">{{ $course->description }}</p>
                    <div class="mt-4 flex items-center space-x-4">
                        <span class="text-sm text-gray-600"><strong>Instructor:</strong> {{ $course->instructor }}</span>
                        <span class="text-sm text-gray-600"><strong>Duration:</strong> {{ $course->duration }}</span>
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $course->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $course->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Modules Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">Course Modules</h2>
                        <button wire:click="openModuleForm" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Module
                        </button>
                    </div>
                </div>

                <!-- Module Form Modal -->
                @if($showModuleForm)
                    <div class="p-6 bg-blue-50 border-t border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            {{ $editingModuleId ? 'Edit Module' : 'Create New Module' }}
                        </h3>
                        
                        <form wire:submit.prevent="saveModule" class="space-y-4">
                            <div>
                                <label for="moduleTitle" class="block text-sm font-medium text-gray-700">Module Title</label>
                                <input type="text" wire:model="moduleTitle" id="moduleTitle" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900" />
                                @error('moduleTitle') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="moduleDescription" class="block text-sm font-medium text-gray-700">Module Description</label>
                                <textarea wire:model="moduleDescription" id="moduleDescription" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900"></textarea>
                                @error('moduleDescription') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="moduleOrder" class="block text-sm font-medium text-gray-700">Module Order</label>
                                <input type="number" wire:model="moduleOrder" id="moduleOrder" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900" />
                                @error('moduleOrder') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" wire:click="closeModuleForm" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                    {{ $editingModuleId ? 'Update Module' : 'Create Module' }}
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- Modules List -->
                @if($modulesWithLessons->isEmpty())
                    <div class="p-6 text-center text-gray-600">
                        <p>No modules yet. Create your first module to get started.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach($modulesWithLessons as $module)
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $module->order }}. {{ $module->title }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ $module->description }}</p>
                                        <p class="text-xs text-gray-500 mt-2">{{ $module->lessons->count() }} lessons</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button wire:click="editModule({{ $module->id }})" class="text-blue-600 hover:text-blue-900 text-sm font-medium">Edit</button>
                                        <button wire:click="deleteModule({{ $module->id }})" wire:confirm="Are you sure?" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                                    </div>
                                </div>

                                <!-- Lessons for this Module -->
                                @if($module->lessons->count() > 0)
                                    <div class="ml-4 mt-3 space-y-2">
                                        @foreach($module->lessons as $lesson)
                                            <div class="flex items-center text-sm text-gray-600 bg-gray-50 p-2 rounded">
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-2">{{ $lesson->type }}</span>
                                                {{ $lesson->title }}
                                                <span class="ml-auto text-xs text-gray-500">({{ $lesson->duration }} min)</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Add Lesson Button -->
                                <div class="mt-3">
                                    @livewire('admin-lesson-manager', ['moduleId' => $module->id], key($module->id))
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
