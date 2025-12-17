@extends('layouts.app')

@section('title', 'Login')

@section('content')
<h2>Login Sistem UFO</h2>

<form>
    <label>Email</label><br>
    <input type="email"><br><br>

    <label>Password</label><br>
    <input type="password"><br><br>

    <button type="submit">Login</button>
</form>
@endsection
