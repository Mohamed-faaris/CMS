<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\LessonProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Create learner users if not exists
        $learner1 = User::firstOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $learner1->assignRole('learner');

        $learner2 = User::firstOrCreate(
            ['email' => 'jane@example.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $learner2->assignRole('learner');

        // Create courses
        $courses = [
            [
                'title' => 'Introduction to Laravel',
                'description' => 'Learn the basics of Laravel framework, including routing, controllers, models, and views.',
                'instructor' => 'Sarah Johnson',
                'duration' => '8 hours',
                'is_published' => true,
                'modules' => [
                    [
                        'title' => 'Getting Started',
                        'description' => 'Learn how to install and set up Laravel',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'What is Laravel?', 'type' => 'video', 'content' => 'Laravel is a PHP framework for web artisans.', 'duration' => 15, 'order' => 1],
                            ['title' => 'Installation Guide', 'type' => 'video', 'content' => 'Step by step installation guide.', 'duration' => 20, 'order' => 2],
                            ['title' => 'Project Structure', 'type' => 'text', 'content' => 'Understanding the Laravel project structure.', 'duration' => 10, 'order' => 3],
                        ]
                    ],
                    [
                        'title' => 'Routing and Controllers',
                        'description' => 'Learn about routing and controllers in Laravel',
                        'order' => 2,
                        'lessons' => [
                            ['title' => 'Basic Routing', 'type' => 'video', 'content' => 'How to define routes in Laravel.', 'duration' => 25, 'order' => 1],
                            ['title' => 'Controller Basics', 'type' => 'video', 'content' => 'Creating and using controllers.', 'duration' => 30, 'order' => 2],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'PHP for Beginners',
                'description' => 'Start your journey with PHP programming language. Perfect for complete beginners.',
                'instructor' => 'Mike Chen',
                'duration' => '10 hours',
                'is_published' => true,
                'modules' => [
                    [
                        'title' => 'PHP Basics',
                        'description' => 'Learn the fundamentals of PHP',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'Variables and Data Types', 'type' => 'video', 'content' => 'Understanding PHP variables.', 'duration' => 20, 'order' => 1],
                            ['title' => 'Control Structures', 'type' => 'video', 'content' => 'If statements and loops.', 'duration' => 25, 'order' => 2],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Advanced JavaScript',
                'description' => 'Master advanced JavaScript concepts including ES6+, async/await, and design patterns.',
                'instructor' => 'Emily Davis',
                'duration' => '12 hours',
                'is_published' => true,
                'modules' => [
                    [
                        'title' => 'ES6 Features',
                        'description' => 'Modern JavaScript features',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'Arrow Functions', 'type' => 'video', 'content' => 'Understanding arrow functions.', 'duration' => 15, 'order' => 1],
                            ['title' => 'Destructuring', 'type' => 'video', 'content' => 'Object and array destructuring.', 'duration' => 20, 'order' => 2],
                        ]
                    ],
                ]
            ],
        ];

        foreach ($courses as $courseData) {
            $modules = $courseData['modules'];
            unset($courseData['modules']);

            $course = Course::firstOrCreate(
                ['title' => $courseData['title']],
                $courseData
            );

            foreach ($modules as $moduleData) {
                $lessons = $moduleData['lessons'];
                unset($moduleData['lessons']);

                $module = Module::firstOrCreate(
                    ['title' => $moduleData['title'], 'course_id' => $course->id],
                    array_merge($moduleData, ['course_id' => $course->id])
                );

                foreach ($lessons as $lessonData) {
                    Lesson::firstOrCreate(
                        ['title' => $lessonData['title'], 'module_id' => $module->id],
                        array_merge($lessonData, ['module_id' => $module->id])
                    );
                }
            }
        }

        // Enroll learners in courses
        $course1 = Course::where('title', 'Introduction to Laravel')->first();
        $course2 = Course::where('title', 'PHP for Beginners')->first();

        if ($course1 && $course2) {
            Enrollment::firstOrCreate(
                ['user_id' => $learner1->id, 'course_id' => $course1->id],
                ['enrolled_at' => now()]
            );

            Enrollment::firstOrCreate(
                ['user_id' => $learner1->id, 'course_id' => $course2->id],
                ['enrolled_at' => now()->subDays(5)]
            );

            Enrollment::firstOrCreate(
                ['user_id' => $learner2->id, 'course_id' => $course1->id],
                ['enrolled_at' => now()->subDays(3)]
            );

            // Add some progress for learner1
            $lesson1 = Lesson::where('title', 'What is Laravel?')->first();
            $lesson2 = Lesson::where('title', 'Installation Guide')->first();

            if ($lesson1) {
                LessonProgress::firstOrCreate(
                    ['user_id' => $learner1->id, 'lesson_id' => $lesson1->id],
                    ['is_completed' => true, 'completed_at' => now()->subDays(2)]
                );
            }

            if ($lesson2) {
                LessonProgress::firstOrCreate(
                    ['user_id' => $learner1->id, 'lesson_id' => $lesson2->id],
                    ['is_completed' => true, 'completed_at' => now()->subDay()]
                );
            }
        }
    }
}
