@extends('layouts.portal.pengurus')

@section('title', 'Kontak Organisasi')

@php
    $contacts = $contacts ?? [];
@endphp

@section('content')
<div class="ufo-kboard-page">
    <section class="ufo-kboard-section">
        <h1 class="ufo-kboard-heading">Kontak Organisasi</h1>
        <p class="ufo-kboard-lead">Hubungi pengurus atau pihak kampus untuk konsultasi dan bantuan.</p>
    </section>

    <section class="ufo-kboard-section">
        <div class="ufo-pg-contact-grid">
            @forelse($contacts as $contact)
                <?php
                    // normalize keys that controller provides (loadContactMiniCards)
                    $name = data_get($contact, 'name', data_get($contact, 'nama', ''));
                    $role = data_get($contact, 'role', data_get($contact, 'jabatan', ''));
                    $phone = data_get($contact, 'phone', data_get($contact, 'whatsapp', ''));
                    $email = data_get($contact, 'email', '');
                ?>

                <article class="ufo-pg-contact-card">
                    <div class="ufo-pg-contact-head">
                        <div class="ufo-pg-avatar">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <h3 class="ufo-kboard-item-title">{{ $name }}</h3>
                            <p class="ufo-kboard-item-meta">{{ $role }}</p>
                        </div>
                    </div>

                    <div class="ufo-pg-contact-actions">
                        <a href="{{ $phone }}" target="_blank" rel="noopener noreferrer" class="ufo-pg-contact-link wa">
                            <i class="bi bi-whatsapp"></i>
                            <div>
                                <p>WhatsApp</p>
                                <small>{{ $phone }}</small>
                            </div>
                            <span>→</span>
                        </a>

                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}" class="ufo-pg-contact-link call">
                            <i class="bi bi-telephone"></i>
                            <div>
                                <p>Telepon</p>
                                <small>{{ $phone }}</small>
                            </div>
                            <span>→</span>
                        </a>

                        <a href="mailto:{{ $email }}" class="ufo-pg-contact-link mail">
                            <i class="bi bi-envelope"></i>
                            <div>
                                <p>Email</p>
                                <small>{{ $email }}</small>
                            </div>
                            <span>→</span>
                        </a>
                    </div>
                </article>
            @empty
                <p class="ufo-kboard-item-meta mb-0">Belum ada kontak organisasi dari database.</p>
            @endforelse
        </div>
    </section>

</div>
@endsection
