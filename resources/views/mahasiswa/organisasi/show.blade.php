@extends('layouts.app')

@section('title', $organization->name . ' - UFO')

@section('content')
<div class="content" style="padding-bottom: 60px;">
    {{-- Top navigation --}}
    <div style="padding: 16px 24px;">
        <a href="{{ url('/') }}" style="display: inline-flex; align-items: center; gap: 8px; color: var(--purple); font-weight: 600; font-size: 14px; text-decoration: none;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali ke Daftar Organisasi
        </a>
    </div>

    {{-- Hero Banner --}}
    <div style="position: relative; width: 100%; height: 280px; background: linear-gradient(135deg, #6f3ba7 0%, #a476d1 50%, #f0b84c 100%);">
        @if($organization->banner_path)
            <img src="{{ Storage::url($organization->banner_path) }}" alt="{{ $organization->name }}" style="width: 100%; height: 100%; object-fit: cover;">
        @endif
        
        {{-- Banner Overlay Gradient --}}
        <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);"></div>
        
        {{-- Org Title Info on Banner --}}
        <div style="position: absolute; bottom: 0; left: 0; right: 0; padding: 24px 40px; display: flex; align-items: flex-end; gap: 20px;">
            <div style="width: 80px; height: 80px; border-radius: 50%; background: #fff; display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border: 4px solid #fff;">
                @if($organization->logo_path)
                    <img src="{{ Storage::url($organization->logo_path) }}" alt="{{ $organization->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <span style="font-size: 32px; font-weight: 800; color: var(--purple);">{{ strtoupper(substr($organization->name, 0, 1)) }}</span>
                @endif
            </div>
            <div style="color: #fff; padding-bottom: 4px;">
                <h1 style="margin: 0 0 4px 0; font-size: 28px; font-weight: 800;">{{ $organization->name }}</h1>
                @if($organization->motto)
                    <p style="margin: 0; font-size: 15px; opacity: 0.9;">{{ $organization->motto }}</p>
                @endif
            </div>
        </div>
    </div>

    <div style="padding: 24px 40px; max-width: 1200px; margin: 0 auto;">
        
        {{-- Action Bar --}}
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 32px; flex-wrap: wrap;">
            <div style="background: #facc15; color: #854d0e; padding: 8px 16px; border-radius: 999px; font-weight: 700; font-size: 14px; display: inline-flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                {{ $organization->member_count ?? 0 }} Anggota Aktif
            </div>
            
            <div style="flex: 1;"></div>
            
            <button onclick="document.getElementById('contactModal').style.display='flex'" style="background: var(--purple); color: #fff; border: none; padding: 12px 24px; border-radius: 999px; font-weight: 700; font-size: 15px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: transform 0.2s;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Hubungi Organisasi
            </button>
            <a href="{{ route('events.index') }}" style="background: #facc15; color: #111827; border: none; padding: 12px 24px; border-radius: 999px; font-weight: 700; font-size: 15px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: transform 0.2s;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                Lihat Event Organisasi
            </a>
            @if($organization->is_open_recruitment)
                <button onclick="document.getElementById('registerModal').style.display='flex'" style="background: #10b981; color: #fff; border: none; padding: 12px 24px; border-radius: 999px; font-weight: 700; font-size: 15px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: transform 0.2s;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Buka Pendaftaran (Oprec)
                </button>
            @else
                <button disabled style="background: #e5e7eb; color: #9ca3af; border: none; padding: 12px 24px; border-radius: 999px; font-weight: 700; font-size: 15px; cursor: not-allowed; display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Pendaftaran Ditutup
                </button>
            @endif
        </div>

        <div style="display: grid; gap: 24px;">
            
            {{-- Visi & Misi --}}
            <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 32px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                <h2 style="margin: 0 0 12px 0; color: var(--purple); font-size: 20px; font-weight: 800;">Visi</h2>
                <p style="margin: 0 0 24px 0; color: #4b5563; font-size: 15px; line-height: 1.6;">
                    {{ $organization->visi ?? 'Belum ada visi.' }}
                </p>
                
                <h2 style="margin: 0 0 12px 0; color: var(--purple); font-size: 20px; font-weight: 800;">Misi</h2>
                <div style="color: #4b5563; font-size: 15px; line-height: 1.6; white-space: pre-line;">
                    @if($organization->misi)
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach(explode("\n", $organization->misi) as $m)
                                @if(trim($m))
                                    <li style="margin-bottom: 8px;">{{ ltrim(trim($m), '-') }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        Belum ada misi.
                    @endif
                </div>
            </div>

            {{-- Budaya & Nilai --}}
            <div style="background: linear-gradient(135deg, #6f3ba7 0%, #a476d1 50%, #f0b84c 100%); border-radius: 16px; padding: 32px; color: #fff;">
                <h2 style="margin: 0 0 12px 0; font-size: 20px; font-weight: 800;">Budaya & Nilai</h2>
                <p style="margin: 0; font-size: 16px; opacity: 0.95; line-height: 1.6;">
                    {{ $organization->budaya_nilai ?? 'Belum ada data.' }}
                </p>
            </div>

            {{-- Program Kegiatan --}}
            <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 32px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                <h2 style="margin: 0 0 20px 0; color: var(--purple); font-size: 20px; font-weight: 800;">Program Kegiatan</h2>
                @if($organization->program_kegiatan)
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
                        @foreach(explode("\n", $organization->program_kegiatan) as $pk)
                            @if(trim($pk))
                                <div style="background: #f9fafb; padding: 16px; border-radius: 12px; display: flex; align-items: center; gap: 12px; border: 1px solid #f3f4f6;">
                                    <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--purple);"></div>
                                    <span style="color: #374151; font-size: 15px; font-weight: 600;">{{ ltrim(trim($pk), '-') }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p style="margin: 0; color: #6b7280; font-style: italic;">Belum ada program kegiatan.</p>
                @endif
            </div>

            {{-- Riwayat Event --}}
            <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 32px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                <h2 style="margin: 0 0 20px 0; color: var(--purple); font-size: 20px; font-weight: 800;">Riwayat Event</h2>
                <div style="display: grid; gap: 12px;">
                    @forelse($events as $event)
                        @php
                            $now = now();
                            $start = $event->start_at;
                            $end = $event->end_at;
                            if ($end && $now->gt($end)) {
                                $statusLabel = 'Selesai';
                                $statusBg = '#f3f4f6';
                                $statusColor = '#6b7280';
                                $borderColor = '#e5e7eb';
                            } elseif ($start && $now->gte($start) && (!$end || $now->lte($end))) {
                                $statusLabel = 'Sedang Berjalan';
                                $statusBg = '#dcfce7';
                                $statusColor = '#16a34a';
                                $borderColor = '#bbf7d0';
                            } else {
                                $statusLabel = 'Akan Datang';
                                $statusBg = '#ede9fe';
                                $statusColor = '#7c3aed';
                                $borderColor = '#ddd6fe';
                            }
                        @endphp
                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px; background: #f9fafb; border: 1px solid {{ $borderColor }}; border-radius: 12px; flex-wrap: wrap;">
                            <div style="display: flex; align-items: center; gap: 14px; min-width: 0; flex: 1;">
                                <div style="width: 44px; height: 44px; border-radius: 10px; background: linear-gradient(135deg, var(--purple), #a476d1); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 16px; flex-shrink: 0; overflow: hidden;">
                                    @if($event->poster_path)
                                        <img src="{{ Storage::url($event->poster_path) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        {{ strtoupper(substr($event->title, 0, 1)) }}
                                    @endif
                                </div>
                                <div style="min-width: 0;">
                                    <div style="font-weight: 700; color: #111827; font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $event->title }}</div>
                                    <div style="font-size: 13px; color: #6b7280; margin-top: 2px;">
                                        {{ optional($event->start_at)->translatedFormat('d M Y, H:i') }}
                                        @if($event->end_at && $event->start_at->toDateString() !== $event->end_at->toDateString())
                                            - {{ $event->end_at->translatedFormat('d M Y') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; flex-shrink: 0;">
                                <span style="background: #ede9fe; color: #5b21b6; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap;">{{ $event->category ?? 'Umum' }}</span>
                                <span style="background: {{ $statusBg }}; color: {{ $statusColor }}; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; white-space: nowrap; display: inline-flex; align-items: center; gap: 4px;">
                                    @if($statusLabel === 'Sedang Berjalan')
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $statusColor }}; display: inline-block; animation: pulse-dot 1.5s ease-in-out infinite;"></span>
                                    @endif
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p style="margin: 0; color: #6b7280; font-style: italic;">Belum ada riwayat event.</p>
                    @endforelse
                </div>
            </div>

            <style>
                @keyframes pulse-dot {
                    0%, 100% { opacity: 1; }
                    50% { opacity: 0.3; }
                }
            </style>

            {{-- Struktur Kepengurusan --}}
            <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; padding: 32px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                <h2 style="margin: 0 0 24px 0; color: var(--purple); font-size: 20px; font-weight: 800;">Struktur Kepengurusan</h2>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                    {{-- Ketua --}}
                    <div style="background: #f9fafb; border: 1px solid #f3f4f6; border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 16px;">
                        <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, var(--purple), #a476d1); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; font-weight: 800;">K</div>
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #4b5563; margin-bottom: 4px;">Ketua Umum</div>
                            <div style="font-size: 16px; font-weight: 800; color: #111827;">{{ $organization->ketua_name ?: '-' }}</div>
                        </div>
                    </div>

                    {{-- Sekretaris --}}
                    <div style="background: #f9fafb; border: 1px solid #f3f4f6; border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 16px;">
                        <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #2563eb, #60a5fa); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; font-weight: 800;">S</div>
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #4b5563; margin-bottom: 4px;">Sekretaris</div>
                            <div style="font-size: 16px; font-weight: 800; color: #111827;">{{ $organization->secretary_name ?? '-' }}</div>
                        </div>
                    </div>

                    {{-- Bendahara --}}
                    <div style="background: #f9fafb; border: 1px solid #f3f4f6; border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 16px;">
                        <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #ca8a04, #fbbf24); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; font-weight: 800;">B</div>
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #4b5563; margin-bottom: 4px;">Bendahara</div>
                            <div style="font-size: 16px; font-weight: 800; color: #111827;">{{ $organization->treasurer_name ?? '-' }}</div>
                        </div>
                    </div>

                    {{-- Pembina / Advisor --}}
                    @if($organization->advisor_name)
                    <div style="background: #f9fafb; border: 1px solid #f3f4f6; border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 16px;">
                        <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #059669, #34d399); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 24px; font-weight: 800;">P</div>
                        <div>
                            <div style="font-size: 13px; font-weight: 700; color: #4b5563; margin-bottom: 4px;">Pembina (Advisor)</div>
                            <div style="font-size: 16px; font-weight: 800; color: #111827;">{{ $organization->advisor_name }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Contact Modal --}}
<div id="contactModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 100; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);">
    <div style="background: #fff; width: 100%; max-width: 480px; border-radius: 20px; padding: 32px; position: relative; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <button type="button" onclick="document.getElementById('contactModal').style.display='none'" style="position: absolute; top: 16px; right: 16px; background: #f3f4f6; color: #4b5563; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 20px; cursor: pointer; display: grid; place-items: center; transition: background 0.2s;">&times;</button>
        
        <h2 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 800; color: var(--purple);">Hubungi {{ $organization->name }}</h2>
        <p style="margin: 0 0 24px 0; color: #6b7280; font-size: 15px;">Pilih platform untuk menghubungi organisasi</p>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            @if($organization->instagram)
                <a href="{{ str_starts_with($organization->instagram, 'http') ? $organization->instagram : 'https://' . $organization->instagram }}" target="_blank" style="background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: #fff; text-decoration: none; padding: 20px; border-radius: 16px; text-align: center; font-weight: 700; font-size: 15px; transition: transform 0.2s;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 8px; display: block;"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    Instagram
                </a>
            @else
                <div style="background: #f3f4f6; color: #9ca3af; padding: 20px; border-radius: 16px; text-align: center; font-weight: 700; font-size: 15px;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 8px; display: block; opacity: 0.5;"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    Instagram
                </div>
            @endif

            @if($organization->whatsapp)
                @php
                    $waClean = preg_replace('/[^0-9]/', '', $organization->whatsapp);
                    // Convert 08 to 628
                    if (str_starts_with($waClean, '0')) {
                        $waClean = '62' . substr($waClean, 1);
                    }
                @endphp
                <a href="{{ str_starts_with($organization->whatsapp, 'http') ? $organization->whatsapp : 'https://wa.me/' . $waClean }}" target="_blank" style="background: #25D366; color: #fff; text-decoration: none; padding: 20px; border-radius: 16px; text-align: center; font-weight: 700; font-size: 15px; transition: transform 0.2s;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 8px; display: block;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    WhatsApp
                </a>
            @else
                <div style="background: #f3f4f6; color: #9ca3af; padding: 20px; border-radius: 16px; text-align: center; font-weight: 700; font-size: 15px;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 8px; display: block; opacity: 0.5;"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    WhatsApp
                </div>
            @endif

            @if($organization->account_email)
                <a href="mailto:{{ $organization->account_email }}" style="background: #ef4444; color: #fff; text-decoration: none; padding: 20px; border-radius: 16px; text-align: center; font-weight: 700; font-size: 15px; transition: transform 0.2s;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 8px; display: block;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Email
                </a>
            @else
                <div style="background: #f3f4f6; color: #9ca3af; padding: 20px; border-radius: 16px; text-align: center; font-weight: 700; font-size: 15px;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 8px; display: block; opacity: 0.5;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    Email
                </div>
            @endif

            @if($organization->website)
                <a href="{{ str_starts_with($organization->website, 'http') ? $organization->website : 'https://'.$organization->website }}" target="_blank" style="background: #3b82f6; color: #fff; text-decoration: none; padding: 20px; border-radius: 16px; text-align: center; font-weight: 700; font-size: 15px; transition: transform 0.2s;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 8px; display: block;"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    Website
                </a>
            @else
                <div style="background: #f3f4f6; color: #9ca3af; padding: 20px; border-radius: 16px; text-align: center; font-weight: 700; font-size: 15px;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 8px; display: block; opacity: 0.5;"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    Website
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Registration Info Modal --}}
@if($organization->is_open_recruitment)
<div id="registerModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 100; align-items: center; justify-content: center; padding: 20px; backdrop-filter: blur(4px);">
    <div style="background: #fff; width: 100%; max-width: 480px; border-radius: 20px; padding: 32px; position: relative; box-shadow: 0 20px 40px rgba(0,0,0,0.2); text-align: left;">
        <button type="button" onclick="document.getElementById('registerModal').style.display='none'" style="position: absolute; top: 16px; right: 16px; background: #f3f4f6; color: #4b5563; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 20px; cursor: pointer; display: grid; place-items: center; transition: background 0.2s;">&times;</button>
        
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="width: 48px; height: 48px; background: #dcfce7; color: #16a34a; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
            <h2 style="margin: 0; font-size: 22px; font-weight: 800; color: #111827;">Pendaftaran Dibuka!</h2>
        </div>
        
        <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
            <h3 style="margin: 0 0 8px 0; font-size: 15px; color: #374151;">Persyaratan & Info:</h3>
            <div style="color: #4b5563; font-size: 14px; line-height: 1.6; white-space: pre-wrap;">{{ $organization->recruitment_req ?: 'Belum ada persyaratan khusus yang dicantumkan.' }}</div>
        </div>
        
        @if($organization->recruitment_link)
        <a href="{{ str_starts_with($organization->recruitment_link, 'http') ? $organization->recruitment_link : 'https://' . $organization->recruitment_link }}" target="_blank" style="display: block; text-align: center; width: 100%; background: #10b981; color: #fff; text-decoration: none; padding: 14px 24px; border-radius: 12px; font-weight: 700; font-size: 16px; cursor: pointer; transition: background 0.2s; margin-bottom: 12px;">
            Isi Formulir Pendaftaran
        </a>
        @endif
        
        <button type="button" onclick="document.getElementById('registerModal').style.display='none'; document.getElementById('contactModal').style.display='flex';" style="width: 100%; background: #f3f4f6; color: #4b5563; border: none; padding: 14px 24px; border-radius: 12px; font-weight: 700; font-size: 16px; cursor: pointer; transition: background 0.2s;">
            Tanya Pengurus Organisasi
        </button>
    </div>
</div>
@endif
@endsection
