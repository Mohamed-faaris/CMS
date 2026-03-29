<?php

namespace App\Livewire;

use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LessonDetail extends Component
{
    public Lesson $lesson;
    public Course $course;

    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson->load(['module.course']);
        $this->course = $this->lesson->module->course;
    }

    public function render()
    {
        return view('livewire.lesson-detail');
    }
}
