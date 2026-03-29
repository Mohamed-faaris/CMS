<?php

namespace App\Livewire;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CourseCard extends Component
{
    public Course $course;
    public bool $isEnrolled = false;
    public float $progress = 0;

    public function mount(Course $course)
    {
        $this->course = $course;
        
        if (Auth::check()) {
            $this->isEnrolled = $course->isEnrolled(Auth::user());
            $this->progress = Auth::user()->getProgressForCourse($course);
        }
    }

    public function enroll()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->isEnrolled) {
            return;
        }

        $this->course->enrolledUsers()->attach(Auth::id(), [
            'enrolled_at' => now(),
        ]);

        $this->isEnrolled = true;
        $this->dispatch('enrolled', course: $this->course->id);
    }

    public function render()
    {
        return view('livewire.course-card');
    }
}
