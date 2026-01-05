@extends('layouts.app')

@section('title', 'Registration Successful')

@section('content')
<style>
    

    .success-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 80vh;
        text-align: center;
        position: relative;
    }

    .confetti {
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: url('/images/confetti.png'); /* optional confetti image */
        background-repeat: repeat;
        opacity: 0.2;
        pointer-events: none;
    }

    .card {
        background-color: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        max-width: 500px;
        width: 100%;
        z-index: 1;
    }

    .card h2 {
        color: #ff6f61;
        margin-bottom: 10px;
    }

    .card .profile-icon {
        font-size: 60px;
        color: #ff6f61;
        margin-bottom: 10px;
    }

    .card .info {
        font-size: 18px;
        margin-bottom: 5px;
    }

    .card .status {
        color: blue;
    }

    .notice {
        margin-top: 20px;
        font-size: 14px;
        color: blue;
    }

    .btn-done {
        margin-top: 30px;
        background-color: #CB3737;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-done:hover {
        background-color: #e65c50;
    }
</style>

<div class="success-container">
    <div class="confetti"></div>

    <div class="card">
        <div class="profile-icon">👤</div>
        <h2>Welcome to HASTA Vehicle Booking System!</h2>

        <div class="info"><strong>{{ $user->name }}</strong></div>
        <div class="status">Your Profile Has Been Created</div>
        <div class="info">E-mail: {{ $user->email }}</div>

        <div class="notice">
            Notice: You will be redirected to a login page.
        </div>

        <a href="{{ route('login') }}" class="btn-done">LOG IN</a>
    </div>
</div>
@endsection