<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LessonPlayer extends Component
{
    public Course $course;
    public Lesson $lesson;
    public $isEnrolled = false;
    public $isCompleted = false;
    public $nextLesson = null;
    public $prevLesson = null;

    public function mount(Course $course, Lesson $lesson)
    {
        $this->course = $course->load(['modules.lessons']);
        $this->lesson = $lesson->load('module');
        
        if (Auth::check()) {
            $this->checkEnrollment();
            $this->checkCompletion();
            $this->findAdjacentLessons();
        }
    }

    private function checkEnrollment()
    {
        $enrollment = \App\Models\Enrollment::where('user_id', Auth::id())
            ->where('course_id', $this->course->id)
            ->first();
        
        $this->isEnrolled = $enrollment !== null;
    }

    private function checkCompletion()
    {
        if (!$this->isEnrolled) {
            return;
        }

        $progress = LessonProgress::where('user_id', Auth::id())
            ->where('lesson_id', $this->lesson->id)
            ->first();
        
        $this->isCompleted = $progress && $progress->completed;
    }

    private function findAdjacentLessons()
    {
        $allLessons = $this->course->modules->flatMap->lessons->sortBy('order');
        $currentIndex = $allLessons->search(function ($lesson) {
            return $lesson->id === $this->lesson->id;
        });

        if ($currentIndex !== false) {
            $this->prevLesson = $allLessons->get($currentIndex - 1);
            $this->nextLesson = $allLessons->get($currentIndex + 1);
        }
    }

    public function markComplete()
    {
        if (!Auth::check() || !$this->isEnrolled) {
            return;
        }

        LessonProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'lesson_id' => $this->lesson->id,
            ],
            [
                'completed' => true,
                'completed_at' => now(),
            ]
        );

        $this->isCompleted = true;

        $this->dispatch('lesson-completed', message: 'Lesson marked as complete!');
    }

    public function render()
    {
        return view('livewire.lesson-player');
    }
}
