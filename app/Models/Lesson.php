<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'content',
        'type',
        'video_url',
        'duration',
        'order',
    ];

    protected $casts = [
        'duration' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Get the module that owns the lesson.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the course that the lesson belongs to through the module.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Get the users who have completed this lesson.
     */
    public function completedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lesson_progress')
            ->withPivot('is_completed', 'completed_at')
            ->withTimestamps();
    }

    /**
     * Get the progress records for this lesson.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    /**
     * Check if a user has completed this lesson.
     */
    public function isCompletedBy(User $user): bool
    {
        return $this->progress()
            ->where('user_id', $user->id)
            ->where('is_completed', true)
            ->exists();
    }

    /**
     * Get the course ID attribute through the module.
     */
    public function getCourseIdAttribute(): int
    {
        return $this->module->course_id;
    }
}
