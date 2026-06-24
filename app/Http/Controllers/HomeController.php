<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $images = [
            'heroBg'         => 'https://images.unsplash.com/photo-1690944851207-3f288c8fcd0b?w=1920&h=1080&fit=crop&auto=format',
            'aerial'         => 'https://images.unsplash.com/photo-1640184713828-83b0fb39242a?w=900&h=700&fit=crop&auto=format',
            'towerA'         => 'https://images.unsplash.com/photo-1637393933151-d37306ed606d?w=600&h=420&fit=crop&auto=format',
            'towerB'         => 'https://images.unsplash.com/photo-1707823942892-3316eeb091a0?w=600&h=420&fit=crop&auto=format',
            'towerC'         => 'https://images.unsplash.com/photo-1686676104932-3d7b6bbaef52?w=600&h=420&fit=crop&auto=format',
            'lobby'          => 'https://images.unsplash.com/photo-1758448656987-cfae6bf225e4?w=800&h=600&fit=crop&auto=format',
            'meetingRoom'    => 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?w=800&h=600&fit=crop&auto=format',
            'officeInterior' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=800&h=600&fit=crop&auto=format',
            'businessLounge' => 'https://images.unsplash.com/photo-1628630468464-4168a51129f1?w=800&h=600&fit=crop&auto=format',
            'exterior2'      => 'https://images.unsplash.com/photo-1624213012413-fda54df1810f?w=600&h=600&fit=crop&auto=format',
            'cityView'       => 'https://images.unsplash.com/photo-1640184713831-cb9be50b846e?w=1200&h=600&fit=crop&auto=format',
            'conference'     => 'https://images.unsplash.com/photo-1606836591695-4d58a73eba1e?w=800&h=600&fit=crop&auto=format',
        ];

        $towers = [
            [
                'name'        => 'Tower A',
                'subtitle'    => 'Premium Office Space',
                'description' => 'Modern architecture with premium finishes. Tower A offers state-of-the-art facilities with panoramic city views, designed for prestigious companies seeking an elite corporate address.',
                'image'       => $images['towerA'],
                'floors'      => '8 Floors',
                'area'        => 'Up to 500 sqm/floor',
                'status'      => 'Available',
            ],
            [
                'name'        => 'Tower B',
                'subtitle'    => 'Flexible Workspace',
                'description' => 'Smart building technology meets flexible workspace solutions. Tower B is designed for dynamic businesses requiring adaptable spaces with cutting-edge smart building integration.',
                'image'       => $images['towerB'],
                'floors'      => '8 Floors',
                'area'        => 'Up to 450 sqm/floor',
                'status'      => 'Available',
            ],
            [
                'name'        => 'Tower C',
                'subtitle'    => 'Executive Office',
                'description' => "The pinnacle of executive office environments. Tower C's Business Center offers exclusive amenities and private executive suites for C-suite leaders and international companies.",
                'image'       => $images['towerC'],
                'floors'      => '8 Floors',
                'area'        => 'Up to 400 sqm/floor',
                'status'      => 'Limited',
            ],
        ];

        $features = [
            ['icon' => 'location', 'title' => 'Strategic Location', 'desc' => 'Prime South Jakarta location with easy access to toll roads, MRT, and major business districts.'],
            ['icon' => 'shield', 'title' => 'High Security', 'desc' => '24/7 CCTV surveillance, access card system, and trained security personnel for complete peace of mind.'],
            ['icon' => 'chip', 'title' => 'Smart Building', 'desc' => 'IoT-integrated building management system with automated energy, climate, and lighting control.'],
            ['icon' => 'wifi', 'title' => 'High-Speed Internet', 'desc' => 'Dedicated fiber optic backbone ensuring 99.9% uptime with redundant connectivity options.'],
            ['icon' => 'car', 'title' => 'Large Parking', 'desc' => 'Expansive multi-level parking facility with a high ratio of spaces per floor area.'],
            ['icon' => 'briefcase', 'title' => 'Pro Management', 'desc' => 'International standard property management delivering seamless tenant experience.'],
            ['icon' => 'users', 'title' => 'Business Community', 'desc' => 'A thriving ecosystem of 100+ companies fostering networking and business collaboration.'],
            ['icon' => 'leaf', 'title' => 'Sustainable', 'desc' => 'Green building certified with energy-efficient systems and eco-friendly design practices.'],
        ];

        $facilities = [
            ['icon' => 'sofa', 'name' => 'Business Lounge', 'desc' => 'Premium networking space'],
            ['icon' => 'presentation', 'name' => 'Meeting Room', 'desc' => 'Fully-equipped rooms'],
            ['icon' => 'mic', 'name' => 'Conference Hall', 'desc' => 'Capacity up to 500 pax'],
            ['icon' => 'utensils', 'name' => 'Food Court', 'desc' => 'Multi-cuisine dining'],
            ['icon' => 'coffee', 'name' => 'Coffee Shop', 'desc' => 'Premium café experience'],
            ['icon' => 'credit-card', 'name' => 'ATM Center', 'desc' => 'Multi-bank ATM cluster'],
            ['icon' => 'parking', 'name' => 'Parking Area', 'desc' => 'Basement & rooftop'],
            ['icon' => 'shield-check', 'name' => '24/7 Security', 'desc' => 'Round-the-clock safety'],
            ['icon' => 'key', 'name' => 'Access Card', 'desc' => 'Biometric & RFID system'],
            ['icon' => 'settings', 'name' => 'BMS System', 'desc' => 'Intelligent management'],
        ];

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

        $gallery = [
            ['image' => $images['lobby'],          'title' => 'Grand Lobby',         'span' => 'large'],
            ['image' => $images['meetingRoom'],    'title' => 'Meeting Room',         'span' => 'normal'],
            ['image' => $images['officeInterior'], 'title' => 'Office Interior',      'span' => 'normal'],
            ['image' => $images['businessLounge'], 'title' => 'Business Lounge',      'span' => 'normal'],
            ['image' => $images['conference'],     'title' => 'Conference Hall',       'span' => 'large'],
            ['image' => $images['exterior2'],      'title' => 'Building Exterior',     'span' => 'normal'],
        ];

        $tenants = [
            ['name' => 'BNI',             'logo' => 'BNI'],
            ['name' => 'Telkom Indonesia','logo' => 'Telkom'],
            ['name' => 'Bank Mandiri',    'logo' => 'Mandiri'],
            ['name' => 'Circle K',        'logo' => 'Circle K'],
            ['name' => 'Kopi Kenangan',   'logo' => 'Kopi Kenangan'],
            ['name' => 'Indomaret',       'logo' => 'Indomaret'],
            ['name' => 'XL Axiata',       'logo' => 'XL Axiata'],
            ['name' => 'Gojek',           'logo' => 'Gojek'],
        ];

        $news = [
            [
                'title'     => 'Beltway Office Park Raih Sertifikasi Green Building Internasional',
                'date'      => 'June 15, 2026',
                'excerpt'   => 'Beltway Office Park berhasil meraih sertifikasi LEED Gold, menegaskan komitmen kami terhadap keberlanjutan lingkungan dan efisiensi energi.',
                'image'     => $images['exterior2'],
                'category'  => 'Achievement',
            ],
            [
                'title'     => 'Grand Opening Tower C: Era Baru Executive Office di Jakarta Selatan',
                'date'      => 'May 28, 2026',
                'excerpt'   => 'Tower C resmi dibuka dengan fasilitas executive office terlengkap, memberikan standar baru dalam dunia perkantoran premium Indonesia.',
                'image'     => $images['towerC'],
                'category'  => 'News',
            ],
            [
                'title'     => 'Smart Building Technology: Wujud Masa Depan Perkantoran Modern',
                'date'      => 'April 10, 2026',
                'excerpt'   => 'Beltway Office Park mengintegrasikan sistem IoT terbaru untuk menciptakan lingkungan kerja yang lebih cerdas, efisien, dan nyaman.',
                'image'     => $images['officeInterior'],
                'category'  => 'Technology',
            ],
            [
                'title'     => 'Peningkatan Fasilitas Parkir dan Keamanan Pintar di Beltway',
                'date'      => 'March 22, 2026',
                'excerpt'   => 'Kami memperkenalkan sistem pengenalan pelat nomor otomatis (ANPR) dan penambahan 200 lot parkir baru untuk kenyamanan tenant.',
                'image'     => $images['lobby'],
                'category'  => 'Facility',
            ],
            [
                'title'     => 'Kolaborasi Komunitas Bisnis Beltway: Networking Night 2026',
                'date'      => 'February 15, 2026',
                'excerpt'   => 'Lebih dari 50 CEO dan eksekutif dari tenant Beltway berkumpul dalam acara Networking Night tahunan untuk menjalin kolaborasi strategis.',
                'image'     => $images['businessLounge'],
                'category'  => 'Community',
            ],
            [
                'title'     => 'Tips Memilih Ruang Kantor yang Tepat untuk Startup Berkembang',
                'date'      => 'January 05, 2026',
                'excerpt'   => 'Panduan lengkap bagi pendiri startup dalam menentukan ukuran, fasilitas, dan lokasi kantor yang mendukung produktivitas tim.',
                'image'     => $images['meetingRoom'],
                'category'  => 'Tips',
            ],
        ];

        return view('home', compact('images', 'towers', 'features', 'facilities', 'officeSpaces', 'gallery', 'tenants', 'news'));
    }

    public function contact(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:30',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'name.required'    => 'Nama lengkap wajib diisi.',
            'email.required'   => 'Alamat email wajib diisi.',
            'email.email'      => 'Format email tidak valid.',
            'message.required' => 'Pesan wajib diisi.',
            'message.min'      => 'Pesan minimal 10 karakter.',
        ]);

        // In production, send email here via Mail facade
        // Mail::to('info@beltwayofficepark.co.id')->send(new ContactMail($validated));

        return redirect()->route('home', ['#contact'])->with('success', 'Terima kasih! Pesan Anda telah kami terima. Tim kami akan segera menghubungi Anda dalam 1x24 jam.');
    }
}
