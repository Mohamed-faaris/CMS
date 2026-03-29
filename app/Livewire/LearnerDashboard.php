<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class LearnerDashboard extends Component
{
    public $enrolledCourses;
    public $recentProgress;
    public $stats;

    public function mount()
    {
        $user = Auth::user();
        
        // Get enrolled courses with progress
        $this->enrolledCourses = Course::whereHas('enrollments', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['modules.lessons', 'enrollments' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->withCount(['lessons', 'modules'])
        ->get()
        ->map(function ($course) use ($user) {
            $totalLessons = $course->lessons_count;
            $completedLessons = LessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_id', $course->modules->flatMap->lessons->pluck('id'))
                ->where('completed', true)
                ->count();
            
            $course->progress_percentage = $totalLessons > 0 
                ? round(($completedLessons / $totalLessons) * 100) 
                : 0;
            
            return $course;
        });

        // Get recent progress
        $this->recentProgress = LessonProgress::where('user_id', $user->id)
            ->with(['lesson.module.course'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Calculate stats
        $this->stats = [
            'total_courses' => $this->enrolledCourses->count(),
            'completed_courses' => $this->enrolledCourses->where('progress_percentage', 100)->count(),
            'total_lessons_completed' => LessonProgress::where('user_id', $user->id)
                ->where('completed', true)
                ->count(),
            'total_hours_learned' => $this->calculateTotalHours($user->id),
        ];
    }

    private function calculateTotalHours($userId)
    {
        $completedLessons = LessonProgress::where('user_id', $userId)
            ->where('completed', true)
            ->with('lesson')
            ->get();

        $totalMinutes = $completedLessons->sum(function ($progress) {
            return $progress->lesson->duration ?? 0;
        });

        return round($totalMinutes / 60, 1);
    }

    public function render()
    {
        return view('livewire.learner-dashboard');
    }
}
