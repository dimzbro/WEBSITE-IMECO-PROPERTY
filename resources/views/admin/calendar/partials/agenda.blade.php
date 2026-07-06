@php $hasAnyEvents = false; @endphp
@foreach($calendarGrid as $cell)
    @if($cell !== null && count($cell['events']) > 0)
        @php $hasAnyEvents = true; @endphp
        @foreach($cell['events'] as $event)
            @php
                $dotColors = [
                    'Masuk' => 'bg-emerald-500 text-white',
                    'Renewal' => 'bg-amber-500 text-white',
                    'Maintenance' => 'bg-indigo-500 text-white',
                ][$event['type']] ?? 'bg-slate-500 text-white';
            @endphp
            <div class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100 duration-200 agenda-item cursor-pointer"
                 data-type="{{ $event['type'] }}"
                 data-title="{{ $event['title'] }}"
                 data-detail="{{ $event['detail'] }}"
                 data-date="{{ $cell['day'] }} {{ $monthName }}">
                <div class="w-8 h-8 rounded-xl {{ $dotColors }} flex items-center justify-center font-black text-xs flex-shrink-0 shadow-sm">
                    {{ $cell['day'] }}
                </div>
                <div class="space-y-0.5 overflow-hidden">
                    <h4 class="font-extrabold text-slate-800 text-xs truncate">{{ $event['title'] }}</h4>
                    <p class="text-[10px] font-semibold text-slate-400 truncate">{{ $event['detail'] }}</p>
                </div>
            </div>
        @endforeach
    @endif
@endforeach

@if(!$hasAnyEvents)
    <div class="py-12 text-center text-slate-400 font-semibold italic bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
        Tidak ada jadwal kegiatan bulan ini.
    </div>
@endif
