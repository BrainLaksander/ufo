@extends('layouts.app')

@section('title', 'Notifikasi Sistem - Kemahasiswaan')

@section('content')
@push('head')
<link rel="stylesheet" href="/css/kemahasiswaan-compact.css">
<style>
    .notif-page {
        padding: 24px 30px;
        max-width: 960px;
        margin: 0 auto;
        min-height: calc(100vh - 74px);
    }
    .notif-page-header {
        margin-bottom: 28px;
    }
    .notif-page-header h1 {
        margin: 0 0 4px;
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
    }
    .notif-page-header p {
        margin: 0;
        font-size: 14px;
        color: #64748b;
    }
    .notif-stats-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }
    .notif-stat {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px 24px;
    }
    .notif-stat-label {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 6px;
        font-weight: 500;
    }
    .notif-stat-value {
        font-size: 32px;
        font-weight: 700;
    }
    .notif-stat-value.total { color: #3b2063; }
    .notif-stat-value.unread { color: #ef4444; }

    .notif-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .notif-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .notif-filter-btn {
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        transition: all 0.15s;
    }
    .notif-filter-btn:hover {
        background: #f1f5f9;
    }
    .notif-filter-btn.active {
        background: #3b2063;
        color: #fff;
        border-color: #3b2063;
    }
    .notif-mark-all-btn {
        background: none;
        border: none;
        color: #3b82f6;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .notif-mark-all-btn:hover {
        text-decoration: underline;
    }

    .notif-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .notif-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px 20px;
        display: flex;
        gap: 14px;
        position: relative;
        transition: box-shadow 0.15s;
    }
    .notif-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .notif-card.unread {
        border-left: 3px solid #3b2063;
        background: #faf8ff;
    }
    .notif-icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .notif-icon-box svg {
        width: 20px;
        height: 20px;
    }
    .notif-icon-box.document { background: #ede9fe; color: #7c3aed; }
    .notif-icon-box.edit { background: #fef3c7; color: #d97706; }
    .notif-icon-box.report { background: #d1fae5; color: #059669; }
    .notif-icon-box.message { background: #fce7f3; color: #db2777; }
    .notif-icon-box.users { background: #e0e7ff; color: #4f46e5; }
    .notif-icon-box.info { background: #f1f5f9; color: #64748b; }

    .notif-body {
        flex: 1;
        min-width: 0;
    }
    .notif-title {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 4px;
    }
    .notif-desc {
        font-size: 13px;
        color: #475569;
        margin: 0 0 8px;
        line-height: 1.5;
    }
    .notif-meta {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .notif-time {
        font-size: 12px;
        color: #94a3b8;
    }
    .notif-actions {
        display: flex;
        gap: 12px;
    }
    .notif-action-btn {
        color: #64748b;
        font-size: 12px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        font-family: inherit;
        transition: color 0.15s;
    }
    .notif-action-btn:hover { color: #1e293b; }
    .notif-action-btn.primary { color: #3b82f6; }
    .notif-action-btn.primary:hover { color: #2563eb; }
    .notif-action-btn.danger:hover { color: #dc2626; }

    .notif-empty {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 48px 20px;
        text-align: center;
    }
    .notif-empty svg {
        margin-bottom: 16px;
        color: #cbd5e1;
    }
    .notif-empty h3 {
        margin: 0 0 8px;
        font-size: 16px;
        font-weight: 600;
        color: #475569;
    }
    .notif-empty p {
        margin: 0;
        font-size: 14px;
        color: #94a3b8;
    }
</style>
@endpush

<div class="notif-page">
    <div class="notif-page-header">
        <h1>Notifikasi</h1>
        <p>Kelola semua notifikasi dan pemberitahuan sistem.</p>
    </div>

    <div class="notif-stats-row">
        <div class="notif-stat">
            <div class="notif-stat-label">Total Notifikasi</div>
            <div class="notif-stat-value total">{{ $totalNotifications }}</div>
        </div>
        <div class="notif-stat">
            <div class="notif-stat-label">Belum Dibaca</div>
            <div class="notif-stat-value unread">{{ $unreadCount }}</div>
        </div>
    </div>

    <div class="notif-toolbar">
        <div class="notif-filters">
            <a href="{{ route('kemahasiswaan.notifications.index', ['filter' => 'semua']) }}" class="notif-filter-btn {{ $filter === 'semua' ? 'active' : '' }}">Semua ({{ $counts['semua'] }})</a>
            <a href="{{ route('kemahasiswaan.notifications.index', ['filter' => 'pengajuan_kegiatan']) }}" class="notif-filter-btn {{ $filter === 'pengajuan_kegiatan' ? 'active' : '' }}">Pengajuan ({{ $counts['pengajuan_kegiatan'] }})</a>
            <a href="{{ route('kemahasiswaan.notifications.index', ['filter' => 'revisi_kegiatan']) }}" class="notif-filter-btn {{ $filter === 'revisi_kegiatan' ? 'active' : '' }}">Revisi ({{ $counts['revisi_kegiatan'] }})</a>
            <a href="{{ route('kemahasiswaan.notifications.index', ['filter' => 'laporan_masuk']) }}" class="notif-filter-btn {{ $filter === 'laporan_masuk' ? 'active' : '' }}">Laporan ({{ $counts['laporan_masuk'] }})</a>
            <a href="{{ route('kemahasiswaan.notifications.index', ['filter' => 'informasi_penting']) }}" class="notif-filter-btn {{ $filter === 'informasi_penting' ? 'active' : '' }}">Info Penting ({{ $counts['informasi_penting'] }})</a>
        </div>
        @if($unreadCount > 0)
        <form action="{{ route('notifications.read-all') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="notif-mark-all-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>

    <div class="notif-list">
        @forelse($filteredNotifications as $notif)
            @php
                $isRead = !is_null($notif->read_at);
                $icon = $notif->data['icon'] ?? 'info';
                $title = $notif->data['title'] ?? 'Notifikasi';
                $message = $notif->data['message'] ?? '';
                $time = $notif->created_at->diffForHumans();
                $actionUrl = $notif->data['action_url'] ?? null;
            @endphp
            <div class="notif-card {{ !$isRead ? 'unread' : '' }}">
                <div class="notif-icon-box {{ $icon }}">
                    @if($icon === 'document')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                    @elseif($icon === 'edit')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    @elseif($icon === 'report')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M9 15v-4"></path><path d="M15 15v-2"></path><path d="M12 15v-6"></path></svg>
                    @elseif($icon === 'message')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    @elseif($icon === 'users')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                    @endif
                </div>
                <div class="notif-body">
                    <h4 class="notif-title">{{ $title }}</h4>
                    <p class="notif-desc">{{ $message }}</p>
                    <div class="notif-meta">
                        <span class="notif-time">{{ $time }}</span>
                        <div class="notif-actions">
                            @if($actionUrl)
                                <a href="{{ $actionUrl }}" class="notif-action-btn primary">Lihat</a>
                            @endif
                            @if(!$isRead)
                            <form action="{{ route('kemahasiswaan.notifications.read', $notif->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="notif-action-btn primary">Tandai Dibaca</button>
                            </form>
                            @endif
                            <form action="{{ route('kemahasiswaan.notifications.destroy', $notif->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="notif-action-btn danger" onclick="return confirm('Hapus notifikasi ini?')">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="notif-empty">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <h3>Tidak Ada Notifikasi</h3>
                <p>Belum ada notifikasi untuk filter ini.</p>
            </div>
        @endforelse
    </div>

    @if(method_exists($filteredNotifications, 'links'))
        <div style="margin-top: 24px;">
            {{ $filteredNotifications->links() }}
        </div>
    @endif
</div>
@endsection
