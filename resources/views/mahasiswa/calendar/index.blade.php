@extends('layouts.app')

@section('title', 'Kalender Kegiatan Kampus')

@push('head')
<style>
    .calendar-page {
        padding: 30px;
        background-color: #f8fafc;
        min-height: calc(100vh - 60px);
    }
    .cal-header-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 24px;
    }
    .cal-header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .cal-header-top h1 {
        margin: 0;
        font-size: 20px;
        color: #1f2937;
    }
    .cal-header-top p {
        margin: 4px 0 0;
        font-size: 13px;
        color: #6b7280;
    }
    .cal-add-btn {
        background: #3b2063;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }
    .cal-filters {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }
    .cal-filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .cal-filter-group label {
        font-size: 12px;
        color: #4b5563;
        font-weight: 500;
    }
    .cal-filter-select, .cal-filter-search {
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 13px;
        color: #374151;
        background: white;
        outline: none;
        width: 100%;
    }
    .cal-search-wrap {
        position: relative;
    }
    .cal-search-wrap svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        color: #9ca3af;
    }
    .cal-search-wrap input {
        padding-left: 36px;
    }
    .cal-legend {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 12px;
        color: #4b5563;
        flex-wrap: wrap;
    }
    .cal-legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .cal-legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    .dot-pkm { background: #10b981; }
    .dot-kebudayaan { background: #f59e0b; }
    .dot-akademik { background: #3b82f6; }
    .dot-keagamaan { background: #8b5cf6; }
    .dot-minat { background: #ec4899; }
    .dot-kebangsaan { background: #ef4444; }
    .dot-libur { background: #eab308; }
    .dot-larangan { background: #991b1b; }

    /* Main Board */
    .cal-board {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
    }
    .cal-board-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .cal-nav-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .cal-nav-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #4b5563;
        display: flex;
        align-items: center;
        padding: 4px;
    }
    .cal-nav-title {
        font-size: 18px;
        font-weight: 600;
        color: #3b2063;
        margin: 0;
        min-width: 120px;
        text-align: center;
    }
    .cal-view-toggle {
        display: flex;
        background: #f1f5f9;
        border-radius: 6px;
        padding: 4px;
        gap: 4px;
    }
    .cal-view-btn {
        border: none;
        background: transparent;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .cal-view-btn.active {
        background: #3b2063;
        color: white;
    }

    /* Grid View */
    .cal-grid {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    .cal-grid th {
        border: 1px solid #e5e7eb;
        padding: 12px;
        font-size: 13px;
        font-weight: 500;
        color: #4b5563;
        text-align: center;
        background: #f8fafc;
    }
    .cal-grid td {
        border: 1px solid #e5e7eb;
        height: 120px;
        vertical-align: top;
        padding: 8px;
    }
    .cal-day-num {
        font-size: 13px;
        color: #4b5563;
        margin-bottom: 8px;
        display: block;
    }
    .cal-event-bar {
        font-size: 11px;
        padding: 4px 6px;
        border-radius: 4px;
        color: white;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .bg-pkm { background: #10b981; }
    .bg-kebudayaan { background: #f59e0b; color: #78350f; }
    .bg-akademik { background: #3b82f6; }
    .bg-keagamaan { background: #8b5cf6; }
    .bg-minat { background: #ec4899; }
    .bg-kebangsaan { background: #ef4444; }
    .bg-libur { background: #eab308; color: #713f12; }
    .bg-larangan { background: #991b1b; }

    /* List View */
    .cal-list {
        display: none;
        flex-direction: column;
        gap: 16px;
    }
    .cal-list-item {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .cal-list-info {
        display: flex;
        gap: 12px;
    }
    .cal-list-dot {
        margin-top: 6px;
    }
    .cal-list-details h3 {
        margin: 0 0 8px;
        font-size: 15px;
        color: #1f2937;
    }
    .cal-list-meta {
        font-size: 13px;
        color: #6b7280;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .cal-list-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .cal-list-badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 999px;
        font-weight: 500;
        color: white;
    }
    
    /* Pagination Styles */
    .cal-pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
    }
    .cal-page-btn {
        background: white;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .cal-page-btn:hover:not(:disabled) {
        background: #f1f5f9;
        color: #1f2937;
    }
    .cal-page-btn.active {
        background: #3b2063;
        color: white;
        border-color: #3b2063;
    }
    .cal-page-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Modal */
    .cal-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 100;
        opacity: 0;
        visibility: hidden;
        transition: 0.2s;
    }
    .cal-modal-backdrop.open {
        opacity: 1;
        visibility: visible;
    }
    .cal-modal {
        background: white;
        border-radius: 8px;
        width: 100%;
        max-width: 500px;
        padding: 24px;
        transform: translateY(20px);
        transition: 0.2s;
    }
    .cal-modal-backdrop.open .cal-modal {
        transform: translateY(0);
    }
    .cal-modal-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .cal-modal-head h2 {
        margin: 0;
        font-size: 18px;
        color: #1f2937;
    }
    .cal-modal-close {
        background: none;
        border: none;
        font-size: 20px;
        color: #6b7280;
        cursor: pointer;
    }
    .cal-form-group {
        margin-bottom: 16px;
    }
    .cal-form-group label {
        display: block;
        font-size: 13px;
        color: #1f2937;
        font-weight: 500;
        margin-bottom: 8px;
    }
    .cal-form-group input, .cal-form-group select, .cal-form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        font-size: 13px;
        outline: none;
        font-family: inherit;
    }
    .cal-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .cal-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
    }
    .cal-btn-cancel {
        background: white;
        border: 1px solid #e5e7eb;
        color: #374151;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
    }
    .cal-btn-save {
        background: #3b2063;
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="calendar-page">
    <div class="cal-header-card">
        <div class="cal-header-top">
            <div>
                <h1>Kalender Kegiatan Kampus</h1>
                <p>Kelola dan pantau semua kegiatan akademik & non-akademik</p>
            </div>
        </div>

        <div class="cal-filters">
            <div class="cal-filter-group">
                <label>Filter Kategori</label>
                <select class="cal-filter-select">
                    <option>Semua Kegiatan</option>
                    <option>Pelayanan/Sosial (PKM)</option>
                    <option>Kebudayaan</option>
                    <option>Akademik</option>
                    <option>Keagamaan/Kerohanian</option>
                    <option>Minat/Bakat</option>
                    <option>Kebangsaan</option>
                    <option>Libur</option>
                    <option>Tidak Boleh Berkegiatan</option>
                </select>
            </div>
            <div class="cal-filter-group">
                <label>Cari Kegiatan</label>
                <div class="cal-search-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" class="cal-filter-search" placeholder="Cari nama kegiatan...">
                </div>
            </div>
        </div>

        <div class="cal-legend">
            <span>Legend:</span>
            <div class="cal-legend-item"><div class="cal-legend-dot dot-pkm"></div> Pelayanan/Sosial (PKM)</div>
            <div class="cal-legend-item"><div class="cal-legend-dot dot-kebudayaan"></div> Kebudayaan</div>
            <div class="cal-legend-item"><div class="cal-legend-dot dot-akademik"></div> Akademik</div>
            <div class="cal-legend-item"><div class="cal-legend-dot dot-keagamaan"></div> Keagamaan/Kerohanian</div>
            <div class="cal-legend-item"><div class="cal-legend-dot dot-minat"></div> Minat/Bakat</div>
            <div class="cal-legend-item"><div class="cal-legend-dot dot-kebangsaan"></div> Kebangsaan</div>
            <div class="cal-legend-item"><div class="cal-legend-dot dot-libur"></div> Libur</div>
            <div class="cal-legend-item"><div class="cal-legend-dot dot-larangan"></div> Tidak Boleh Berkegiatan</div>
        </div>
    </div>

    <div class="cal-board">
        <div class="cal-board-nav">
            <div class="cal-nav-left">
                <a href="?year={{ $currentMonth == 1 ? $currentYear - 1 : $currentYear }}&month={{ $currentMonth == 1 ? 12 : $currentMonth - 1 }}" class="cal-nav-btn"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></a>
                <h3 class="cal-nav-title">{{ $monthName }} {{ $currentYear }}</h3>
                <a href="?year={{ $currentMonth == 12 ? $currentYear + 1 : $currentYear }}&month={{ $currentMonth == 12 ? 1 : $currentMonth + 1 }}" class="cal-nav-btn"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></a>
            </div>
            <div class="cal-view-toggle">
                <button class="cal-view-btn active" id="btnGrid" onclick="toggleView('grid')">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    Bulan
                </button>
                <button class="cal-view-btn" id="btnList" onclick="toggleView('list')">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    List
                </button>
            </div>
        </div>

        <table class="cal-grid" id="viewGrid">
            <thead>
                <tr>
                    <th>Minggu</th>
                    <th>Senin</th>
                    <th>Selasa</th>
                    <th>Rabu</th>
                    <th>Kamis</th>
                    <th>Jumat</th>
                    <th>Sabtu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($calendarGrid as $week)
                <tr>
                    @foreach($week as $day)
                        @if($day)
                            <td>
                                <span class="cal-day-num">{{ $day['day'] }}</span>
                                @foreach($day['events'] as $ce)
                                    @php
                                        $colorClass = 'bg-akademik';
                                        if($ce->category == 'Pelayanan/Sosial (PKM)') $colorClass = 'bg-pkm';
                                        if($ce->category == 'Kebudayaan') $colorClass = 'bg-kebudayaan';
                                        if($ce->category == 'Keagamaan/Kerohanian') $colorClass = 'bg-keagamaan';
                                        if($ce->category == 'Minat/Bakat') $colorClass = 'bg-minat';
                                        if($ce->category == 'Kebangsaan') $colorClass = 'bg-kebangsaan';
                                        if($ce->category == 'Libur' || str_contains($ce->category, 'Libur')) $colorClass = 'bg-libur';
                                        if($ce->category == 'Tidak Boleh Berkegiatan' || str_contains(strtolower($ce->category), 'tidak boleh')) $colorClass = 'bg-larangan';
                                    @endphp
                                    <div class="cal-event-bar {{ $colorClass }}" title="{{ $ce->title }}">{{ $ce->title }}</div>
                                @endforeach
                                @foreach($day['ukmEvents'] as $ue)
                                    @php
                                        $ueColorClass = 'bg-akademik';
                                        if($ue->category == 'Pelayanan/Sosial (PKM)') $ueColorClass = 'bg-pkm';
                                        if($ue->category == 'Kebudayaan') $ueColorClass = 'bg-kebudayaan';
                                        if($ue->category == 'Keagamaan/Kerohanian') $ueColorClass = 'bg-keagamaan';
                                        if($ue->category == 'Minat/Bakat') $ueColorClass = 'bg-minat';
                                        if($ue->category == 'Kebangsaan') $ueColorClass = 'bg-kebangsaan';
                                        if($ue->category == 'Libur' || str_contains($ue->category, 'Libur')) $ueColorClass = 'bg-libur';
                                        if($ue->category == 'Tidak Boleh Berkegiatan' || str_contains(strtolower($ue->category), 'tidak boleh')) $ueColorClass = 'bg-larangan';
                                    @endphp
                                    <div class="cal-event-bar {{ $ueColorClass }}" title="{{ $ue->title }}">{{ $ue->title }}</div>
                                @endforeach
                            </td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="cal-list" id="viewList">
            @foreach($events as $ce)
                @php
                    $colorDotClass = 'dot-akademik';
                    $colorBgClass = 'bg-akademik';
                    if($ce->category == 'Pelayanan/Sosial (PKM)') { $colorDotClass = 'dot-pkm'; $colorBgClass = 'bg-pkm'; }
                    if($ce->category == 'Kebudayaan') { $colorDotClass = 'dot-kebudayaan'; $colorBgClass = 'bg-kebudayaan'; }
                    if($ce->category == 'Keagamaan/Kerohanian') { $colorDotClass = 'dot-keagamaan'; $colorBgClass = 'bg-keagamaan'; }
                    if($ce->category == 'Minat/Bakat') { $colorDotClass = 'dot-minat'; $colorBgClass = 'bg-minat'; }
                    if($ce->category == 'Kebangsaan') { $colorDotClass = 'dot-kebangsaan'; $colorBgClass = 'bg-kebangsaan'; }
                    if($ce->category == 'Libur' || str_contains($ce->category, 'Libur')) { $colorDotClass = 'dot-libur'; $colorBgClass = 'bg-libur'; }
                    if($ce->category == 'Tidak Boleh Berkegiatan' || str_contains(strtolower($ce->category), 'tidak boleh')) { $colorDotClass = 'dot-larangan'; $colorBgClass = 'bg-larangan'; }
                @endphp
                <div class="cal-list-item">
                    <div class="cal-list-info">
                        <div class="cal-legend-dot {{ $colorDotClass }} cal-list-dot"></div>
                        <div class="cal-list-details">
                            <h3>{{ $ce->title }}</h3>
                            <div class="cal-list-meta">
                                <span><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> {{ \Carbon\Carbon::parse($ce->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($ce->end_date)->format('d M Y') }}</span>
                                @if($ce->location)
                                    <span><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1 1 18 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg> {{ $ce->location }}</span>
                                @endif
                                @if($ce->organizer)
                                    <span><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> {{ $ce->organizer }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="cal-list-badge {{ $colorBgClass }}">{{ $ce->category }}</div>
                </div>
            @endforeach
            @foreach($ukmEvents as $ue)
                <div class="cal-list-item">
                    <div class="cal-list-info">
                        <div class="cal-legend-dot dot-green cal-list-dot"></div>
                        <div class="cal-list-details">
                            <h3>{{ $ue->title }}</h3>
                            <div class="cal-list-meta">
                                <span><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> {{ $ue->start_at ? $ue->start_at->format('d M Y') : '-' }}</span>
                                @if($ue->location)
                                    <span><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1 1 18 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg> {{ $ue->location }}</span>
                                @endif
                                <span><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg> {{ $ue->organization->name ?? 'UKM' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="cal-list-badge bg-pkm">{{ $ue->category ?? 'Event UKM' }}</div>
                </div>
            @endforeach
            
            <div id="pagination-controls" class="cal-pagination">
                <!-- Buttons injected by JS -->
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.querySelector('input[name="start_date"]');
        const endDateInput = document.querySelector('input[name="end_date"]');
        
        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
                if (endDateInput.value && endDateInput.value < this.value) {
                    endDateInput.value = this.value;
                }
            });
        }
    });

    function toggleView(view) {
        const btnGrid = document.getElementById('btnGrid');
        const btnList = document.getElementById('btnList');
        const viewGrid = document.getElementById('viewGrid');
        const viewList = document.getElementById('viewList');
        
        if(view === 'grid') {
            btnGrid.classList.add('active');
            btnList.classList.remove('active');
            viewGrid.style.display = 'table';
            viewList.style.display = 'none';
        } else {
            btnList.classList.add('active');
            btnGrid.classList.remove('active');
            viewGrid.style.display = 'none';
            viewList.style.display = 'flex';
            renderPagination(); // Re-render pagination on view switch
        }
    }

    // Pagination Logic
    let currentPage = 1;
    const itemsPerPage = 9;
    
    function renderPagination() {
        const listContainer = document.getElementById('viewList');
        // Only select actual list items, excluding the pagination container itself
        const items = Array.from(listContainer.querySelectorAll('.cal-list-item'));
        
        if (items.length === 0) return;

        const totalPages = Math.ceil(items.length / itemsPerPage);
        
        // Hide all items first
        items.forEach(item => item.style.display = 'none');
        
        // Show only items for current page
        const startIdx = (currentPage - 1) * itemsPerPage;
        const endIdx = startIdx + itemsPerPage;
        
        items.slice(startIdx, endIdx).forEach(item => {
            item.style.display = 'flex';
        });

        // Render controls
        const controls = document.getElementById('pagination-controls');
        controls.innerHTML = '';
        
        if (totalPages > 1) {
            // Prev button
            const prevBtn = document.createElement('button');
            prevBtn.className = 'cal-page-btn';
            prevBtn.innerHTML = '‹';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => { if(currentPage > 1) { currentPage--; renderPagination(); window.scrollTo(0, 0); } };
            controls.appendChild(prevBtn);

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `cal-page-btn ${currentPage === i ? 'active' : ''}`;
                pageBtn.innerHTML = i;
                pageBtn.onclick = () => { currentPage = i; renderPagination(); window.scrollTo(0, 0); };
                controls.appendChild(pageBtn);
            }

            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.className = 'cal-page-btn';
            nextBtn.innerHTML = '›';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => { if(currentPage < totalPages) { currentPage++; renderPagination(); window.scrollTo(0, 0); } };
            controls.appendChild(nextBtn);
        }
    }

    // Initial render
    document.addEventListener('DOMContentLoaded', function() {
        renderPagination();
    });
</script>
@endpush
@endsection
