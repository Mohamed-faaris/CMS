<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class CourseList extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $sortBy = 'newest';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function render()
    {
        $courses = Course::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('instructor', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->category, function ($query) {
                $query->where('category', $this->category);
            })
            ->when($this->sortBy === 'newest', function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->when($this->sortBy === 'oldest', function ($query) {
                $query->orderBy('created_at', 'asc');
            })
            ->when($this->sortBy === 'title', function ($query) {
                $query->orderBy('title', 'asc');
            })
            ->withCount(['modules', 'lessons'])
            ->paginate(12);

        $categories = Course::whereNotNull('category')
            ->distinct('category')
            ->pluck('category')
            ->filter()
            ->values();

        return view('livewire.course-list', [
            'courses' => $courses,
            'categories' => $categories,
        ]);
    }
}
