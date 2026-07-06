@foreach($calendarGrid as $index => $cell)
    @if($cell === null)
        <!-- Empty padding slots -->
        <div class="bg-slate-50/30 min-h-[110px] p-2"></div>
    @else
        @php
            $isToday = (\Carbon\Carbon::today()->day == $cell['day'] && \Carbon\Carbon::today()->month == $month && \Carbon\Carbon::today()->year == $year);
            $hasEvents = count($cell['events']) > 0;
        @endphp
        <div class="bg-white min-h-[110px] p-2.5 flex flex-col justify-between hover:bg-slate-50/50 transition-colors group cursor-pointer day-cell" 
             data-day="{{ $cell['day'] }}"
             data-events="{{ json_encode($cell['events']) }}">
            
            <!-- Day Number -->
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-extrabold flex items-center justify-center w-6 h-6 rounded-full transition-colors 
                    {{ $isToday ? 'bg-[#1E3A8A] text-white shadow-md' : 'text-slate-700 group-hover:bg-slate-100' }}">
                    {{ $cell['day'] }}
                </span>
                @if($hasEvents)
                    <span class="w-1.5 h-1.5 rounded-full bg-[#1E3A8A]"></span>
                @endif
            </div>

            <!-- Events Pills List inside Day cell -->
            <div class="space-y-1 overflow-y-auto max-h-[70px] pr-0.5">
                @foreach($cell['events'] as $event)
                    @php
                        $colorClasses = [
                            'Masuk' => 'bg-emerald-50 text-emerald-700 border-emerald-100 hover:bg-emerald-100/50',
                            'Renewal' => 'bg-amber-50 text-amber-700 border-amber-100 hover:bg-amber-100/50',
                            'Maintenance' => 'bg-indigo-50 text-indigo-700 border-indigo-100 hover:bg-indigo-100/50',
                        ][$event['type']] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                    @endphp
                    <div class="px-2 py-0.5 rounded text-[9px] font-black border truncate transition-all duration-200 {{ $colorClasses }} event-pill cursor-pointer"
                         data-type="{{ $event['type'] }}"
                         data-title="{{ $event['title'] }}"
                         data-detail="{{ $event['detail'] }}"
                         data-date="{{ $cell['day'] }} {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}"
                         title="{{ $event['title'] }} – {{ $event['detail'] }}">
                        {{ $event['title'] }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endforeach
