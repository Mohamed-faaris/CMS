<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Module;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class AdminCourseDetail extends Component
{
    public $course;
    public $showModuleForm = false;
    public $editingModuleId = null;
    public $modulesWithLessons;

    // Module form properties
    public $moduleTitle = '';
    public $moduleDescription = '';
    public $moduleOrder = 1;

    protected $rules = [
        'moduleTitle' => 'required|string|max:255',
        'moduleDescription' => 'required|string|min:5',
        'moduleOrder' => 'required|integer|min:1',
    ];

    public function mount(Course $course)
    {
        $this->course = $course;
        $this->loadCourseData();
    }

    private function loadCourseData()
    {
        $this->course = $this->course->load('modules.lessons');
        $this->modulesWithLessons = $this->course->modules()->with('lessons')->orderBy('order')->get();
    }

    public function openModuleForm()
    {
        $this->resetModuleForm();
        $this->showModuleForm = true;
    }

    public function closeModuleForm()
    {
        $this->resetModuleForm();
    }

    public function resetModuleForm()
    {
        $this->reset(['moduleTitle', 'moduleDescription', 'moduleOrder', 'editingModuleId', 'showModuleForm']);
        $this->moduleOrder = $this->course->modules()->max('order') + 1 ?: 1;
    }

    public function saveModule()
    {
        $this->validate();

        if ($this->editingModuleId) {
            $module = Module::find($this->editingModuleId);
            $module->update([
                'title' => $this->moduleTitle,
                'description' => $this->moduleDescription,
                'order' => $this->moduleOrder,
            ]);
            session()->flash('success', 'Module updated successfully!');
        } else {
            Module::create([
                'course_id' => $this->course->id,
                'title' => $this->moduleTitle,
                'description' => $this->moduleDescription,
                'order' => $this->moduleOrder,
            ]);
            session()->flash('success', 'Module created successfully!');
        }

        $this->resetModuleForm();
        $this->loadCourseData();
    }

    public function editModule($moduleId)
    {
        $module = Module::find($moduleId);
        $this->editingModuleId = $moduleId;
        $this->moduleTitle = $module->title;
        $this->moduleDescription = $module->description;
        $this->moduleOrder = $module->order;
        $this->showModuleForm = true;
    }

    public function deleteModule($moduleId)
    {
        Module::find($moduleId)->delete();
        session()->flash('success', 'Module deleted successfully!');
        $this->loadCourseData();
    }

    public function render()
    {
        return view('livewire.admin-course-detail');
    }
}
