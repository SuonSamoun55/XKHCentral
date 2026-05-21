@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Contact Detail')

@section('content')

<div class="contact-page">

    {{-- Header --}}
    <div class="contact-header">
        <a href="{{ url()->previous() }}" class="back-btn">
            <i class="bi bi-arrow-left"></i>
        </a>

        <h4>Contact</h4>
    </div>

    {{-- Profile --}}
    <div class="profile-section">

        <img class="profile-img"
            src="{{ $contact->profile_image ?? asset('images/pos/Rectangle 2.png') }}"
            onerror="this.src='{{ asset('images/pos/Rectangle 2.png') }}'">

        <div class="name">
            {{ $contact->name }}
        </div>

        <a href="{{ route('user.chat.index', ['admin_id' => $contact->id]) }}" class="msg-btn">
            ✏️ New Message
        </a>

    </div>

    {{-- Info --}}
    <div class="info-box">

        <div class="info-item">
            <span>📍 New York</span>
        </div>

        <div class="info-item">
            <strong>Birthday</strong>
            <span>{{ $contact->birthday ?? '-' }}</span>
        </div>

        <div class="info-item">
            <strong>Phone Number</strong>
            <span>{{ $contact->phone ?? '-' }}</span>
        </div>

        <div class="info-item">
            <strong>Email</strong>
            <span>{{ $contact->email ?? '-' }}</span>
        </div>

    </div>

</div>

<style>

.contact-page{
    background:#fff;
    min-height:100vh;
}

/* HEADER */
.contact-header{
    display:flex;
    align-items:center;
    padding:14px;
}

.back-btn{
    width:38px;
    height:38px;
    background:#eef2ff;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
    color:#000;
}

.contact-header h4{
    flex:1;
    text-align:center;
    margin:0;
}

/* PROFILE */
.profile-section{
    text-align:center;
    padding:20px;
}

.profile-img{
    width:110px;
    height:110px;
    border-radius:50%;
    object-fit:cover;
}

.name{
    font-size:22px;
    font-weight:600;
    margin-top:10px;
}

.msg-btn{
    display:inline-block;
    margin-top:10px;
    padding:10px 18px;
    background:#22d3ee;
    color:#fff;
    border-radius:20px;
    text-decoration:none;
}

/* INFO */
.info-box{
    margin-top:20px;
    padding:0 16px;
}

.info-item{
    padding:14px 0;
    border-bottom:1px solid #eee;
    display:flex;
    justify-content:space-between;
    font-size:14px;
}

</style>

@endsection