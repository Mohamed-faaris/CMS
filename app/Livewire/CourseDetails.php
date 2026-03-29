<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CourseDetails extends Component
{
    public Course $course;
    public $isEnrolled = false;
    public $progress = 0;
    public $completedLessons = [];
    public $currentLesson = null;

    public function mount(Course $course)
    {
        $this->course = $course->load(['modules.lessons']);
        
        if (Auth::check()) {
            $this->checkEnrollment();
            $this->loadProgress();
        }
    }

    private function checkEnrollment()
    {
        $enrollment = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $this->course->id)
            ->first();
        
        $this->isEnrolled = $enrollment !== null;
    }

    private function loadProgress()
    {
        if (!$this->isEnrolled) {
            return;
        }

        $lessonIds = $this->course->modules->flatMap->lessons->pluck('id');
        
        $this->completedLessons = LessonProgress::where('user_id', Auth::id())
            ->whereIn('lesson_id', $lessonIds)
            ->where('completed', true)
            ->pluck('lesson_id')
            ->toArray();

        $totalLessons = $lessonIds->count();
        $completedCount = count($this->completedLessons);
        
        $this->progress = $totalLessons > 0 
            ? round(($completedCount / $totalLessons) * 100) 
            : 0;

        // Find the first incomplete lesson
        $this->currentLesson = $this->course->modules->flatMap->lessons->first(function ($lesson) {
            return !in_array($lesson->id, $this->completedLessons);
        });
    }

    public function enroll()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->isEnrolled) {
            return;
        }

        Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $this->course->id,
            'enrolled_at' => now(),
        ]);

        $this->isEnrolled = true;
        $this->loadProgress();

        $this->dispatch('enrolled', message: 'Successfully enrolled in the course!');
    }

    public function markLessonComplete($lessonId)
    {
        if (!Auth::check() || !$this->isEnrolled) {
            return;
        }

        LessonProgress::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'lesson_id' => $lessonId,
            ],
            [
                'completed' => true,
                'completed_at' => now(),
            ]
        );

        $this->loadProgress();

        $this->dispatch('lesson-completed', message: 'Lesson marked as complete!');
    }

    public function render()
    {
        return view('livewire.course-details');
    }
}
