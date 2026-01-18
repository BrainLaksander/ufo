@extends('layouts.mahasiswa')

@section('title', 'Beranda Mahasiswa')

@section('content')
    <h2>Selamat datang mahasiswa Universitas Klabat</h2>
    <p>Selamat datang di sistem informasi organisasi mahasiswa (UFO).</p>

    <ul>
        <li><a href="{{ route('organisasi.index') }}">Daftar Organisasi</a></li>
        <li><a href="{{ route('kegiatan.index') }}">Kegiatan Organisasi</a></li>
        <li><a href="{{ route('login') }}">Login Pengurus</a></li>
    </ul>
@endsection
