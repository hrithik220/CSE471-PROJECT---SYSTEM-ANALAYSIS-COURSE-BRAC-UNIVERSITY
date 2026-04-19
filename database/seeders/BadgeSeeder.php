<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            ['name' => 'New Member',      'slug' => 'new-member',       'description' => 'Welcome to the community!',              'icon' => '🌱', 'required_points' => 0,    'color' => '#4CAF50'],
            ['name' => 'Helper',          'slug' => 'helper',           'description' => 'Lent 5 items to the community.',          'icon' => '🤝', 'required_points' => 50,   'color' => '#2196F3'],
            ['name' => 'Community Star',  'slug' => 'community-star',   'description' => 'Earned 200 karma points.',                'icon' => '⭐', 'required_points' => 200,  'color' => '#FF9800'],
            ['name' => 'Super Lender',    'slug' => 'super-lender',     'description' => 'Lent 20+ items to the community.',        'icon' => '🦸', 'required_points' => 500,  'color' => '#9C27B0'],
            ['name' => 'Eco Warrior',     'slug' => 'eco-warrior',      'description' => 'Saved 50+ items from being purchased.',   'icon' => '🌍', 'required_points' => 1000, 'color' => '#009688'],
            ['name' => 'Top Contributor', 'slug' => 'top-contributor',  'description' => 'Ranked #1 on monthly leaderboard.',       'icon' => '🥇', 'required_points' => 2000, 'color' => '#FFD700'],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['slug' => $badge['slug']], $badge);
        }
    }
}
