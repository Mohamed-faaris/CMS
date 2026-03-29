<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class EnrollButton extends Component
{
    public Course $course;
    public bool $isEnrolled = false;

    public function mount(Course $course)
    {
        $this->course = $course;
        $this->checkEnrollment();
    }

    public function checkEnrollment()
    {
        if (Auth::check()) {
            $this->isEnrolled = Enrollment::where('user_id', Auth::id())
                ->where('course_id', $this->course->id)
                ->exists();
        }
    }

    public function enroll()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->isEnrolled) {
            $this->dispatch('enrollment-error', 'You are already enrolled in this course.');
            return;
        }

        Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $this->course->id,
            'enrolled_at' => now(),
        ]);

        $this->isEnrolled = true;
        $this->dispatch('enrollment-success', 'Successfully enrolled in the course!');
    }

    public function unenroll()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Enrollment::where('user_id', Auth::id())
            ->where('course_id', $this->course->id)
            ->delete();

        $this->isEnrolled = false;
        $this->dispatch('enrollment-success', 'Successfully unenrolled from the course.');
    }

    public function render()
    {
        return view('livewire.enroll-button');
    }
}
