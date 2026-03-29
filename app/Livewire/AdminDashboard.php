<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class AdminDashboard extends Component
{
    public $totalCourses;
    public $totalLearners;
    public $totalEnrollments;
    public $recentCourses;

    public function mount()
    {
        $this->loadStats();
    }

    private function loadStats()
    {
        $this->totalCourses = Course::count();
        $this->totalLearners = User::role('learner')->count();
        $this->totalEnrollments = \App\Models\Enrollment::count();
        $this->recentCourses = Course::with(['modules', 'enrolledUsers'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function refresh()
    {
        $this->loadStats();
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
