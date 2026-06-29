<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clean existing records first
        News::truncate();

        $newsData = [
            [
                'title'        => 'Beltway Office Park Raih Sertifikasi Green Building Internasional',
                'category'     => 'Achievement',
                'excerpt'      => 'Beltway Office Park berhasil meraih sertifikasi LEED Gold, menegaskan komitmen kami terhadap keberlanjutan lingkungan dan efisiensi energi.',
                'content'      => 'Beltway Office Park dengan bangga mengumumkan keberhasilan kami dalam memperoleh sertifikasi LEED (Leadership in Energy and Environmental Design) Gold dari Green Building Certification Institute. Penghargaan internasional ini menegaskan komitmen jangka panjang kami terhadap pembangunan berkelanjutan, pengurangan emisi karbon, dan efisiensi operasional energi di seluruh kawasan perkantoran kami.',
                'image'        => 'https://images.unsplash.com/photo-1624213012413-fda54df1810f?w=600&h=600&fit=crop&auto=format',
                'published_at' => Carbon::parse('2026-06-15 09:00:00'),
            ],
            [
                'title'        => 'Grand Opening Tower C: Era Baru Executive Office di Jakarta Selatan',
                'category'     => 'News',
                'excerpt'      => 'Tower C resmi dibuka dengan fasilitas executive office terlengkap, memberikan standar baru dalam dunia perkantoran premium Indonesia.',
                'content'      => 'Hari ini kami secara resmi membuka Tower C, gedung perkantoran mewah terbaru di kompleks Beltway Office Park. Tower C dirancang khusus untuk memenuhi standar eksekutif dengan menghadirkan premium business lounge, private meeting rooms dengan teknologi konferensi terkini, serta sistem keamanan biometrik tingkat tinggi guna mendukung produktivitas perusahaan berskala internasional.',
                'image'        => 'https://images.unsplash.com/photo-1686676104932-3d7b6bbaef52?w=600&h=420&fit=crop&auto=format',
                'published_at' => Carbon::parse('2026-05-28 10:00:00'),
            ],
            [
                'title'        => 'Smart Building Technology: Wujud Masa Depan Perkantoran Modern',
                'category'     => 'Technology',
                'excerpt'      => 'Beltway Office Park mengintegrasikan sistem IoT terbaru untuk menciptakan lingkungan kerja yang lebih cerdas, efisien, dan nyaman.',
                'content'      => 'Sebagai pionir dalam ruang kerja cerdas, Beltway Office Park telah berhasil mengintegrasikan sistem manajemen IoT (Internet of Things) yang mutakhir. Mulai dari sistem kontrol pencahayaan otomatis, pemantauan kualitas udara secara real-time, hingga pengaturan suhu ruangan pintar yang menyesuaikan dengan okupansi gedung untuk memastikan kenyamanan maksimal bagi seluruh tenant.',
                'image'        => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?w=800&h=600&fit=crop&auto=format',
                'published_at' => Carbon::parse('2026-04-10 08:30:00'),
            ],
            [
                'title'        => 'Peningkatan Fasilitas Parkir dan Keamanan Pintar di Beltway',
                'category'     => 'Facility',
                'excerpt'      => 'Kami memperkenalkan sistem pengenalan pelat nomor otomatis (ANPR) dan penambahan 200 lot parkir baru untuk kenyamanan tenant.',
                'content'      => 'Untuk meningkatkan kenyamanan dan keamanan bagi seluruh tenant dan pengunjung, kami meluncurkan teknologi Automatic Number Plate Recognition (ANPR) di gerbang masuk kawasan. Selain itu, kami juga telah merampungkan perluasan area parkir gedung dengan tambahan 200 slot baru, menjadikannya salah satu kawasan dengan kapasitas parkir terluas di Jakarta Selatan.',
                'image'        => 'https://images.unsplash.com/photo-1758448656987-cfae6bf225e4?w=800&h=600&fit=crop&auto=format',
                'published_at' => Carbon::parse('2026-03-22 13:15:00'),
            ],
            [
                'title'        => 'Kolaborasi Komunitas Bisnis Beltway: Networking Night 2026',
                'category'     => 'Community',
                'excerpt'      => 'Lebih dari 50 CEO dan eksekutif dari tenant Beltway berkumpul dalam acara Networking Night tahunan untuk menjalin kolaborasi strategis.',
                'content'      => 'Acara tahunan Networking Night Beltway Office Park kembali digelar dengan sukses di Business Lounge Tower A. Menghadirkan lebih dari 50 CEO, direktur, dan profesional dari berbagai industri, acara ini berfungsi sebagai wadah kolaborasi strategis antar-tenant demi mempererat ekosistem bisnis yang saling menguntungkan di lingkungan Beltway.',
                'image'        => 'https://images.unsplash.com/photo-1628630468464-4168a51129f1?w=800&h=600&fit=crop&auto=format',
                'published_at' => Carbon::parse('2026-02-15 19:00:00'),
            ],
            [
                'title'        => 'Tips Memilih Ruang Kantor yang Tepat untuk Startup Berkembang',
                'category'     => 'Tips',
                'excerpt'      => 'Panduan lengkap bagi pendiri startup dalam menentukan ukuran, fasilitas, dan lokasi kantor yang mendukung produktivitas tim.',
                'content'      => 'Memilih kantor pertama atau melakukan ekspansi adalah keputusan besar bagi startup yang sedang tumbuh. Dalam artikel panduan ini, tim property management kami merangkum beberapa tips krusial mulai dari analisis kebutuhan luas ruangan per karyawan, pentingnya fleksibilitas kontrak, hingga pemilihan lokasi strategis yang memudahkan akses bagi klien maupun talenta terbaik Anda.',
                'image'        => 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?w=800&h=600&fit=crop&auto=format',
                'published_at' => Carbon::parse('2026-01-05 11:00:00'),
            ],
        ];

        foreach ($newsData as $data) {
            News::create($data);
        }
    }
}
