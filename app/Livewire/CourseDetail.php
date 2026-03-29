<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class CourseDetail extends Component
{
    public Course $course;

    public function mount(Course $course)
    {
        $this->course = $course->load(['modules.lessons']);
    }

    public function render()
    {
        return view('livewire.course-detail');
    }
}
