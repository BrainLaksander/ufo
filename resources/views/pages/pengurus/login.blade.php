@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/pengurus.css') }}">
@endpush

@section('content')
  <div class="pengurus-login-wrap">
    <div class="pengurus-login-card">
      <div class="login-brand"> UFO</div>
      <h2 class="login-title">Login Pengurus</h2>

      <form id="pengurus-login-form">
        <label class="form-label">Email</label>
        <input id="login-email" class="form-input" type="email" placeholder="pengurus@contoh.test" required />

        <label class="form-label">Password</label>
        <input id="login-password" class="form-input" type="password" placeholder="••••••••" required />

        <button type="submit" class="btn-primary">Login</button>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('pengurus-login-form').addEventListener('submit', function(e){
      e.preventDefault();
      // dummy redirect
      window.location.href = '/pengurus/dashboard';
    });
  </script>
@endsection
