<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gallery = [
            [
                'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&h=600&fit=crop&auto=format',
                'title' => 'Grand Lobby',
                'span' => 'large'
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1497215842964-222b430dc094?w=800&h=600&fit=crop&auto=format',
                'title' => 'Meeting Room',
                'span' => 'normal'
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=800&h=600&fit=crop&auto=format',
                'title' => 'Office Interior',
                'span' => 'normal'
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1628630468464-4168a51129f1?w=800&h=600&fit=crop&auto=format',
                'title' => 'Business Lounge',
                'span' => 'normal'
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1606836591695-4d58a73eba1e?w=800&h=600&fit=crop&auto=format',
                'title' => 'Conference Hall',
                'span' => 'large'
            ],
            [
                'image' => 'https://images.unsplash.com/photo-1624213012413-fda54df1810f?w=600&h=600&fit=crop&auto=format',
                'title' => 'Building Exterior',
                'span' => 'normal'
            ],
        ];

        foreach ($gallery as $item) {
            \App\Models\Gallery::create($item);
        }
    }
}
