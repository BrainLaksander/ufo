@extends('layouts.public.mahasiswa')

@section('title', 'Kalendar Kegiatan Kampus - UFO')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/views/mahasiswa-calendar.css') }}">
@endpush

@section('content')
<section class="figma-page-container figma-calendar-page py-3" aria-label="Kalendar kegiatan kampus">
    <header class="figma-page-header">
        <h1>Kalendar Kegiatan Kampus</h1>
        <p>Lihat jadwal event kampus per tanggal dan buka detail kegiatan langsung dari kalender.</p>
    </header>

    <article class="figma-calendar-shell">
        <div class="figma-calendar-toolbar">
            <button type="button" class="figma-calendar-nav" id="calendar-prev" aria-label="Bulan sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </button>
            <h2 id="calendar-month-label">-</h2>
            <button type="button" class="figma-calendar-nav" id="calendar-next" aria-label="Bulan berikutnya">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <div class="figma-calendar-weekdays" aria-hidden="true">
            <span>Min</span>
            <span>Sen</span>
            <span>Sel</span>
            <span>Rab</span>
            <span>Kam</span>
            <span>Jum</span>
            <span>Sab</span>
        </div>

        <div id="calendar-grid" class="figma-calendar-grid" role="grid" aria-label="Kalendar bulanan"></div>

        <div class="figma-calendar-agenda">
            <h3 id="agenda-title">Agenda Tanggal Terpilih</h3>
            <div id="agenda-list" class="figma-calendar-agenda-list"></div>
        </div>
    </article>
</section>
@endsection

@push('scripts')
<script>
(function () {
    var events = @json($calendarEvents ?? []);
    var gridEl = document.getElementById('calendar-grid');
    var monthLabelEl = document.getElementById('calendar-month-label');
    var agendaTitleEl = document.getElementById('agenda-title');
    var agendaListEl = document.getElementById('agenda-list');
    var prevBtn = document.getElementById('calendar-prev');
    var nextBtn = document.getElementById('calendar-next');

    var monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    var today = new Date();
    var currentYear = today.getFullYear();
    var currentMonth = today.getMonth();
    var selectedDateKey = toDateKey(today);

    function toDateKey(date) {
        var y = date.getFullYear();
        var m = String(date.getMonth() + 1).padStart(2, '0');
        var d = String(date.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + d;
    }

    function parseDateKey(dateKey) {
        var parts = String(dateKey || '').split('-');
        if (parts.length !== 3) {
            return null;
        }

        var y = Number(parts[0]);
        var m = Number(parts[1]);
        var d = Number(parts[2]);

        if (!Number.isFinite(y) || !Number.isFinite(m) || !Number.isFinite(d)) {
            return null;
        }

        return new Date(y, m - 1, d);
    }

    function formatDateLong(date) {
        return String(date.getDate()) + ' ' + monthNames[date.getMonth()] + ' ' + String(date.getFullYear());
    }

    function eventsByDateKey() {
        return events.reduce(function (acc, eventItem) {
            var key = eventItem.date_iso;
            if (!key) {
                return acc;
            }

            if (!acc[key]) {
                acc[key] = [];
            }

            acc[key].push(eventItem);
            return acc;
        }, {});
    }

    var groupedEvents = eventsByDateKey();

    function mapBadgeClass(category) {
        var value = String(category || '').toLowerCase();

        if (value.includes('akademik') || value.includes('acad')) {
            return 'acad';
        }

        if (value.includes('libur') || value.includes('holiday')) {
            return 'holiday';
        }

        if (value.includes('besar') || value.includes('utama') || value.includes('major')) {
            return 'major';
        }

        return 'org';
    }

    function renderAgenda(dateKey) {
        var selectedDate = parseDateKey(dateKey);
        var dayEvents = groupedEvents[dateKey] || [];

        agendaTitleEl.textContent = selectedDate
            ? 'Agenda ' + formatDateLong(selectedDate)
            : 'Agenda Tanggal Terpilih';

        if (dayEvents.length === 0) {
            agendaListEl.innerHTML = '<p class="figma-muted mb-0">Tidak ada kegiatan kampus pada tanggal ini.</p>';
            return;
        }

        agendaListEl.innerHTML = dayEvents.map(function (eventItem) {
            return `
                <article class="figma-calendar-event-card">
                    <div>
                        <h4>${eventItem.title}</h4>
                        <p class="figma-muted mb-1"><i class="bi bi-people"></i> ${eventItem.organizer || '-'} </p>
                        <p class="figma-muted mb-1"><i class="bi bi-geo-alt"></i> ${eventItem.location || '-'}</p>
                        <p class="figma-muted mb-0"><i class="bi bi-clock"></i> ${eventItem.time || '-'}</p>
                    </div>
                    <a href="${eventItem.detail_url}" class="figma-btn-primary">Detail</a>
                </article>
            `;
        }).join('');
    }

    function renderCalendar(year, month) {
        monthLabelEl.textContent = monthNames[month] + ' ' + String(year);

        var firstDayOfMonth = new Date(year, month, 1);
        var lastDayOfMonth = new Date(year, month + 1, 0);
        var calendarStart = new Date(firstDayOfMonth);
        calendarStart.setDate(calendarStart.getDate() - calendarStart.getDay());
        var calendarEnd = new Date(lastDayOfMonth);
        calendarEnd.setDate(calendarEnd.getDate() + (6 - calendarEnd.getDay()));
        var totalCells = Math.round((calendarEnd - calendarStart) / 86400000) + 1;

        // Keep a stable 6-week grid like pengurus dashboard.
        if (totalCells < 42) {
            calendarEnd.setDate(calendarEnd.getDate() + (42 - totalCells));
        }

        var html = '';

        for (var dateObj = new Date(calendarStart); dateObj <= calendarEnd; dateObj.setDate(dateObj.getDate() + 1)) {
            var day = dateObj.getDate();
            var dateKey = toDateKey(dateObj);
            var isToday = dateKey === toDateKey(today);
            var isSelected = dateKey === selectedDateKey;
            var isMuted = dateObj.getMonth() !== month;
            var eventCount = (groupedEvents[dateKey] || []).length;
            var dailyEvents = groupedEvents[dateKey] || [];
            var previewEvents = dailyEvents.slice(0, 2).map(function (eventItem) {
                var badgeClass = mapBadgeClass(eventItem.category);
                return '<span class="figma-calendar-badge ' + badgeClass + '">' + (eventItem.title || '-') + '</span>';
            }).join('');
            var overflowCount = Math.max(0, dailyEvents.length - 2);
            var overflowHtml = overflowCount > 0
                ? '<span class="figma-calendar-overflow">+' + overflowCount + ' lainnya</span>'
                : '';

            html += `
                <button
                    type="button"
                    class="figma-calendar-cell ${isToday ? 'is-today' : ''} ${isSelected ? 'is-selected' : ''} ${isMuted ? 'is-muted' : ''}"
                    data-date-key="${dateKey}"
                    role="gridcell"
                    aria-label="${day} ${monthNames[month]} ${year}, ${eventCount} kegiatan"
                >
                    <span class="figma-calendar-day">${day}</span>
                    <div class="figma-calendar-events-preview">
                        ${previewEvents}
                        ${overflowHtml}
                    </div>
                </button>
            `;
        }

        gridEl.innerHTML = html;

        Array.from(gridEl.querySelectorAll('[data-date-key]')).forEach(function (button) {
            button.addEventListener('click', function () {
                selectedDateKey = button.getAttribute('data-date-key') || '';
                renderCalendar(currentYear, currentMonth);
                renderAgenda(selectedDateKey);
            });
        });
    }

    prevBtn.addEventListener('click', function () {
        currentMonth -= 1;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear -= 1;
        }
        renderCalendar(currentYear, currentMonth);
    });

    nextBtn.addEventListener('click', function () {
        currentMonth += 1;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear += 1;
        }
        renderCalendar(currentYear, currentMonth);
    });

    var selectedInCurrentMonth = parseDateKey(selectedDateKey);
    if (!selectedInCurrentMonth || selectedInCurrentMonth.getMonth() !== currentMonth || selectedInCurrentMonth.getFullYear() !== currentYear) {
        var firstEventDate = events.length > 0 ? parseDateKey(events[0].date_iso) : null;
        if (firstEventDate) {
            currentYear = firstEventDate.getFullYear();
            currentMonth = firstEventDate.getMonth();
            selectedDateKey = events[0].date_iso;
        }
    }

    renderCalendar(currentYear, currentMonth);
    renderAgenda(selectedDateKey);
})();
</script>
@endpush
