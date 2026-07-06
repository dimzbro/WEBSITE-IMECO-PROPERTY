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
        <div class="flex flex-wrap items-center gap-3.5 text-xs font-bold text-slate-500">
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                Tenant Masuk
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                Lease Berakhir
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                Maintenance
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

<!-- Modal: Event Detail -->
<div id="event-detail-modal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all duration-200 scale-95 opacity-0" id="event-detail-card">
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
            
            <button onclick="closeEventDetailModal()" class="w-full py-2.5 text-center bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-xs transition-colors cursor-pointer mt-2">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthSelect = document.getElementById('month-select');
        const yearSelect = document.getElementById('year-select');
        const prevMonthBtn = document.getElementById('prev-month-btn');
        const nextMonthBtn = document.getElementById('next-month-btn');
        const calendarGridCells = document.getElementById('calendar-grid-cells');
        const agendaListContainer = document.getElementById('agenda-list-container');
        const agendaTitle = document.getElementById('agenda-title');
        const selectedDateLabel = document.getElementById('selected-date-label');
        const calendarLoading = document.getElementById('calendar-loading');
        
        let defaultAgendaHtml = agendaListContainer.innerHTML;
        let defaultAgendaTitle = agendaTitle.textContent;

        // Function to bind click listeners to day cells
        function bindDayCells() {
            const dayCells = document.querySelectorAll('.day-cell');
            dayCells.forEach(cell => {
                cell.addEventListener('click', function(e) {
                    // Remove active styling from all cells
                    dayCells.forEach(c => c.classList.remove('ring-2', 'ring-[#1E3A8A]', 'z-10'));
                    
                    // Add active style to clicked cell
                    this.classList.add('ring-2', 'ring-[#1E3A8A]', 'z-10');

                    const day = this.getAttribute('data-day');
                    const events = JSON.parse(this.getAttribute('data-events') || '[]');

                    // Update selected date header label
                    const monthText = monthSelect.options[monthSelect.selectedIndex].text;
                    selectedDateLabel.innerHTML = `<span class="text-[#1E3A8A] font-extrabold">${day} ${monthText}</span>`;
                    agendaTitle.textContent = `Jadwal Tanggal ${day}`;

                    // Populate side panel with clicked day's events
                    agendaListContainer.innerHTML = '';

                    if (events.length === 0) {
                        agendaListContainer.innerHTML = `
                            <div class="py-12 text-center text-slate-400 font-semibold italic bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                                Tidak ada jadwal untuk tanggal ini.
                            </div>
                        `;
                    } else {
                        events.forEach(event => {
                            let dotColor = 'bg-slate-500 text-white';
                            if (event.type === 'Masuk') dotColor = 'bg-emerald-500 text-white';
                            else if (event.type === 'Renewal') dotColor = 'bg-amber-500 text-white';
                            else if (event.type === 'Maintenance') dotColor = 'bg-indigo-500 text-white';

                            const eventCard = `
                                <div class="flex items-start gap-3 p-2.5 rounded-xl bg-slate-50/50 border border-slate-100 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-xl ${dotColor} flex items-center justify-center font-black text-xs flex-shrink-0 shadow-sm">
                                        ${day}
                                    </div>
                                    <div class="space-y-0.5 overflow-hidden">
                                        <h4 class="font-extrabold text-slate-800 text-xs truncate">${event.title}</h4>
                                        <p class="text-[10px] font-semibold text-slate-450 truncate">${event.detail}</p>
                                    </div>
                                </div>
                            `;
                            agendaListContainer.insertAdjacentHTML('beforeend', eventCard);
                        });
                    }
                });
            });
        }

        // Fetch calendar data from backend
        function loadCalendarData(month, year) {
            // Show loading overlay
            calendarLoading.classList.remove('opacity-0', 'pointer-events-none');
            calendarLoading.classList.add('opacity-100');

            const url = `{{ route('admin.calendar.index') }}?month=${month}&year=${year}&ajax=1`;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                // Update dropdown values
                monthSelect.value = data.month;
                yearSelect.value = data.year;

                // Update DOM containers
                calendarGridCells.innerHTML = data.grid_html;
                agendaListContainer.innerHTML = data.agenda_html;

                // Update default agenda baseline state
                defaultAgendaHtml = data.agenda_html;
                defaultAgendaTitle = `Semua Jadwal ${data.monthName}`;

                // Reset selection highlights and side labels
                selectedDateLabel.textContent = 'Klik tanggal untuk melihat jadwal';
                agendaTitle.textContent = defaultAgendaTitle;

                // Re-bind click events to newly loaded day cells
                bindDayCells();
            })
            .catch(err => {
                console.error("Gagal memuat data kalender:", err);
            })
            .finally(() => {
                // Hide loading overlay
                calendarLoading.classList.remove('opacity-100');
                calendarLoading.classList.add('opacity-0', 'pointer-events-none');
            });
        }

        // Bind initial day cell click handlers
        bindDayCells();

        // Listen to Month dropdown selection change
        monthSelect.addEventListener('change', function() {
            loadCalendarData(this.value, yearSelect.value);
        });

        // Listen to Year dropdown selection change
        yearSelect.addEventListener('change', function() {
            loadCalendarData(monthSelect.value, this.value);
        });

        // Prev Month navigation click handler
        prevMonthBtn.addEventListener('click', function() {
            let currentMonth = parseInt(monthSelect.value);
            let currentYear = parseInt(yearSelect.value);

            currentMonth--;
            if (currentMonth < 1) {
                currentMonth = 12;
                currentYear--;
            }

            loadCalendarData(currentMonth, currentYear);
        });

        // Next Month navigation click handler
        nextMonthBtn.addEventListener('click', function() {
            let currentMonth = parseInt(monthSelect.value);
            let currentYear = parseInt(yearSelect.value);

            currentMonth++;
            if (currentMonth > 12) {
                currentMonth = 1;
                currentYear++;
            }

            loadCalendarData(currentMonth, currentYear);
        });

        // Reset click listener when clicking outside cells
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.day-cell') && !e.target.closest('#agenda-list-container') && !e.target.closest('.day-cell *')) {
                const dayCells = document.querySelectorAll('.day-cell');
                dayCells.forEach(c => c.classList.remove('ring-2', 'ring-[#1E3A8A]', 'z-10'));
                selectedDateLabel.textContent = 'Klik tanggal untuk melihat jadwal';
                agendaTitle.textContent = defaultAgendaTitle;
                agendaListContainer.innerHTML = defaultAgendaHtml;
            }
        });

        // Dynamic delegation click handler for event pills & agenda items
        document.addEventListener('click', function(e) {
            const eventPill = e.target.closest('.event-pill');
            const agendaItem = e.target.closest('.agenda-item');
            
            if (eventPill || agendaItem) {
                e.preventDefault();
                e.stopPropagation();
                
                const target = eventPill || agendaItem;
                const type = target.getAttribute('data-type');
                const title = target.getAttribute('data-title');
                const detail = target.getAttribute('data-detail');
                const date = target.getAttribute('data-date');
                
                openEventDetailModal(type, title, detail, date);
            }
        });

        // Close detail modal on click outside card
        const eventDetailModal = document.getElementById('event-detail-modal');
        if (eventDetailModal) {
            eventDetailModal.addEventListener('click', function(e) {
                if (!e.target.closest('#event-detail-card')) {
                    closeEventDetailModal();
                }
            });
        }
    });

    function openEventDetailModal(type, title, detail, date) {
        const modal = document.getElementById('event-detail-modal');
        const card = document.getElementById('event-detail-card');
        const header = document.getElementById('event-modal-header');
        const badge = document.getElementById('event-modal-type-badge');
        const titleEl = document.getElementById('event-modal-title');
        const locationEl = document.getElementById('event-modal-location');
        const dateEl = document.getElementById('event-modal-date');
        
        // Color themes
        header.className = "p-5 text-white flex items-center justify-between " + (
            type === 'Masuk' ? 'bg-emerald-600' :
            type === 'Renewal' ? 'bg-amber-500' : 'bg-indigo-600'
        );
        
        badge.textContent = type === 'Masuk' ? 'Tenant Masuk' :
                            type === 'Renewal' ? 'Lease Berakhir' : 'Maintenance';
                            
        titleEl.textContent = title;
        locationEl.textContent = detail;
        dateEl.textContent = date;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 50);
    }
    
    function closeEventDetailModal() {
        const modal = document.getElementById('event-detail-modal');
        const card = document.getElementById('event-detail-card');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
    
    // Make them globally available
    window.openEventDetailModal = openEventDetailModal;
    window.closeEventDetailModal = closeEventDetailModal;
</script>
@endsection
