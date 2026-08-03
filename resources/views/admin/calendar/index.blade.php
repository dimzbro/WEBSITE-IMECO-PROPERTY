@extends('layouts.admin')

@section('title', 'Kalender Kegiatan – Beltway Office Park')
@section('breadcrumb', 'Kalender')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        <!-- Month Navigation Dropdowns -->
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-3">
                <!-- Prev Button (AJAX) -->
                <button id="prev-month-btn" type="button"
                   class="p-2 hover:bg-slate-100 rounded-xl text-slate-650 transition-colors flex items-center justify-center cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <!-- Month Dropdown Selector -->
                <select id="month-select" class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none font-extrabold text-slate-800 text-sm shadow-sm cursor-pointer focus:border-[#1E3A8A]">
                    @foreach([
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ] as $mVal => $mName)
                        <option value="{{ $mVal }}" {{ $month == $mVal ? 'selected' : '' }}>{{ $mName }}</option>
                    @endforeach
                </select>

                <!-- Year Dropdown Selector -->
                <select id="year-select" class="px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white outline-none font-extrabold text-slate-800 text-sm shadow-sm cursor-pointer focus:border-[#1E3A8A]">
                    @for($yVal = 2020; $yVal <= 2040; $yVal++)
                        <option value="{{ $yVal }}" {{ $year == $yVal ? 'selected' : '' }}>{{ $yVal }}</option>
                    @endfor
                </select>

                <!-- Next Button (AJAX) -->
                <button id="next-month-btn" type="button"
                   class="p-2 hover:bg-slate-100 rounded-xl text-slate-650 transition-colors flex items-center justify-center cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Legend Tags -->
        <div class="flex flex-wrap items-center gap-3 text-xs font-bold text-slate-500">
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                Meeting
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                Inspeksi / Masuk
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                Maintenance / Lease
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                Acara
            </span>
        </div>
    </div>

    <!-- Main Calendar Columns Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <!-- Column: Calendar Grid (Occupies 3/4) -->
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col relative">
            
            <!-- Loading Indicator Overlay -->
            <div id="calendar-loading" class="absolute inset-0 bg-white/70 backdrop-blur-[1px] z-20 flex flex-col items-center justify-center opacity-0 pointer-events-none transition-opacity duration-200">
                <div class="flex flex-col items-center gap-3">
                    <svg class="animate-spin h-8 w-8 text-[#1E3A8A]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-bold text-slate-500">Memuat Jadwal...</span>
                </div>
            </div>

            <!-- Day of Week Headers (Monday to Sunday start!) -->
            <div class="grid grid-cols-7 border-b border-slate-100 bg-slate-50/50">
                @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $dayHeader)
                    <div class="py-3 text-center text-xs font-extrabold text-slate-450 uppercase tracking-wide">
                        {{ $dayHeader }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Days Cells Grid -->
            <div class="grid grid-cols-7 bg-slate-100 gap-px" id="calendar-grid-cells">
                @include('admin.calendar.partials.grid')
            </div>
        </div>

        <!-- Column: Sidebar Agenda Info (Occupies 1/4) -->
        <div class="space-y-6">
            <!-- Sidebar Card: Select Date Info Panel -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-1.5">Pilih tanggal</h3>
                    <p class="text-sm font-semibold text-slate-700" id="selected-date-label">Klik tanggal untuk melihat jadwal</p>
                </div>
            </div>

            <!-- Sidebar Card: Events List Agenda -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider pb-2 border-b border-slate-100" id="agenda-title">
                    Semua Jadwal {{ $monthName }}
                </h3>

                <div class="space-y-3.5 max-h-[380px] overflow-y-auto pr-1" id="agenda-list-container">
                    @include('admin.calendar.partials.agenda')
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Modal: Event Detail -->
<div id="event-detail-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all duration-200 scale-95 opacity-0" id="event-detail-card">
        <!-- Header color-coded by event type -->
        <div id="event-modal-header" class="p-5 text-white flex items-center justify-between">
            <div>
                <span id="event-modal-type-badge" class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-white/20"></span>
                <h3 class="text-base font-extrabold mt-1" id="event-modal-title"></h3>
            </div>
            <button onclick="closeEventDetailModal()" class="text-white/70 hover:text-white p-1 rounded-lg cursor-pointer font-black text-sm">✕</button>
        </div>

        <!-- Detail content -->
        <div class="p-6 space-y-4 text-xs font-bold text-slate-650">
            <div class="space-y-3 font-semibold text-slate-650">
                <div>
                    <span class="text-slate-400 block text-[9px] uppercase tracking-wider">Detail Lokasi / Informasi</span>
                    <span class="text-slate-800 font-extrabold text-xs mt-0.5 block" id="event-modal-location"></span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[9px] uppercase tracking-wider">Waktu Pelaksanaan</span>
                    <span class="text-slate-800 font-extrabold text-xs mt-0.5 block" id="event-modal-date"></span>
                </div>
            </div>

            <div id="custom-event-actions" class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2 hidden">
                <form id="form-delete-event" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="py-2 px-3 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl transition-colors">
                        Hapus Agenda
                    </button>
                </form>
            </div>
            
            <button onclick="closeEventDetailModal()" class="w-full py-2.5 text-center bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-colors cursor-pointer mt-1">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const monthSelect = document.getElementById('month-select');
    const yearSelect = document.getElementById('year-select');
    const prevBtn = document.getElementById('prev-month-btn');
    const nextBtn = document.getElementById('next-month-btn');
    const calendarGridCells = document.getElementById('calendar-grid-cells');
    const agendaListContainer = document.getElementById('agenda-list-container');
    const agendaTitle = document.getElementById('agenda-title');
    const calendarLoading = document.getElementById('calendar-loading');

    let currentMonth = parseInt(monthSelect.value);
    let currentYear = parseInt(yearSelect.value);

    // Color definitions for event modal header
    const typeHeaderColors = {
        'Masuk': 'bg-emerald-600',
        'Renewal': 'bg-amber-600',
        'Meeting': 'bg-indigo-600',
        'Inspeksi': 'bg-emerald-600',
        'Maintenance': 'bg-amber-600',
        'Acara': 'bg-purple-600',
    };

    function openEventDetailModal(data) {
        const modal = document.getElementById('event-detail-modal');
        const card = document.getElementById('event-detail-card');
        const header = document.getElementById('event-modal-header');
        const badge = document.getElementById('event-modal-type-badge');
        const title = document.getElementById('event-modal-title');
        const location = document.getElementById('event-modal-location');
        const date = document.getElementById('event-modal-date');
        const customActions = document.getElementById('custom-event-actions');
        const formDelete = document.getElementById('form-delete-event');

        // Apply color
        const bgClass = typeHeaderColors[data.type] || 'bg-slate-800';
        header.className = `p-5 text-white flex items-center justify-between ${bgClass}`;

        badge.textContent = data.type || 'Agenda';
        title.textContent = data.title || '-';
        location.textContent = data.detail || '-';
        date.textContent = data.date || '-';

        if (data.isCustom === '1' && data.id) {
            customActions.classList.remove('hidden');
            formDelete.action = `{{ url('admin/calendar/events') }}/${data.id}`;
        } else {
            customActions.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    window.closeEventDetailModal = function() {
        const modal = document.getElementById('event-detail-modal');
        const card = document.getElementById('event-detail-card');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    };

    function attachEventHandlers() {
        document.querySelectorAll('.event-pill, .agenda-item').forEach(el => {
            el.addEventListener('click', function(e) {
                e.stopPropagation();
                openEventDetailModal({
                    id: this.getAttribute('data-id'),
                    isCustom: this.getAttribute('data-is-custom'),
                    type: this.getAttribute('data-type'),
                    title: this.getAttribute('data-title'),
                    detail: this.getAttribute('data-detail'),
                    date: this.getAttribute('data-date'),
                    eventDate: this.getAttribute('data-event-date'),
                    reminder: this.getAttribute('data-reminder'),
                    location: this.getAttribute('data-location'),
                    notes: this.getAttribute('data-notes')
                });
            });
        });
    }

    attachEventHandlers();

    // Fetch calendar data from backend via AJAX
    function loadCalendarData(month, year) {
        calendarLoading.classList.remove('opacity-0', 'pointer-events-none');
        calendarLoading.classList.add('opacity-100');

        const url = `{{ route('admin.calendar.index') }}?month=${month}&year=${year}&ajax=1`;
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.json())
            .then(data => {
                calendarGridCells.innerHTML = data.grid_html;
                agendaListContainer.innerHTML = data.agenda_html;
                agendaTitle.textContent = `Semua Jadwal ${data.monthName}`;
                
                attachEventHandlers();

                calendarLoading.classList.remove('opacity-100');
                calendarLoading.classList.add('opacity-0', 'pointer-events-none');
            })
            .catch(err => {
                console.error(err);
                calendarLoading.classList.remove('opacity-100');
                calendarLoading.classList.add('opacity-0', 'pointer-events-none');
            });
    }

    monthSelect.addEventListener('change', function() {
        currentMonth = parseInt(this.value);
        loadCalendarData(currentMonth, currentYear);
    });

    yearSelect.addEventListener('change', function() {
        currentYear = parseInt(this.value);
        loadCalendarData(currentMonth, currentYear);
    });

    prevBtn.addEventListener('click', function() {
        currentMonth--;
        if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        monthSelect.value = currentMonth;
        yearSelect.value = currentYear;
        loadCalendarData(currentMonth, currentYear);
    });

    nextBtn.addEventListener('click', function() {
        currentMonth++;
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        }
        monthSelect.value = currentMonth;
        yearSelect.value = currentYear;
        loadCalendarData(currentMonth, currentYear);
    });
});
</script>
@endsection
