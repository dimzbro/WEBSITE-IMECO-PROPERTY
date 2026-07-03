@extends('layouts.admin')

@section('title', 'Admin Dashboard – Beltway Office Park')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- Metric: Active Tenants -->
        <div class="p-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm flex items-start justify-between hover:shadow-md transition-shadow">
            <div class="space-y-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#1E3A8A]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $activeTenantsCount }}</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Total Tenant Aktif</p>
                </div>
                <p class="text-[11px] text-slate-500">dari {{ $totalUnits }} unit tersedia • {{ $occupancyRate }}% occupancy</p>
            </div>
            <div class="px-2.5 py-1 text-xs font-bold bg-emerald-50 text-emerald-600 rounded-full flex items-center gap-1">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.58 5.59L20 12l-8-8-8 8z"/>
                </svg>
                +5.7%
            </div>
        </div>

        <!-- Metric: Approaching End Contracts -->
        <div class="p-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm flex items-start justify-between hover:shadow-md transition-shadow">
            <div class="space-y-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $approachingEndCount }}</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Kontrak Akan Berakhir</p>
                </div>
                <p class="text-[11px] text-slate-500">dalam 90 hari ke depan • tindak lanjut segera</p>
            </div>
            <div class="px-2.5 py-1 text-xs font-bold bg-amber-50 text-amber-600 rounded-full flex items-center gap-1">
                +2
            </div>
        </div>

        <!-- Metric: Due Payments -->
        <div class="p-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm flex items-start justify-between hover:shadow-md transition-shadow">
            <div class="space-y-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $paymentDueCount }}</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Pembayaran Jatuh Tempo</p>
                </div>
                <p class="text-[11px] text-slate-500">total tagihan bulanan • {{ $overdueCount }} tertunggak</p>
            </div>
            <div class="px-2.5 py-1 text-xs font-bold bg-rose-50 text-rose-600 rounded-full flex items-center gap-1">
                Rp {{ number_format($overdueAmount / 1000000, 0, ',', '.') }}jt
            </div>
        </div>

    </div>

    <!-- Occupancy Progress Bars & Secondary Statistics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Occupancy breakdown per building (Dynamic Progress Bars) -->
        <div class="p-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Okupansi Gedung</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Persentase ruang disewa per gedung perkantoran</p>
                </div>
                <a href="{{ route('admin.buildings.index') }}" class="text-xs font-bold text-[#1E3A8A] hover:underline flex items-center gap-1">
                    Detail Map
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="space-y-5">
                @foreach($buildingStats as $stats)
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-700">{{ $stats['name'] }}</span>
                        <span class="text-slate-800">{{ $stats['occupancy_rate'] }}%</span>
                    </div>
                    <!-- Progress Bar container -->
                    <div class="w-full h-3 rounded-full bg-slate-100 overflow-hidden relative">
                        <div class="h-full rounded-full transition-all duration-500 ease-out {{ $stats['occupancy_rate'] > 75 ? 'bg-emerald-500' : ($stats['occupancy_rate'] > 40 ? 'bg-amber-500' : 'bg-rose-500') }}"
                             style="width: {{ $stats['occupancy_rate'] }}%"></div>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-slate-500">
                        <span>{{ $stats['occupied_units'] }} tenant aktif</span>
                        <span>{{ $stats['vacant_units'] }} unit kosong</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Metric Details / Revenue summaries -->
        <div class="p-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm space-y-6 flex flex-col justify-between">
            <div class="space-y-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Pendapatan Operasional</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Estimasi pemasukan bulanan berjalan</p>
                </div>
                
                <div class="py-4 border-y border-slate-100 flex flex-col items-center justify-center text-center">
                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider">Pendapatan Bulan Ini</span>
                    <h2 class="text-3xl font-black text-[#1E3A8A] mt-1.5">Rp {{ number_format($monthlyRevenue / 1000000, 0, ',', '.') }}jt</h2>
                    <span class="text-[10px] text-emerald-600 font-bold mt-1 bg-emerald-50 px-2 py-0.5 rounded-full">+12.3% dari bulan lalu</span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-2 text-center text-xs">
                <div>
                    <div class="font-extrabold text-slate-800">{{ $totalUnits }}</div>
                    <div class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5">Unit</div>
                </div>
                <div>
                    <div class="font-extrabold text-emerald-600">{{ $occupiedUnitsCount }}</div>
                    <div class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5">Terisi</div>
                </div>
                <div>
                    <div class="font-extrabold text-slate-500">{{ $totalUnits - $occupiedUnitsCount }}</div>
                    <div class="text-[10px] text-slate-400 font-semibold uppercase mt-0.5">Tersedia</div>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts & Upcoming Tasks -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Charts panel -->
        <div class="p-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm lg:col-span-2 space-y-6">
            <div>
                <h3 class="text-base font-extrabold text-slate-800">Analisis Pertumbuhan & Pendapatan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Statistik pertumbuhan tenant sepanjang 2024</p>
            </div>

            <!-- Canvas charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tenant Growth Line Chart -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wide">Tenant Growth</h4>
                    <div class="h-56 relative flex items-center justify-center">
                        <canvas id="tenantGrowthChart" class="w-full h-full"></canvas>
                    </div>
                </div>

                <!-- Pendapatan per Gedung Bar Chart -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wide">Pendapatan per Gedung</h4>
                    <div class="h-56 relative flex items-center justify-center">
                        <canvas id="revenueBuildingChart" class="w-full h-full"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Tugas Mendatang widget -->
        <div class="p-6 bg-white rounded-2xl border border-slate-200/80 shadow-sm space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Tugas Mendatang</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Item operasional perlu perhatian</p>
                </div>
                <span class="w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center text-[10px] font-bold">{{ count($upcomingTasks) }}</span>
            </div>

            <div class="space-y-3.5">
                @foreach($upcomingTasks as $task)
                <div class="p-3 bg-slate-50 rounded-xl flex gap-3 border border-slate-100">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 {{ $task['type'] === 'danger' ? 'bg-rose-100 text-rose-600' : ($task['type'] === 'warning' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600') }}">
                        @if($task['icon'] === 'file-text')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @elseif($task['icon'] === 'credit-card')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        @elseif($task['icon'] === 'phone')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        @endif
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="text-xs font-bold text-slate-800 truncate">{{ $task['title'] }}</h4>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">{{ $task['date'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<!-- Load ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Line Chart: Tenant Growth
        const ctxGrowth = document.getElementById('tenantGrowthChart').getContext('2d');
        new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyUtilization['labels']) !!},
                datasets: [{
                    label: 'Jumlah Tenant',
                    data: {!! json_encode($monthlyUtilization['growth']) !!},
                    borderColor: '#1E3A8A',
                    backgroundColor: 'rgba(30, 58, 138, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointBackgroundColor: '#1E3A8A',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Bar Chart: Revenue/Utilization per Building
        const ctxRevenue = document.getElementById('revenueBuildingChart').getContext('2d');
        new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyUtilization['labels']) !!},
                datasets: {!! json_encode($monthlyUtilization['datasets']) !!}
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { boxWidth: 10, font: { size: 10 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: {
                            callback: function(val) { return val + 'jt'; }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
