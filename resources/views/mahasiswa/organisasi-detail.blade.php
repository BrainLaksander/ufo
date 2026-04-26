@extends('layouts.public.mahasiswa')

@section('title', ($org['name'] ?? '') . ' - UFO')

@section('content')
@php
    $ui = $pageContent ?? [];
@endphp
<section class="figma-page-container py-3">
    <a href="{{ route('mahasiswa.organisasi.index') }}" class="figma-link-back">
        <i class="bi bi-arrow-left"></i>
        {{ $ui['back_to_list'] ?? '' }}
    </a>

    <article class="figma-org-detail-hero">
        <img src="{{ $org['banner'] ?? '' }}" alt="{{ $org['name'] ?? '' }}">
        <div class="figma-org-detail-hero-content">
            <div class="figma-org-logo">
                @if(!empty($org['logo']))
                    <img src="{{ $org['logo'] }}" alt="Logo {{ $org['name'] ?? '' }}" class="img-fluid rounded-circle">
                @else
                    {{ strtoupper($org['logo_text'] ?? '') }}
                @endif
            </div>
            <div>
                <h1>{{ $org['name'] ?? '' }}</h1>
                <p>{{ $org['tagline'] ?? '' }}</p>
            </div>
        </div>
    </article>

    <div class="figma-org-members-badge">
        <i class="bi bi-people"></i>
        {{ $org['active_members'] ?? 0 }} {{ $ui['active_members_label'] ?? 'Anggota Aktif' }}
    </div>

    <div class="figma-org-action-grid">
        <button type="button" class="figma-btn-primary" data-bs-toggle="modal" data-bs-target="#contactModal">
            <i class="bi bi-chat-dots"></i>
            {{ $ui['contact_button'] ?? '' }}
        </button>

        <a href="{{ route('mahasiswa.event', ['org' => $org['id'] ?? 0]) }}" class="figma-btn-secondary">
            <i class="bi bi-calendar-event"></i>
            {{ $ui['org_events_button'] ?? '' }}
        </a>

        <a href="{{ route('mahasiswa.organisasi.daftar', ['id' => $org['id'] ?? 0]) }}" class="figma-btn-danger">
            <i class="bi bi-pencil-square"></i>
            {{ $ui['register_button'] ?? '' }}
        </a>
    </div>

    <article class="figma-section">
        <h2>{{ $ui['vision_title'] ?? '' }}</h2>
        <p>{{ $org['visi'] ?? '' }}</p>

        <h2 class="mt-3">{{ $ui['mission_title'] ?? '' }}</h2>
        <ul>
            @foreach(($org['misi'] ?? []) as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    </article>

    <article class="figma-highlight">
        <h2 class="h4 mb-2">{{ $ui['culture_title'] ?? '' }}</h2>
        <p class="mb-0">{{ $org['culture'] ?? '' }}</p>
    </article>

    <article class="figma-section">
        <h2>{{ $ui['programs_title'] ?? '' }}</h2>
        <div class="figma-program-grid" id="program-grid">
            @foreach(($org['programs'] ?? []) as $index => $program)
                <button type="button" class="figma-program-btn" data-program-index="{{ $index }}">
                    <strong>{{ $program['name'] ?? '' }}</strong>
                </button>
            @endforeach
        </div>
    </article>

    <article class="figma-section">
        <h2>{{ $ui['history_title'] ?? '' }}</h2>
        <div class="figma-event-list">
            @foreach(($org['events'] ?? []) as $event)
                <a href="{{ route('mahasiswa.organisasi.event.detail', ['orgId' => $org['id'] ?? 0, 'eventId' => $event['id'] ?? '']) }}" class="figma-event-item">
                    <div>
                        <strong>{{ $event['name'] ?? '' }}</strong>
                        <br>
                        <small>{{ $event['date'] ?? '' }}</small>
                    </div>
                    <i class="bi bi-calendar-event"></i>
                </a>
            @endforeach
        </div>
    </article>

    <article class="figma-section">
        <h2>{{ $ui['structure_title'] ?? '' }}</h2>
        <div class="figma-structure-grid">
            @foreach(($org['structure'] ?? []) as $member)
                <div class="figma-structure-item">
                    <strong>{{ $member['position'] ?? '' }}</strong>
                    <p class="mb-0">{{ $member['name'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </article>
</section>

<div class="modal fade" id="programModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="programModalTitle">{{ $ui['program_modal_title'] ?? '' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="programModalBody"></div>
        </div>
    </div>
</div>

<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $ui['contact_modal_title_prefix'] ?? '' }} {{ $org['name'] ?? '' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="figma-org-contact-grid">
                    @if(!empty($org['social_media']['instagram']))
                        <a href="{{ $org['social_media']['instagram'] }}" target="_blank" rel="noopener" class="figma-org-contact-link instagram">
                            <i class="bi bi-instagram"></i>
                            {{ $ui['contact_instagram_label'] ?? '' }}
                        </a>
                    @endif

                    @if(!empty($org['social_media']['whatsapp']))
                        <a href="{{ $org['social_media']['whatsapp'] }}" target="_blank" rel="noopener" class="figma-org-contact-link whatsapp">
                            <i class="bi bi-whatsapp"></i>
                            {{ $ui['contact_whatsapp_label'] ?? '' }}
                        </a>
                    @endif

                    @if(!empty($org['social_media']['email']))
                        <a href="{{ $org['social_media']['email'] }}" class="figma-org-contact-link email">
                            <i class="bi bi-envelope"></i>
                            {{ $ui['contact_email_label'] ?? '' }}
                        </a>
                    @endif

                    @if(!empty($org['social_media']['website']))
                        <a href="{{ $org['social_media']['website'] }}" target="_blank" rel="noopener" class="figma-org-contact-link website">
                            <i class="bi bi-globe2"></i>
                            {{ $ui['contact_website_label'] ?? '' }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var programs = @json($org['programs'] ?? []);
    var ui = @json($ui);
    var buttons = Array.from(document.querySelectorAll('[data-program-index]'));
    var modalEl = document.getElementById('programModal');

    if (!modalEl || buttons.length === 0) {
        return;
    }

    var modal = new bootstrap.Modal(modalEl);
    var titleEl = document.getElementById('programModalTitle');
    var bodyEl = document.getElementById('programModalBody');

    function asList(values) {
        if (!Array.isArray(values) || values.length === 0) {
            return '<p class="mb-0">' + (ui.program_empty_activities || '') + '</p>';
        }

        return '<ul>' + values.map(function (item) {
            return '<li>' + item + '</li>';
        }).join('') + '</ul>';
    }

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            var index = Number(button.getAttribute('data-program-index') || -1);
            var program = programs[index];
            if (!program) {
                return;
            }

            titleEl.textContent = program.name || (ui.program_modal_title || '');
            bodyEl.innerHTML = `
                <p><strong>${ui.program_goal_label || ''}</strong> ${program.goal || ''}</p>
                <h6>${ui.program_activities_label || ''}</h6>
                ${asList(program.activities || [])}
                <div class="figma-register-alert mt-3 mb-3">
                    <strong>${ui.program_period_label || ''}</strong> ${program.period || ''}
                </div>
                <p class="mb-0"><strong>${ui.program_impact_label || ''}</strong> ${program.impact || ''}</p>
            `;

            modal.show();
        });
    });
})();
</script>
@endpush
