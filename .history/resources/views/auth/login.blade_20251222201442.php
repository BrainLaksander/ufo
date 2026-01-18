@extends('layouts.app')

@section('title', 'Login')

@section('content')
<h2>Login Sistem UFO</h2>

@if ($errors->any())
    <div style="background:#fee; padding:0.5rem; border:1px solid #c00; margin-bottom:1rem;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('login.perform') }}">
    @csrf
    <div>
        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email') }}" required><br><br>
    </div>

    <div>
        <label>Password</label><br>
        <input type="password" name="password" required><br><br>
    </div>

    <button type="submit">Login</button>
</form>
@endsection
