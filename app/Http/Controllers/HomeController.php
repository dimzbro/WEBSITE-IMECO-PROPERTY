<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;

class HomeController extends Controller
{
    public function index()
    {
        $images = [
            'heroBg'         => asset('bg-hero.jpg'),
            'aerial'         => asset('ls.JPG'),
            'towerA'         => asset('tower-a.jpg'),
            'towerB'         => asset('tower-b.jpg'),
            'towerC'         => asset('tower-c.jpg'),
            'lobby'          => 'https://images.unsplash.com/photo-1758448656987-cfae6bf225e4?w=800&h=600&fit=crop&auto=format',
            'meetingRoom'    => 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?w=800&h=600&fit=crop&auto=format',
            'officeInterior' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=800&h=600&fit=crop&auto=format',
            'businessLounge' => 'https://images.unsplash.com/photo-1628630468464-4168a51129f1?w=800&h=600&fit=crop&auto=format',
            'exterior2'      => 'https://images.unsplash.com/photo-1624213012413-fda54df1810f?w=600&h=600&fit=crop&auto=format',
            'cityView'       => asset('ls.JPG'),
            'conference'     => 'https://images.unsplash.com/photo-1606836591695-4d58a73eba1e?w=800&h=600&fit=crop&auto=format',
        ];

        $towers = [
            [
                'name'        => 'Tower A',
                'subtitle'    => 'Premium Office Space',
                'description' => 'Modern architecture with premium finishes. Tower A offers state-of-the-art facilities with panoramic city views, designed for prestigious companies seeking an elite corporate address.',
                'image'       => $images['towerA'],
                'floors'      => '8 Floors',
                'area'        => 'Up to 890 sqm/floor',
                'status'      => 'Available',
            ],
            [
                'name'        => 'Tower B',
                'subtitle'    => 'Flexible Workspace',
                'description' => 'Smart building technology meets flexible workspace solutions. Tower B is designed for dynamic businesses requiring adaptable spaces with cutting-edge smart building integration.',
                'image'       => $images['towerB'],
                'floors'      => '8 Floors',
                'area'        => 'Up to 1,200 sqm/floor',
                'status'      => 'Available',
            ],
            [
                'name'        => 'Tower C',
                'subtitle'    => 'Executive Office',
                'description' => "The pinnacle of executive office environments. Tower C's Business Center offers exclusive amenities and private executive suites for C-suite leaders and international companies.",
                'image'       => $images['towerC'],
                'floors'      => '8 Floors',
                'area'        => 'Up to 830 sqm/floor',
                'status'      => 'Limited',
            ],
        ];

        $features = [
            ['icon' => 'location', 'title' => 'Strategic Location', 'desc' => 'Prime South Jakarta location with easy access to toll roads, MRT, and major business districts.'],
            ['icon' => 'shield', 'title' => 'High Security', 'desc' => '24/7 CCTV surveillance, access card system, and trained security personnel for complete peace of mind.'],
            ['icon' => 'charging', 'title' => 'SPKLU', 'desc' => 'Electric vehicle charging stations (SPKLU) available within the park for sustainable transit.'],
            ['icon' => 'wifi', 'title' => 'High-Speed Internet', 'desc' => 'Dedicated fiber optic backbone ensuring 99.9% uptime with redundant connectivity options.'],
            ['icon' => 'car', 'title' => 'Large Parking', 'desc' => 'Expansive multi-level parking facility with a high ratio of spaces per floor area.'],
            ['icon' => 'briefcase', 'title' => 'Pro Management', 'desc' => 'International standard property management delivering seamless tenant experience.'],
            ['icon' => 'users', 'title' => 'Business Community', 'desc' => 'A thriving ecosystem of 100+ companies fostering networking and business collaboration.'],
            ['icon' => 'sports', 'title' => 'Sport', 'desc' => 'Equipped with volleyball and table tennis courts, plus active communities for football, badminton, volleyball, and padel.'],
        ];

        $facilities = [
            ['icon' => 'sofa', 'name' => 'Business Lounge', 'desc' => 'Premium networking space'],
            ['icon' => 'presentation', 'name' => 'Meeting Room', 'desc' => 'Fully-equipped rooms'],
            ['icon' => 'mic', 'name' => 'Conference Hall', 'desc' => 'Capacity up to 500 pax'],
            ['icon' => 'utensils', 'name' => 'Food Court', 'desc' => 'Multi-cuisine dining'],
            ['icon' => 'coffee', 'name' => 'Coffee Shop', 'desc' => 'Premium café experience'],
            ['icon' => 'credit-card', 'name' => 'ATM Center', 'desc' => 'Multi-bank ATM cluster'],
            ['icon' => 'parking', 'name' => 'Parking Area', 'desc' => 'Up to 700 slots'],
            ['icon' => 'shield-check', 'name' => '24/7 Security', 'desc' => 'Round-the-clock safety'],
            ['icon' => 'key', 'name' => 'Access Card', 'desc' => 'Biometric & RFID system'],
            ['icon' => 'clinic', 'name' => 'Klinik', 'desc' => 'Medical clinic & first-aid room'],
        ];

        $officeSpaces = \App\Models\OfficeSpace::all();
        $buildings = \App\Models\Building::all();

        $gallery = \App\Models\Gallery::all();

        $tenants = [
            ['name' => 'Circle K',                'logo' => 'ck.png'],
            ['name' => 'BNI',                     'logo' => 'bni.png'],
            ['name' => 'Bank Mandiri',            'logo' => 'mandiri.png'],
            ['name' => 'Kopi Kenangan',           'logo' => 'kopken.png'],
            ['name' => 'Kapal Api Coffee Corner', 'logo' => 'kapal api.jpg'],
        ];

        $news = News::orderBy('published_at', 'desc')->get();

        return view('home', compact('images', 'towers', 'features', 'facilities', 'officeSpaces', 'gallery', 'tenants', 'news', 'buildings'));
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
