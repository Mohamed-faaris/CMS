<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create learner users
        $learner1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $learner1->assignRole('learner');

        $learner2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $learner2->assignRole('learner');

        // Create courses
        $courses = [
            [
                'title' => 'Introduction to Laravel',
                'description' => 'Learn the basics of Laravel framework, including routing, controllers, models, and views. This course is perfect for beginners who want to start building web applications with Laravel.',
                'instructor' => 'Sarah Johnson',
                'duration' => '8 hours',
                'category' => 'Web Development',
                'is_published' => true,
                'modules' => [
                    [
                        'title' => 'Getting Started with Laravel',
                        'description' => 'Learn how to install and set up Laravel',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'What is Laravel?', 'type' => 'video', 'duration' => 15, 'order' => 1, 'content' => 'Laravel is a PHP framework for web development.'],
                            ['title' => 'Installation Guide', 'type' => 'video', 'duration' => 20, 'order' => 2, 'content' => 'Step by step installation guide.'],
                            ['title' => 'Project Structure', 'type' => 'text', 'duration' => 10, 'order' => 3, 'content' => 'Understanding the Laravel project structure.'],
                        ]
                    ],
                    [
                        'title' => 'Routing and Controllers',
                        'description' => 'Learn about routing and controllers in Laravel',
                        'order' => 2,
                        'lessons' => [
                            ['title' => 'Basic Routing', 'type' => 'video', 'duration' => 25, 'order' => 1, 'content' => 'How to define routes in Laravel.'],
                            ['title' => 'Controller Basics', 'type' => 'video', 'duration' => 30, 'order' => 2, 'content' => 'Creating and using controllers.'],
                            ['title' => 'Route Parameters', 'type' => 'text', 'duration' => 15, 'order' => 3, 'content' => 'Working with route parameters.'],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Advanced PHP Programming',
                'description' => 'Master advanced PHP concepts including OOP, design patterns, and best practices. Take your PHP skills to the next level.',
                'instructor' => 'Michael Chen',
                'duration' => '12 hours',
                'category' => 'Programming',
                'is_published' => true,
                'modules' => [
                    [
                        'title' => 'Object-Oriented Programming',
                        'description' => 'Deep dive into OOP concepts',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'Classes and Objects', 'type' => 'video', 'duration' => 30, 'order' => 1, 'content' => 'Understanding classes and objects in PHP.'],
                            ['title' => 'Inheritance', 'type' => 'video', 'duration' => 25, 'order' => 2, 'content' => 'How to use inheritance in PHP.'],
                            ['title' => 'Polymorphism', 'type' => 'text', 'duration' => 20, 'order' => 3, 'content' => 'Understanding polymorphism.'],
                        ]
                    ],
                    [
                        'title' => 'Design Patterns',
                        'description' => 'Learn common design patterns',
                        'order' => 2,
                        'lessons' => [
                            ['title' => 'Singleton Pattern', 'type' => 'video', 'duration' => 20, 'order' => 1, 'content' => 'Implementing the singleton pattern.'],
                            ['title' => 'Factory Pattern', 'type' => 'video', 'duration' => 25, 'order' => 2, 'content' => 'Understanding the factory pattern.'],
                            ['title' => 'Observer Pattern', 'type' => 'text', 'duration' => 15, 'order' => 3, 'content' => 'How to use the observer pattern.'],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Database Design Fundamentals',
                'description' => 'Learn how to design efficient and scalable databases. Covers normalization, indexing, and query optimization.',
                'instructor' => 'Emily Davis',
                'duration' => '6 hours',
                'category' => 'Database',
                'is_published' => true,
                'modules' => [
                    [
                        'title' => 'Database Basics',
                        'description' => 'Introduction to databases',
                        'order' => 1,
                        'lessons' => [
                            ['title' => 'What is a Database?', 'type' => 'video', 'duration' => 15, 'order' => 1, 'content' => 'Introduction to databases.'],
                            ['title' => 'SQL Basics', 'type' => 'video', 'duration' => 25, 'order' => 2, 'content' => 'Learning SQL fundamentals.'],
                            ['title' => 'Database Types', 'type' => 'text', 'duration' => 10, 'order' => 3, 'content' => 'Different types of databases.'],
                        ]
                    ],
                ]
            ],
        ];

        foreach ($courses as $courseData) {
            $modules = $courseData['modules'];
            unset($courseData['modules']);

            $course = Course::create($courseData);

            foreach ($modules as $moduleData) {
                $lessons = $moduleData['lessons'];
                unset($moduleData['lessons']);

                $module = Module::create(array_merge($moduleData, ['course_id' => $course->id]));

                foreach ($lessons as $lessonData) {
                    Lesson::create(array_merge($lessonData, ['module_id' => $module->id]));
                }
            }
        }
    }
}
