@extends('layouts.portal.pengurus')

@section('title', 'Dashboard Organisasi')

@php
    $activities = $activities ?? [];
    $summaryCards = $summaryCards ?? [];
    $legendItems = $legendItems ?? [];
    $calendarDays = $calendarDays ?? [];
    $pendingTasks = $pendingTasks ?? [];
    $monthLabel = $monthLabel ?? \Carbon\Carbon::now()->translatedFormat('F Y');
    $prevMonth = $prevMonth ?? \Carbon\Carbon::now()->subMonth()->format('Y-m');
    $nextMonth = $nextMonth ?? \Carbon\Carbon::now()->addMonth()->format('Y-m');
    $profileStatusValue = $profileStatusValue ?? '';
    $profileStatusLabel = $profileStatusLabel ?? '';
@endphp

@section('content')
<div class="ufo-kboard-page">
    <section class="ufo-kboard-section">
        <h1 class="ufo-kboard-heading">Dashboard Organisasi</h1>
        <p class="ufo-kboard-lead">Selamat datang kembali! Berikut ringkasan kondisi organisasi Anda.</p>

        <div class="ufo-pg-calendar-shell mt-3">
            <div class="ufo-pg-calendar-summary">
                @foreach($summaryCards as $card)
                    <article class="{{ $card['tone'] }}">
                        <p>{{ $card['label'] }}</p>
                        <h4>{{ $card['value'] }}</h4>
                    </article>
                @endforeach
            </div>

            <div class="ufo-pg-calendar-legend">
                @foreach($legendItems as $legend)
                    <span>{{ $legend['label'] }}</span>
                @endforeach
            </div>

            <div class="ufo-pg-calendar-nav">
                <a href="{{ route('dashboard.pengurus', ['bulan' => $prevMonth]) }}" class="ufo-kboard-btn ghost" aria-label="Bulan sebelumnya">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <h3>{{ $monthLabel }}</h3>
                <a href="{{ route('dashboard.pengurus', ['bulan' => $nextMonth]) }}" class="ufo-kboard-btn ghost" aria-label="Bulan berikutnya">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>

            <div class="ufo-pg-calendar-grid">
                <div class="ufo-pg-calendar-weekdays">
                    <span>Min</span>
                    <span>Sen</span>
                    <span>Sel</span>
                    <span>Rab</span>
                    <span>Kam</span>
                    <span>Jum</span>
                    <span>Sab</span>
                </div>

                <div class="ufo-pg-calendar-days">
                    @foreach($calendarDays as $day)
                        <article class="ufo-pg-calendar-day {{ $day['muted'] ? 'muted' : '' }}">
                            <span class="ufo-pg-calendar-date {{ $day['is_today'] ? 'today' : '' }}">{{ $day['day'] }}</span>

                            @foreach($day['events'] as $event)
                                <span class="ufo-pg-event-badge {{ $event['badge'] }}">{{ $event['name'] }}</span>
                            @endforeach

                            @if($day['overflow'] > 0)
                                <span class="ufo-kboard-item-meta">+{{ $day['overflow'] }} lainnya</span>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="ufo-kboard-section">
        <div class="ufo-kboard-row two">
            <article class="ufo-kboard-stat green">
                <h3>{{ $profileStatusValue }}</h3>
                <p>{{ $profileStatusLabel }}</p>
            </article>

            <article class="ufo-kboard-stat gold">
                <h3>{{ count($pendingTasks) }}</h3>
                <p>Tugas Menunggu Tindak Lanjut</p>
            </article>
        </div>
    </section>

    <section class="ufo-kboard-section">
        <h3 class="ufo-kboard-item-title">Tugas Pending</h3>
        <div class="ufo-pg-tasks mt-2">
            @forelse($pendingTasks as $task)
                <article class="ufo-pg-task-card {{ $task['priority'] }}">
                    <div class="top">
                        <strong>{{ $task['task'] }}</strong>
                        <span class="ufo-pg-task-tag {{ $task['priority'] }}">{{ ucfirst($task['priority']) }}</span>
                    </div>
                    <p>{{ $task['deadline'] }}</p>
                </article>
            @empty
                <p class="ufo-kboard-item-meta mb-0">Belum ada tugas pending dari database.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
