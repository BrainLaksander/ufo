@extends('layouts.pengurus')

@section('title', 'Buat Event')

@section('content')
<div class="page-header mb-4">
    <h1>Buat Event Baru</h1>
    <p class="page-subtitle">Buat event UKM dari data organisasi pengurus saat ini</p>
</div>

@if(session('success'))
    <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
@endif

@if(!($hasPengurusContext ?? false))
    <div class="alert alert-warning" role="alert">
        Akun pengurus belum terhubung ke data organisasi/member di database.
    </div>
@elseif(!($hasApprovedIzin ?? false))
    <div class="alert alert-warning" role="alert">
        Pembuatan event dikunci. Organisasi harus memiliki minimal satu izin kegiatan berstatus <strong>Disetujui</strong>.
    </div>
@else
    <div class="alert alert-success" role="alert">
        Izin kegiatan sudah disetujui. Anda dapat membuat event baru.
    </div>
@endif

@php $canCreateEvent = ($hasPengurusContext ?? false) && ($hasApprovedIzin ?? false); @endphp

<div class="form-org">
    <form method="POST" action="{{ route('portal.pengurus.events.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Event</label>
                    <input type="text" name="name" placeholder="Contoh: Workshop Web Development" required @disabled(!$canCreateEvent)>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Mulai</label>
                    <input type="datetime-local" name="start_date" required @disabled(!$canCreateEvent)>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Selesai (opsional)</label>
                    <input type="datetime-local" name="end_date" @disabled(!$canCreateEvent)>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" name="location" placeholder="Contoh: Aula Gedung A" required @disabled(!$canCreateEvent)>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Kuota Peserta</label>
                    <input type="number" name="quota" min="1" value="50" required @disabled(!$canCreateEvent)>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Status Event</label>
                    <select name="status" required @disabled(!$canCreateEvent)>
                        <option value="draft">Draft</option>
                        <option value="approved">Approved</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi Event</label>
            <textarea name="description" placeholder="Jelaskan tujuan, agenda, dan manfaat event..." required @disabled(!$canCreateEvent)></textarea>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn-primary-org" @disabled(!$canCreateEvent)>Simpan Event</button>
            <a href="{{ route('portal.pengurus.events') }}" class="btn-secondary-org" style="text-decoration: none;">Kembali</a>
        </div>
    </form>
</div>
@endsection
