@extends('layouts.app')

@section('title', 'Dashboard Organisasi')
@section('bodyClass', 'ufo-orgdash-body')
@section('mainClass', 'ufo-orgdash-main')

@php
    $summaryCards = [
        [
            'label' => 'Total Kegiatan Akademik',
            'value' => '2',
            'tone' => 'blue',
        ],
        [
            'label' => 'Total Kegiatan Organisasi',
            'value' => '4',
            'tone' => 'green',
        ],
        [
            'label' => 'Bulan Ini',
            'value' => '4',
            'tone' => 'purple',
        ],
        [
            'label' => 'Total Semua Event',
            'value' => '12',
            'tone' => 'orange',
        ],
    ];

    $legendItems = [
        ['key' => 'academic', 'label' => 'Kegiatan Akademik', 'tone' => 'blue'],
        ['key' => 'organization', 'label' => 'Kegiatan Organisasi', 'tone' => 'green'],
        ['key' => 'restricted', 'label' => 'Masa Tidak Boleh Berorganisasi', 'tone' => 'red'],
        ['key' => 'holiday', 'label' => 'Libur Akademik', 'tone' => 'yellow'],
        ['key' => 'campus', 'label' => 'Event Kampus Besar', 'tone' => 'purple'],
    ];

    $calendarWeeks = [
        [
            ['day' => 30, 'muted' => true, 'events' => []],
            ['day' => 31, 'muted' => true, 'events' => []],
            ['day' => 1, 'muted' => false, 'events' => [
                ['title' => 'UAS Semester Ganjil', 'type' => 'academic', 'tone' => 'blue'],
                ['title' => 'Masa Tenang UAS', 'type' => 'restricted', 'tone' => 'red'],
            ]],
            ['day' => 2, 'muted' => false, 'events' => []],
            ['day' => 3, 'muted' => false, 'events' => []],
            ['day' => 4, 'muted' => false, 'events' => []],
            ['day' => 5, 'muted' => false, 'events' => []],
        ],
        [
            ['day' => 6, 'muted' => false, 'events' => []],
            ['day' => 7, 'muted' => false, 'events' => []],
            ['day' => 8, 'muted' => false, 'events' => []],
            ['day' => 9, 'muted' => false, 'events' => []],
            ['day' => 10, 'muted' => false, 'events' => []],
            ['day' => 11, 'muted' => false, 'events' => []],
            ['day' => 12, 'muted' => false, 'events' => []],
        ],
        [
            ['day' => 13, 'muted' => false, 'events' => []],
            ['day' => 14, 'muted' => false, 'events' => []],
            ['day' => 15, 'muted' => false, 'events' => []],
            ['day' => 16, 'muted' => false, 'events' => []],
            ['day' => 17, 'muted' => false, 'events' => [
                ['title' => 'Libur Paskah', 'type' => 'holiday', 'tone' => 'yellow'],
            ]],
            ['day' => 18, 'muted' => false, 'events' => []],
            ['day' => 19, 'muted' => false, 'events' => []],
        ],
        [
            ['day' => 20, 'muted' => false, 'events' => []],
            ['day' => 21, 'muted' => false, 'events' => []],
            ['day' => 22, 'muted' => false, 'events' => []],
            ['day' => 23, 'muted' => false, 'events' => []],
            ['day' => 24, 'muted' => false, 'events' => []],
            ['day' => 25, 'muted' => false, 'events' => [
                ['title' => 'Recruitment Organisasi', 'type' => 'organization', 'tone' => 'green'],
            ]],
            ['day' => 26, 'muted' => false, 'events' => []],
        ],
        [
            ['day' => 27, 'muted' => false, 'events' => []],
            ['day' => 28, 'muted' => false, 'events' => [
                ['title' => 'Festival Kampus', 'type' => 'campus', 'tone' => 'purple'],
            ]],
            ['day' => 29, 'muted' => false, 'events' => []],
            ['day' => 30, 'muted' => false, 'events' => []],
            ['day' => 1, 'muted' => true, 'events' => []],
            ['day' => 2, 'muted' => true, 'events' => []],
            ['day' => 3, 'muted' => true, 'events' => []],
        ],
    ];

    $monthEvents = [
        ['title' => 'UAS Semester Ganjil', 'date' => '1 Apr - 8 Apr 2026', 'type' => 'academic', 'tone' => 'blue', 'group' => 'Kegiatan'],
        ['title' => 'Libur Paskah', 'date' => '17 Apr - 20 Apr 2026', 'type' => 'holiday', 'tone' => 'yellow', 'group' => 'Libur'],
        ['title' => 'Masa Tenang UAS', 'date' => '29 Mar - 9 Apr 2026', 'type' => 'restricted', 'tone' => 'red', 'group' => 'Masa'],
        ['title' => 'Recruitment Organisasi', 'date' => '25 Apr - 5 Mei 2026', 'type' => 'organization', 'tone' => 'green', 'group' => 'Kegiatan'],
    ];

    $pendingTasks = [
        [
            'title' => 'Revisi Proposal Event',
            'deadline' => 'Deadline: 2 hari lagi',
            'priority' => 'Urgent',
            'tone' => 'urgent',
        ],
        [
            'title' => 'Update Profil Organisasi',
            'deadline' => 'Deadline: 5 hari lagi',
            'priority' => 'Normal',
            'tone' => 'normal',
        ],
        [
            'title' => 'Upload Dokumentasi Event',
            'deadline' => 'Deadline: 1 minggu lagi',
            'priority' => 'Low',
            'tone' => 'low',
        ],
    ];
@endphp

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .ufo-orgdash-body .ufo-shell-header,
    .ufo-orgdash-body .ufo-shell-footer {
        display: none;
    }

    .ufo-orgdash-body {
        background: #f0f1f5;
    }

    .ufo-orgdash-main {
        width: 100%;
        max-width: none;
        padding: 0;
        margin: 0;
    }

    .ufo-orgdash {
        min-height: 100vh;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #111827;
        position: relative;
        padding-bottom: 4.5rem;
    }

    .ufo-orgdash-topbar {
        background: #6d33a5;
        color: #ffffff;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        position: sticky;
        top: 0;
        z-index: 20;
        box-shadow: 0 3px 14px rgba(56, 22, 88, 0.26);
    }

    .ufo-orgdash-topbar-inner {
        width: min(100%, 960px);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .ufo-orgdash-icon-btn {
        border: 0;
        background: transparent;
        color: #ffffff;
        width: 2.2rem;
        height: 2.2rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        position: relative;
    }

    .ufo-orgdash-icon-btn:hover {
        background: rgba(255, 255, 255, 0.16);
    }

    .ufo-orgdash-brand {
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
        margin-right: auto;
    }

    .ufo-orgdash-brand-mark {
        width: 2rem;
        height: 2rem;
        border-radius: 999px;
        background: #facc15;
        color: #5b21b6;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
    }

    .ufo-orgdash-brand h1 {
        margin: 0;
        font-size: 1.12rem;
        font-weight: 800;
        letter-spacing: 0.01em;
        color: #ffffff;
    }

    .ufo-orgdash-brand p {
        margin: 0.05rem 0 0;
        font-size: 0.75rem;
        opacity: 0.82;
    }

    .ufo-orgdash-alert-dot {
        position: absolute;
        top: 0.32rem;
        right: 0.4rem;
        width: 0.44rem;
        height: 0.44rem;
        border-radius: 999px;
        background: #ef4444;
        border: 2px solid #6d33a5;
    }

    .ufo-orgdash-container {
        width: min(100%, 960px);
        margin: 1rem auto 0;
        padding: 0 0.75rem;
    }

    .ufo-orgdash-panel {
        background: #f3f4f6;
    }

    .ufo-orgdash-title {
        font-size: clamp(1.45rem, 2.4vw, 2rem);
        line-height: 1.16;
        font-weight: 800;
        margin: 0;
        color: #0f172a;
    }

    .ufo-orgdash-subtitle {
        margin: 0.3rem 0 1.1rem;
        color: #4b5563;
        font-size: 0.99rem;
    }

    .ufo-orgdash-card {
        border: 1px solid #d4d4d8;
        background: #ffffff;
        border-radius: 14px;
        padding: 1.12rem;
        box-shadow: 0 2px 9px rgba(15, 23, 42, 0.07);
        margin-bottom: 1rem;
    }

    .ufo-orgdash-card-head {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        margin-bottom: 0.8rem;
    }

    .ufo-orgdash-card-head-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.55rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #7c3aed;
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        flex-shrink: 0;
    }

    .ufo-orgdash-card h2 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 800;
        color: #111827;
    }

    .ufo-orgdash-card-lead {
        margin: 0.25rem 0 0;
        color: #4b5563;
        font-size: 0.92rem;
    }

    .ufo-orgdash-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.7rem;
        margin-bottom: 0.95rem;
    }

    .ufo-orgdash-summary-item {
        border-radius: 12px;
        border: 1px solid transparent;
        padding: 0.8rem 0.9rem;
    }

    .ufo-orgdash-summary-item small {
        display: block;
        font-size: 0.84rem;
        font-weight: 600;
        margin-bottom: 0.2rem;
    }

    .ufo-orgdash-summary-item strong {
        display: block;
        font-size: 1.8rem;
        line-height: 1;
        font-weight: 800;
    }

    .ufo-orgdash-summary-item.tone-blue {
        background: #eff6ff;
        border-color: #93c5fd;
        color: #2563eb;
    }

    .ufo-orgdash-summary-item.tone-green {
        background: #f0fdf4;
        border-color: #86efac;
        color: #16a34a;
    }

    .ufo-orgdash-summary-item.tone-purple {
        background: #faf5ff;
        border-color: #d8b4fe;
        color: #7e22ce;
    }

    .ufo-orgdash-summary-item.tone-orange {
        background: #fff7ed;
        border-color: #fdba74;
        color: #ea580c;
    }

    .ufo-orgdash-filter {
        margin-bottom: 0.72rem;
    }

    .ufo-orgdash-filter label {
        display: block;
        font-size: 0.88rem;
        color: #374151;
        font-weight: 600;
        margin-bottom: 0.36rem;
    }

    .ufo-orgdash-select {
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 11px;
        background: #ffffff;
        color: #111827;
        font-size: 0.95rem;
        padding: 0.62rem 0.78rem;
    }

    .ufo-orgdash-select:focus {
        outline: none;
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.14);
    }

    .ufo-orgdash-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 0.55rem 0.9rem;
        margin-bottom: 0.95rem;
    }

    .ufo-orgdash-legend-item {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.76rem;
        color: #374151;
    }

    .ufo-orgdash-legend-item.is-dim {
        opacity: 0.45;
    }

    .ufo-orgdash-dot {
        width: 0.58rem;
        height: 0.58rem;
        border-radius: 999px;
        display: inline-block;
    }

    .ufo-orgdash-dot.tone-blue {
        background: #3b82f6;
    }

    .ufo-orgdash-dot.tone-green {
        background: #22c55e;
    }

    .ufo-orgdash-dot.tone-red {
        background: #ef4444;
    }

    .ufo-orgdash-dot.tone-yellow {
        background: #eab308;
    }

    .ufo-orgdash-dot.tone-purple {
        background: #a855f7;
    }

    .ufo-orgdash-month-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .ufo-orgdash-month-row h3 {
        margin: 0;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 0.01em;
        color: #111827;
    }

    .ufo-orgdash-month-btn {
        border: 0;
        background: transparent;
        color: #1f2937;
        width: 2rem;
        height: 2rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .ufo-orgdash-month-btn:hover {
        background: #e5e7eb;
    }

    .ufo-orgdash-calendar {
        border: 1px solid #d1d5db;
        border-radius: 12px;
        overflow: hidden;
        background: #ffffff;
    }

    .ufo-orgdash-week-header,
    .ufo-orgdash-week-row {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
    }

    .ufo-orgdash-week-header span {
        border-right: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
        text-align: center;
        font-size: 0.84rem;
        font-weight: 700;
        color: #374151;
        padding: 0.58rem 0.2rem;
    }

    .ufo-orgdash-week-header span:last-child {
        border-right: 0;
    }

    .ufo-orgdash-day {
        min-height: 100px;
        border-right: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        padding: 0.4rem 0.38rem;
        display: flex;
        flex-direction: column;
        gap: 0.28rem;
        background: #ffffff;
    }

    .ufo-orgdash-week-row:last-child .ufo-orgdash-day {
        border-bottom: 0;
    }

    .ufo-orgdash-day:nth-child(7n) {
        border-right: 0;
    }

    .ufo-orgdash-day.muted {
        background: #f8fafc;
    }

    .ufo-orgdash-day-number {
        font-size: 0.9rem;
        font-weight: 700;
        color: #111827;
    }

    .ufo-orgdash-day.muted .ufo-orgdash-day-number {
        color: #9ca3af;
    }

    .ufo-orgdash-event-tag {
        border-radius: 6px;
        padding: 0.15rem 0.36rem;
        font-size: 0.66rem;
        font-weight: 700;
        line-height: 1.2;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .ufo-orgdash-event-tag.tone-blue {
        background: #3b82f6;
        color: #ffffff;
    }

    .ufo-orgdash-event-tag.tone-green {
        background: #22c55e;
        color: #ffffff;
    }

    .ufo-orgdash-event-tag.tone-red {
        background: #ef4444;
        color: #ffffff;
    }

    .ufo-orgdash-event-tag.tone-yellow {
        background: #eab308;
        color: #78350f;
    }

    .ufo-orgdash-event-tag.tone-purple {
        background: #a855f7;
        color: #ffffff;
    }

    .ufo-orgdash-event-tag.is-hidden {
        display: none;
    }

    .ufo-orgdash-list-title {
        margin: 0 0 0.8rem;
        font-size: 1.72rem;
        font-weight: 800;
        color: #111827;
    }

    .ufo-orgdash-event-list {
        display: grid;
        gap: 0.58rem;
    }

    .ufo-orgdash-event-item {
        border: 1px solid transparent;
        border-radius: 12px;
        padding: 0.8rem 0.85rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
    }

    .ufo-orgdash-event-item h4 {
        margin: 0;
        font-size: 0.98rem;
        font-weight: 700;
    }

    .ufo-orgdash-event-item p {
        margin: 0.15rem 0 0;
        font-size: 0.83rem;
        color: #4b5563;
    }

    .ufo-orgdash-event-item-type {
        font-size: 0.8rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .ufo-orgdash-event-item.tone-blue {
        background: #dbeafe;
        border-color: #93c5fd;
        color: #2563eb;
    }

    .ufo-orgdash-event-item.tone-green {
        background: #dcfce7;
        border-color: #86efac;
        color: #15803d;
    }

    .ufo-orgdash-event-item.tone-red {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #dc2626;
    }

    .ufo-orgdash-event-item.tone-yellow {
        background: #fef9c3;
        border-color: #facc15;
        color: #a16207;
    }

    .ufo-orgdash-event-item.tone-purple {
        background: #f3e8ff;
        border-color: #d8b4fe;
        color: #7e22ce;
    }

    .ufo-orgdash-event-item.is-hidden {
        display: none;
    }

    .ufo-orgdash-status-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.85rem;
        margin-bottom: 1rem;
    }

    .ufo-orgdash-status {
        border-radius: 14px;
        border: 2px solid transparent;
        padding: 0.92rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
        min-height: 94px;
    }

    .ufo-orgdash-status h4 {
        margin: 0;
        font-size: 1.06rem;
        font-weight: 800;
    }

    .ufo-orgdash-status p {
        margin: 0.22rem 0 0;
        font-size: 0.93rem;
        font-weight: 700;
    }

    .ufo-orgdash-status-icon {
        width: 2.2rem;
        height: 2.2rem;
        border-radius: 0.8rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.08rem;
        background: rgba(255, 255, 255, 0.72);
    }

    .ufo-orgdash-status.profile {
        background: #dcfce7;
        border-color: #86efac;
        color: #15803d;
    }

    .ufo-orgdash-status.approval {
        background: #fef9c3;
        border-color: #facc15;
        color: #a16207;
    }

    .ufo-orgdash-task-list {
        display: grid;
        gap: 0.6rem;
    }

    .ufo-orgdash-task {
        border-radius: 12px;
        border: 1px solid transparent;
        padding: 0.78rem 0.8rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .ufo-orgdash-task h4 {
        margin: 0;
        font-size: 0.96rem;
        font-weight: 700;
    }

    .ufo-orgdash-task p {
        margin: 0.2rem 0 0;
        font-size: 0.86rem;
        color: #1d4ed8;
        font-weight: 600;
    }

    .ufo-orgdash-task.tone-urgent {
        background: #fef9c3;
        border-color: #fde047;
    }

    .ufo-orgdash-task.tone-normal {
        background: #eff6ff;
        border-color: #bfdbfe;
    }

    .ufo-orgdash-task.tone-low {
        background: #dcfce7;
        border-color: #bbf7d0;
    }

    .ufo-orgdash-priority {
        border-radius: 999px;
        padding: 0.25rem 0.62rem;
        font-size: 0.72rem;
        font-weight: 700;
        line-height: 1;
        white-space: nowrap;
    }

    .ufo-orgdash-priority.urgent {
        background: #fde68a;
        color: #92400e;
    }

    .ufo-orgdash-priority.normal {
        background: #bfdbfe;
        color: #1d4ed8;
    }

    .ufo-orgdash-priority.low {
        background: #bbf7d0;
        color: #15803d;
    }

    .ufo-orgdash-chat {
        position: fixed;
        right: 1.1rem;
        bottom: 1.1rem;
        width: 3.2rem;
        height: 3.2rem;
        border-radius: 999px;
        border: 0;
        background: #6d33a5;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        box-shadow: 0 14px 28px rgba(88, 28, 135, 0.32);
        z-index: 35;
    }

    .ufo-orgdash-chat:hover {
        background: #5b2892;
    }

    @media (max-width: 900px) {
        .ufo-orgdash-month-row h3,
        .ufo-orgdash-list-title {
            font-size: 1.45rem;
        }

        .ufo-orgdash-day {
            min-height: 86px;
        }
    }

    @media (max-width: 768px) {
        .ufo-orgdash-container {
            padding: 0 0.55rem;
        }

        .ufo-orgdash-summary-grid,
        .ufo-orgdash-status-grid {
            grid-template-columns: 1fr;
        }

        .ufo-orgdash-week-header,
        .ufo-orgdash-week-row {
            min-width: 740px;
        }

        .ufo-orgdash-calendar {
            overflow-x: auto;
        }

        .ufo-orgdash-event-item {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<section class="ufo-orgdash">
    <header class="ufo-orgdash-topbar" aria-label="Navigasi dashboard organisasi">
        <div class="ufo-orgdash-topbar-inner">
            <button type="button" class="ufo-orgdash-icon-btn" aria-label="Buka menu navigasi">
                <i class="bi bi-list"></i>
            </button>

            <div class="ufo-orgdash-brand" aria-label="Brand UFO">
                <span class="ufo-orgdash-brand-mark">
                    <i class="bi bi-send-fill"></i>
                </span>
                <div>
                    <h1>UFO</h1>
                    <p>UNKLAB Forum Organization</p>
                </div>
            </div>

            <button type="button" class="ufo-orgdash-icon-btn" aria-label="Notifikasi">
                <i class="bi bi-bell"></i>
                <span class="ufo-orgdash-alert-dot" aria-hidden="true"></span>
            </button>
        </div>
    </header>

    <div class="ufo-orgdash-container">
        <section class="ufo-orgdash-panel">
            <h1 class="ufo-orgdash-title">Dashboard Organisasi</h1>
            <p class="ufo-orgdash-subtitle">Selamat datang kembali! Berikut ringkasan kondisi organisasi Anda.</p>

            <article class="ufo-orgdash-card">
                <div class="ufo-orgdash-card-head">
                    <span class="ufo-orgdash-card-head-icon">
                        <i class="bi bi-calendar2-week"></i>
                    </span>
                    <div>
                        <h2>Kalender Kegiatan Akademik &amp; Organisasi</h2>
                        <p class="ufo-orgdash-card-lead">Tampilan kalender kegiatan untuk 2 semester / 1 tahun akademik (Read-Only)</p>
                    </div>
                </div>

                <div class="ufo-orgdash-summary-grid">
                    @foreach ($summaryCards as $card)
                        <article class="ufo-orgdash-summary-item tone-{{ $card['tone'] }}">
                            <small>{{ $card['label'] }}</small>
                            <strong>{{ $card['value'] }}</strong>
                        </article>
                    @endforeach
                </div>

                <div class="ufo-orgdash-filter">
                    <label for="calendar-filter">Filter Kegiatan:</label>
                    <select id="calendar-filter" class="ufo-orgdash-select">
                        <option value="all">Semua Kegiatan</option>
                        <option value="academic">Kegiatan Akademik</option>
                        <option value="organization">Kegiatan Organisasi</option>
                        <option value="restricted">Masa Tidak Boleh Berorganisasi</option>
                        <option value="holiday">Libur Akademik</option>
                        <option value="campus">Event Kampus Besar</option>
                    </select>
                </div>

                <div class="ufo-orgdash-legend" id="calendar-legend">
                    @foreach ($legendItems as $legend)
                        <span class="ufo-orgdash-legend-item" data-type="{{ $legend['key'] }}">
                            <span class="ufo-orgdash-dot tone-{{ $legend['tone'] }}"></span>
                            {{ $legend['label'] }}
                        </span>
                    @endforeach
                </div>

                <div class="ufo-orgdash-month-row">
                    <button type="button" class="ufo-orgdash-month-btn" aria-label="Bulan sebelumnya">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <h3>April 2026</h3>
                    <button type="button" class="ufo-orgdash-month-btn" aria-label="Bulan berikutnya">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <div class="ufo-orgdash-calendar">
                    <div class="ufo-orgdash-week-header">
                        <span>Min</span>
                        <span>Sen</span>
                        <span>Sel</span>
                        <span>Rab</span>
                        <span>Kam</span>
                        <span>Jum</span>
                        <span>Sab</span>
                    </div>

                    @foreach ($calendarWeeks as $week)
                        <div class="ufo-orgdash-week-row">
                            @foreach ($week as $day)
                                <div class="ufo-orgdash-day {{ $day['muted'] ? 'muted' : '' }}">
                                    <span class="ufo-orgdash-day-number">{{ $day['day'] }}</span>

                                    @foreach ($day['events'] as $event)
                                        <span class="ufo-orgdash-event-tag tone-{{ $event['tone'] }}" data-type="{{ $event['type'] }}">
                                            {{ $event['title'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </article>

            <section class="ufo-orgdash-card">
                <h2 class="ufo-orgdash-list-title">Semua Kegiatan Bulan April 2026</h2>

                <div class="ufo-orgdash-event-list" id="event-list">
                    @foreach ($monthEvents as $event)
                        <article class="ufo-orgdash-event-item tone-{{ $event['tone'] }}" data-type="{{ $event['type'] }}">
                            <div>
                                <h4>{{ $event['title'] }}</h4>
                                <p>{{ $event['date'] }}</p>
                            </div>
                            <span class="ufo-orgdash-event-item-type">{{ $event['group'] }}</span>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="ufo-orgdash-status-grid">
                <article class="ufo-orgdash-status profile">
                    <div>
                        <h4>Lengkap</h4>
                        <p>Status Profil</p>
                    </div>
                    <span class="ufo-orgdash-status-icon">
                        <i class="bi bi-check2-circle"></i>
                    </span>
                </article>

                <article class="ufo-orgdash-status approval">
                    <div>
                        <h4>5</h4>
                        <p>Pengajuan Disetujui</p>
                    </div>
                    <span class="ufo-orgdash-status-icon">
                        <i class="bi bi-journal-check"></i>
                    </span>
                </article>
            </section>

            <section class="ufo-orgdash-card">
                <h2>Tugas Pending</h2>

                <div class="ufo-orgdash-task-list">
                    @foreach ($pendingTasks as $task)
                        <article class="ufo-orgdash-task tone-{{ $task['tone'] }}">
                            <div>
                                <h4>{{ $task['title'] }}</h4>
                                <p>{{ $task['deadline'] }}</p>
                            </div>
                            <span class="ufo-orgdash-priority {{ $task['tone'] }}">{{ $task['priority'] }}</span>
                        </article>
                    @endforeach
                </div>
            </section>
        </section>
    </div>

    <button type="button" class="ufo-orgdash-chat" aria-label="Buka bantuan chat">
        <i class="bi bi-send-fill"></i>
    </button>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filter = document.getElementById('calendar-filter');
    const legendItems = Array.from(document.querySelectorAll('.ufo-orgdash-legend-item'));
    const eventTags = Array.from(document.querySelectorAll('.ufo-orgdash-event-tag'));
    const eventListItems = Array.from(document.querySelectorAll('.ufo-orgdash-event-item'));

    function applyFilter(filterValue) {
        const showAll = filterValue === 'all';

        legendItems.forEach(function (item) {
            const match = showAll || item.dataset.type === filterValue;
            item.classList.toggle('is-dim', !match);
        });

        eventTags.forEach(function (item) {
            const match = showAll || item.dataset.type === filterValue;
            item.classList.toggle('is-hidden', !match);
        });

        eventListItems.forEach(function (item) {
            const match = showAll || item.dataset.type === filterValue;
            item.classList.toggle('is-hidden', !match);
        });
    }

    filter.addEventListener('change', function () {
        applyFilter(filter.value);
    });

    applyFilter('all');
});
</script>
@endpush