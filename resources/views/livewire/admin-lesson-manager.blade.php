<div class="mt-2">
    @if($showLessonForm)
        <div class="bg-gray-50 border border-gray-300 rounded p-4">
            <h4 class="font-semibold text-gray-900 mb-3">
                {{ $editingLessonId ? 'Edit Lesson' : 'Add Lesson to ' . $module->title }}
            </h4>
            
            <form wire:submit.prevent="saveLesson" class="space-y-3">
                <div>
                    <label for="lessonTitle" class="block text-sm font-medium text-gray-700">Lesson Title</label>
                    <input type="text" wire:model="lessonTitle" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm" />
                    @error('lessonTitle') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="lessonType" class="block text-sm font-medium text-gray-700">Type</label>
                        <select wire:model="lessonType" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm">
                            <option value="video">Video</option>
                            <option value="text">Text</option>
                            <option value="quiz">Quiz</option>
                        </select>
                        @error('lessonType') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="lessonDuration" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                        <input type="number" wire:model="lessonDuration" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm" />
                        @error('lessonDuration') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label for="lessonContent" class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea wire:model="lessonContent" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 text-sm"></textarea>
                    @error('lessonContent') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" wire:click="closeLessonForm" class="px-3 py-1 border border-gray-300 rounded text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-3 py-1 border border-transparent rounded text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        {{ $editingLessonId ? 'Update' : 'Add' }} Lesson
                    </button>
                </div>
            </form>
        </div>
    @else
        <button wire:click="openLessonForm" class="text-blue-600 hover:text-blue-900 text-xs font-medium">+ Add Lesson</button>
    @endif
</div>
