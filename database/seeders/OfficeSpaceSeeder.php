<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfficeSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officeSpaces = [
            [
                'tower'    => 'Tower A',
                'floor'    => '3rd Floor',
                'sqm'      => '250 sqm',
                'price'    => 'IDR 185,000/sqm/mo',
                'status'   => 'Available',
                'image'    => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=700&h=460&fit=crop&auto=format',
                'filter'   => 'tower-a',
            ],
            [
                'tower'    => 'Tower A',
                'floor'    => '5th Floor',
                'sqm'      => '500 sqm',
                'price'    => 'IDR 185,000/sqm/mo',
                'status'   => 'Available',
                'image'    => 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?w=700&h=460&fit=crop&auto=format',
                'filter'   => 'tower-a',
            ],
            [
                'tower'    => 'Tower B',
                'floor'    => '6th Floor',
                'sqm'      => '1,200 sqm',
                'price'    => 'IDR 190,000/sqm/mo',
                'status'   => 'Available',
                'image'    => 'https://images.unsplash.com/photo-1628630468464-4168a51129f1?w=700&h=460&fit=crop&auto=format',
                'filter'   => 'tower-b',
            ],
            [
                'tower'    => 'Tower B',
                'floor'    => '2nd Floor',
                'sqm'      => '320 sqm',
                'price'    => 'IDR 190,000/sqm/mo',
                'status'   => 'Available',
                'image'    => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=700&h=460&fit=crop&auto=format',
                'filter'   => 'tower-b',
            ],
            [
                'tower'    => 'Tower C',
                'floor'    => '7th Floor',
                'sqm'      => '800 sqm',
                'price'    => 'IDR 200,000/sqm/mo',
                'status'   => 'Available',
                'image'    => 'https://images.unsplash.com/photo-1606836591695-4d58a73eba1e?w=700&h=460&fit=crop&auto=format',
                'filter'   => 'tower-c',
            ],
            [
                'tower'    => 'Tower C',
                'floor'    => '4th Floor',
                'sqm'      => '150 sqm',
                'price'    => 'IDR 200,000/sqm/mo',
                'status'   => 'Available',
                'image'    => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=700&h=460&fit=crop&auto=format',
                'filter'   => 'tower-c',
            ],
        ];

        foreach ($officeSpaces as $space) {
            \App\Models\OfficeSpace::create($space);
        }
    }
}
