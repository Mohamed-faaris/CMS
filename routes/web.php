<?php

use App\Livewire\CourseList;
use App\Livewire\CourseDetail;
use App\Livewire\LessonDetail;
use App\Livewire\Dashboard;
use App\Livewire\AdminDashboard;
use App\Livewire\AdminCourseDetail;
use App\Livewire\LearnerDashboard;
use App\Livewire\CourseManagement;
use App\Livewire\BrowseCourses;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Main dashboard - redirects based on role
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    
    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/courses', CourseManagement::class)->name('courses');
        Route::get('/courses/{course}', AdminCourseDetail::class)->name('course-detail');
    });
    
    // Learner routes
    Route::middleware('role:learner')->prefix('learner')->name('learner.')->group(function () {
        Route::get('/dashboard', LearnerDashboard::class)->name('dashboard');
        Route::get('/browse-courses', BrowseCourses::class)->name('browse-courses');
        Route::get('/courses/{course}', CourseDetail::class)->name('courses.detail');
        Route::get('/lessons/{lesson}', LessonDetail::class)->name('lessons.detail');
    });
    
    // Legacy routes (keep for backward compatibility)
    Route::get('/courses', CourseList::class)->name('courses');
    Route::get('/courses/{course}', CourseDetail::class)->name('courses.detail');
    Route::get('/lessons/{lesson}', LessonDetail::class)->name('lessons.detail');
});
