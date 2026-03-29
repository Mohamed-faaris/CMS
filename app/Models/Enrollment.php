<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'enrolled_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
    ];

    /**
     * Get the user that owns the enrollment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course that the enrollment is for.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the progress percentage for the enrollment.
     */
    public function getProgressPercentageAttribute(): float
    {
        $totalLessons = $this->course->total_lessons;
        
        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = LessonProgress::where('user_id', $this->user_id)
            ->whereIn('lesson_id', $this->course->lessons->pluck('id'))
            ->where('is_completed', true)
            ->count();

        return round(($completedLessons / $totalLessons) * 100, 2);
    }

    /**
     * Get the last watched lesson for the enrollment.
     */
    public function getLastWatchedLessonAttribute(): ?Lesson
    {
        $lastProgress = LessonProgress::where('user_id', $this->user_id)
            ->whereIn('lesson_id', $this->course->lessons->pluck('id'))
            ->orderBy('updated_at', 'desc')
            ->first();

        return $lastProgress?->lesson;
    }
}
