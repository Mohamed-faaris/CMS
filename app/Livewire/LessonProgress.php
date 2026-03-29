<?php

namespace App\Livewire;

use App\Models\Lesson;
use App\Models\LessonProgress as LessonProgressModel;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LessonProgress extends Component
{
    public Lesson $lesson;
    public bool $isCompleted = false;

    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->checkCompletion();
    }

    public function checkCompletion()
    {
        if (Auth::check()) {
            $this->isCompleted = LessonProgressModel::where('user_id', Auth::id())
                ->where('lesson_id', $this->lesson->id)
                ->where('is_completed', true)
                ->exists();
        }
    }

    public function toggleComplete()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $progress = LessonProgressModel::where('user_id', Auth::id())
            ->where('lesson_id', $this->lesson->id)
            ->first();

        if ($progress) {
            $progress->update([
                'is_completed' => !$progress->is_completed,
                'completed_at' => !$progress->is_completed ? now() : null,
            ]);
        } else {
            LessonProgressModel::create([
                'user_id' => Auth::id(),
                'lesson_id' => $this->lesson->id,
                'is_completed' => true,
                'completed_at' => now(),
            ]);
        }

        $this->checkCompletion();
    }

    public function render()
    {
        return view('livewire.lesson-progress');
    }
}
