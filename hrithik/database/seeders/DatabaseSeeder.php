<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Resource;
use App\Models\ResourceReview;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = [
            ['name' => 'Textbooks',     'slug' => 'textbooks',    'icon' => '📚'],
            ['name' => 'Notes',         'slug' => 'notes',        'icon' => '📝'],
            ['name' => 'Lab Equipment', 'slug' => 'lab-equipment','icon' => '🔬'],
            ['name' => 'Electronics',   'slug' => 'electronics',  'icon' => '💻'],
            ['name' => 'Stationery',    'slug' => 'stationery',   'icon' => '✏️'],
        ];
        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Users
        $fahim = User::create([
            'name' => 'Fahim Ahmed', 'email' => 'fahim@campus.com',
            'password' => Hash::make('password'), 'role' => 'student',
            'credibility_score' => 4.8, 'karma_points' => 75,
        ]);
        $samantha = User::create([
            'name' => 'Samantha', 'email' => 'samantha@campus.com',
            'password' => Hash::make('password'), 'role' => 'student',
            'credibility_score' => 4.5, 'karma_points' => 50,
        ]);
        $hrithik = User::create([
            'name' => 'Hrithik', 'email' => 'hrithik@campus.com',
            'password' => Hash::make('password'), 'role' => 'student',
            'credibility_score' => 4.2, 'karma_points' => 30,
        ]);
        User::create([
            'name' => 'Admin', 'email' => 'admin@campus.com',
            'password' => Hash::make('password'), 'role' => 'admin',
            'credibility_score' => 5.0, 'karma_points' => 100,
        ]);

        // Resources with pickup locations (BRACU campus area)
        $resources = [
            [
                'user_id' => $fahim->id, 'category_id' => 1,
                'title' => 'Data Structures & Algorithms - 3rd Edition',
                'description' => 'Excellent condition. Used for CSE220. Covers arrays, linked lists, trees, graphs. All pages intact.',
                'condition' => 'good', 'availability_status' => 'available',
                'sharing_type' => 'free', 'availability_until' => '2026-06-30',
                'is_approved' => true, 'view_count' => 45,
                'pickup_lat' => 23.7809, 'pickup_lng' => 90.4012,
                'pickup_address' => 'DSC Building, Ground Floor, BRACU',
            ],
            [
                'user_id' => $fahim->id, 'category_id' => 4,
                'title' => 'Arduino Uno R3 Kit',
                'description' => 'Complete kit with breadboard, jumper wires, LEDs. Perfect for CSE341 lab.',
                'condition' => 'new', 'availability_status' => 'available',
                'sharing_type' => 'exchange', 'availability_until' => '2026-05-15',
                'is_approved' => true, 'view_count' => 32,
                'pickup_lat' => 23.7815, 'pickup_lng' => 90.4020,
                'pickup_address' => 'Lab Building, Room 302, BRACU',
            ],
            [
                'user_id' => $samantha->id, 'category_id' => 2,
                'title' => 'CSE311 Logic Design Notes',
                'description' => 'Handwritten notes covering all chapters. Includes solved examples.',
                'condition' => 'good', 'availability_status' => 'available',
                'sharing_type' => 'free', 'availability_until' => '2026-04-30',
                'is_approved' => true, 'view_count' => 28,
                'pickup_lat' => 23.7800, 'pickup_lng' => 90.4005,
                'pickup_address' => 'Main Library, 2nd Floor, BRACU',
            ],
            [
                'user_id' => $samantha->id, 'category_id' => 3,
                'title' => 'Digital Multimeter - Fluke 87V',
                'description' => 'Professional grade multimeter in perfect working condition.',
                'condition' => 'fair', 'availability_status' => 'available',
                'sharing_type' => 'free', 'is_approved' => true, 'view_count' => 19,
                'pickup_lat' => 23.7820, 'pickup_lng' => 90.4030,
                'pickup_address' => 'ECE Lab, Room 101, BRACU',
            ],
            [
                'user_id' => $hrithik->id, 'category_id' => 1,
                'title' => 'Operating System Concepts - Silberschatz',
                'description' => 'Used for CSE321. Some highlights on chapters 1-5 but rest is clean.',
                'condition' => 'fair', 'availability_status' => 'available',
                'sharing_type' => 'exchange', 'is_approved' => true, 'view_count' => 15,
                'pickup_lat' => 23.7795, 'pickup_lng' => 90.3998,
                'pickup_address' => 'Student Cafeteria Lobby, BRACU',
            ],
        ];

        $created = [];
        foreach ($resources as $res) {
            $created[] = Resource::create($res);
        }

        // Reviews
        ResourceReview::create(['resource_id' => $created[0]->id, 'reviewer_id' => $samantha->id, 'rating' => 5, 'comment' => 'Fahim was very helpful! Book was in great condition.']);
        ResourceReview::create(['resource_id' => $created[0]->id, 'reviewer_id' => $hrithik->id, 'rating' => 4, 'comment' => 'Good book, returned on time. Would borrow again.']);
        ResourceReview::create(['resource_id' => $created[2]->id, 'reviewer_id' => $fahim->id, 'rating' => 5, 'comment' => 'Notes were incredibly detailed. Samantha is a star!']);
    }
}
