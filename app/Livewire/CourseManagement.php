<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.app')]
class CourseManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'newest';
    public $showCreateForm = false;
    public $editingCourseId = null;

    // Form properties
    public $title = '';
    public $description = '';
    public $instructor = '';
    public $duration = '';
    public $category = '';
    public $is_published = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string|min:10',
        'instructor' => 'required|string|max:255',
        'duration' => 'required|string|max:100',
        'category' => 'nullable|string|max:100',
        'is_published' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset(['title', 'description', 'instructor', 'duration', 'category', 'is_published', 'editingCourseId', 'showCreateForm']);
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = true;
    }

    public function closeForm()
    {
        $this->resetForm();
    }

    public function saveCourse()
    {
        $this->validate();

        if ($this->editingCourseId) {
            $course = Course::find($this->editingCourseId);
            $course->update([
                'title' => $this->title,
                'description' => $this->description,
                'instructor' => $this->instructor,
                'duration' => $this->duration,
                'category' => $this->category,
                'is_published' => $this->is_published,
            ]);
            session()->flash('success', 'Course updated successfully!');
        } else {
            Course::create([
                'title' => $this->title,
                'description' => $this->description,
                'instructor' => $this->instructor,
                'duration' => $this->duration,
                'category' => $this->category,
                'is_published' => $this->is_published,
            ]);
            session()->flash('success', 'Course created successfully!');
        }

        $this->resetForm();
    }

    public function editCourse($courseId)
    {
        $course = Course::find($courseId);
        $this->editingCourseId = $courseId;
        $this->title = $course->title;
        $this->description = $course->description;
        $this->instructor = $course->instructor;
        $this->duration = $course->duration;
        $this->category = $course->category;
        $this->is_published = $course->is_published;
        $this->showCreateForm = true;
    }

    public function deleteCourse($courseId)
    {
        Course::find($courseId)->delete();
        session()->flash('success', 'Course deleted successfully!');
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
            ->when($this->sortBy === 'newest', function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->when($this->sortBy === 'oldest', function ($query) {
                $query->orderBy('created_at', 'asc');
            })
            ->when($this->sortBy === 'title', function ($query) {
                $query->orderBy('title', 'asc');
            })
            ->withCount(['modules', 'lessons', 'enrolledUsers'])
            ->paginate(10);

        return view('livewire.course-management', [
            'courses' => $courses,
        ]);
    }
}
