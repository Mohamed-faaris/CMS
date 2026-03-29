<div>
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if ($isEnrolled)
        <div class="flex items-center gap-4">
            <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Enrolled
            </span>
            <button 
                wire:click="unenroll" 
                wire:confirm="Are you sure you want to unenroll from this course?"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
            >
                Unenroll
            </button>
        </div>
    @else
        <button 
            wire:click="enroll" 
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Enroll Now
        </button>
    @endif
</div>
