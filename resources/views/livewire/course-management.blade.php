<div class="min-h-screen bg-gray-100">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Course Management</h1>
                    <p class="mt-2 text-gray-600">Create, edit, and manage your courses.</p>
                </div>
                <button wire:click="openCreateForm" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Course
                </button>
            </div>

            <!-- Flash Messages -->
            @if (session()->has('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Create/Edit Form Modal -->
            @if($showCreateForm)
                <div class="mb-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $editingCourseId ? 'Edit Course' : 'Create New Course' }}
                        </h2>
                    </div>
                    
                    <form wire:submit.prevent="saveCourse" class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700">Course Title</label>
                                <input type="text" wire:model="title" id="title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900 placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter course title" />
                                @error('title') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Course Description</label>
                                <textarea wire:model="description" id="description" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900 placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" placeholder="Enter course description"></textarea>
                                @error('description') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Instructor -->
                            <div>
                                <label for="instructor" class="block text-sm font-medium text-gray-700">Instructor</label>
                                <input type="text" wire:model="instructor" id="instructor" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900 placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" placeholder="Instructor name" />
                                @error('instructor') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Duration -->
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700">Duration</label>
                                <input type="text" wire:model="duration" id="duration" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900 placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., 8 hours" />
                                @error('duration') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                <input type="text" wire:model="category" id="category" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900 placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Web Development" />
                                @error('category') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Publish Status -->
                            <div>
                                <label for="is_published" class="flex items-center">
                                    <input type="checkbox" wire:model="is_published" id="is_published" class="h-4 w-4 text-blue-600 rounded" />
                                    <span class="ml-2 text-sm font-medium text-gray-700">Publish Course</span>
                                </label>
                                @error('is_published') <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <button type="button" wire:click="closeForm" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ $editingCourseId ? 'Update Course' : 'Create Course' }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Search and Filter -->
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input type="text" wire:model.live="search" placeholder="Search courses..." class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900 placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <div>
                            <select wire:model.live="sortBy" class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-gray-900 placeholder-gray-500 focus:ring-blue-500 focus:border-blue-500">
                                <option value="newest">Newest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="title">Title A-Z</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Course Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Instructor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Modules</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Lessons</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Enrolled</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($courses as $course)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $course->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $course->instructor }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $course->duration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $course->modules_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $course->lessons_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $course->enrolled_users_count }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $course->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $course->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                        <a href="{{ route('admin.course-detail', $course) }}" class="text-green-600 hover:text-green-900">View</a>
                                        <button wire:click="editCourse({{ $course->id }})" class="text-blue-600 hover:text-blue-900">Edit</button>
                                        <button wire:click="deleteCourse({{ $course->id }})" wire:confirm="Are you sure?" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-600">No courses found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</div>
