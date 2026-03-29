<?php

namespace App\Livewire;

use App\Models\Lesson;
use App\Models\Module;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class AdminLessonManager extends Component
{
    public $module;
    public $moduleId;
    public $showLessonForm = false;
    public $editingLessonId = null;

    // Lesson form properties
    public $lessonTitle = '';
    public $lessonType = 'video'; // video, text, quiz
    public $lessonDuration = 0;
    public $lessonContent = '';
    public $lessonOrder = 1;

    protected $rules = [
        'lessonTitle' => 'required|string|max:255',
        'lessonType' => 'required|in:video,text,quiz',
        'lessonDuration' => 'required|integer|min:0',
        'lessonContent' => 'required|string|min:5',
        'lessonOrder' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->module = Module::with('lessons')->find($this->moduleId);
        if (!$this->module) {
            session()->flash('error', 'Module not found!');
        }
    }

    public function openLessonForm()
    {
        $this->resetLessonForm();
        $this->showLessonForm = true;
    }

    public function closeLessonForm()
    {
        $this->resetLessonForm();
    }

    public function resetLessonForm()
    {
        $this->reset(['lessonTitle', 'lessonType', 'lessonDuration', 'lessonContent', 'lessonOrder', 'editingLessonId', 'showLessonForm']);
        $this->lessonOrder = $this->module->lessons()->max('order') + 1 ?: 1;
        $this->lessonType = 'video';
    }

    public function saveLesson()
    {
        $this->validate();

        if ($this->editingLessonId) {
            $lesson = Lesson::find($this->editingLessonId);
            $lesson->update([
                'title' => $this->lessonTitle,
                'type' => $this->lessonType,
                'duration' => $this->lessonDuration,
                'content' => $this->lessonContent,
                'order' => $this->lessonOrder,
            ]);
            session()->flash('success', 'Lesson updated successfully!');
        } else {
            Lesson::create([
                'module_id' => $this->moduleId,
                'title' => $this->lessonTitle,
                'type' => $this->lessonType,
                'duration' => $this->lessonDuration,
                'content' => $this->lessonContent,
                'order' => $this->lessonOrder,
            ]);
            session()->flash('success', 'Lesson created successfully!');
        }

        $this->resetLessonForm();
        $this->module = $this->module->fresh(['lessons']);
    }

    public function editLesson($lessonId)
    {
        $lesson = Lesson::find($lessonId);
        $this->editingLessonId = $lessonId;
        $this->lessonTitle = $lesson->title;
        $this->lessonType = $lesson->type;
        $this->lessonDuration = $lesson->duration;
        $this->lessonContent = $lesson->content;
        $this->lessonOrder = $lesson->order;
        $this->showLessonForm = true;
    }

    public function deleteLesson($lessonId)
    {
        Lesson::find($lessonId)->delete();
        session()->flash('success', 'Lesson deleted successfully!');
        $this->module = $this->module->fresh(['lessons']);
    }

    public function render()
    {
        return view('livewire.admin-lesson-manager');
    }
}
