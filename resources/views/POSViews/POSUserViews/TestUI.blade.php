@extends('ManagementSystemViews.UserViews.Layouts.app')

@section('title', 'Notification')

@section('content')

<div class="notif-page">

    <!-- PAGE HEADER -->
    <div class="notif-top">
        <h2 class="title">Notification</h2>

        <div class="top-actions">
            <span class="inbox-label">
                <i class="bi bi-chat-left"></i> Inbox
            </span>

            <button class="btn-send">
                <i class="bi bi-send"></i> Send Message
            </button>
        </div>
    </div>

    <!-- MAIN LAYOUT -->
    <div class="notif-layout">

        <!-- LEFT PANEL -->
     <!-- LEFT PANEL -->
<div class="notif-left">

    <input type="text"
        class="search-input"
        placeholder="Search username...">

    <p class="section-title">CONTACT</p>

    <div class="contact-list">

        @forelse($contactList as $index => $contact)

            <div class="contact {{ $index == 0 ? 'active' : '' }}"

                data-name="{{ $contact->name }}"
                data-avatar="{{ $contact->chat_avatar }}"
                data-phone="{{ $contact->phone ?? '+1 (605) 655 2777' }}"
                data-email="{{ $contact->email ?? 'sample@email.com' }}">

                {{-- AVATAR --}}
                <img src="{{ $contact->chat_avatar }}"
                    onerror="this.src='{{ asset('images/pos/Rectangle 2.png') }}'"
                    alt="{{ $contact->name }}">

                {{-- INFO --}}
                <div>

                    <strong>{{ $contact->name }}</strong>

                    <small>last seen recently</small>

                </div>

                {{-- BADGE --}}
                @if ($contact->unread_count > 0)

                    <span class="badge">

                        {{ $contact->unread_count }}

                    </span>

                @endif

            </div>

        @empty

            <div class="ac-empty-text">

                No contacts available

            </div>

        @endforelse

    </div>

</div>



<!-- RIGHT PANEL -->
<div class="notif-right">

    <div class="profile">

        <img src="{{ $contactList->first()->chat_avatar ?? asset('images/pos/Rectangle 2.png') }}"
            class="profile-img"
            id="profileImage">

        <h4 id="profileName">
            {{ $contactList->first()->name ?? 'No User' }}
        </h4>

    </div>

    <div class="settings">

        <div class="row-item">

            <span>
                <i class="bi bi-bell"></i>
                Notifications
            </span>

            <strong>No</strong>

        </div>

        <div class="row-item">

            <span>
                <i class="bi bi-download"></i>
                Save to Downloads
            </span>

            <strong>Default</strong>

        </div>

        <div class="row-item">

            <span>
                <i class="bi bi-person"></i>
                Contact Details
            </span>

        </div>

        <div class="details">

            <p>

                <strong>Phone Number</strong><br>

                <span id="profilePhone">
                    {{ $contactList->first()->phone ?? '+1 (605) 655 2777' }}
                </span>

            </p>

            <p>

                <strong>Email</strong><br>

                <span id="profileEmail">
                    {{ $contactList->first()->email ?? 'sample@email.com' }}
                </span>

            </p>

        </div>

    </div>

</div>

    </div>

</div>

<script>

    const contacts = document.querySelectorAll('.contact');

    const profileName = document.getElementById('profileName');
    const profileImage = document.getElementById('profileImage');
    const profilePhone = document.getElementById('profilePhone');
    const profileEmail = document.getElementById('profileEmail');

    contacts.forEach(contact => {

        contact.addEventListener('click', function () {

            // REMOVE ACTIVE
            contacts.forEach(item => {
                item.classList.remove('active');
            });

            // ACTIVE CURRENT
            this.classList.add('active');

            // GET DATA
            const name = this.getAttribute('data-name');
            const avatar = this.getAttribute('data-avatar');
            const phone = this.getAttribute('data-phone');
            const email = this.getAttribute('data-email');

            // UPDATE RIGHT PANEL
            profileName.innerText = name;
            profileImage.src = avatar;
            profilePhone.innerText = phone;
            profileEmail.innerText = email;

        });

    });

</script>
<style>

/* PAGE */
.notif-page {
    padding: 20px;
    height: 100vh;
    background: #f5f5f5;
    width: 100%;
}

/* HEADER */
.notif-top {
    margin-bottom: 20px;
}

.title {
    color: #26a5a8;
    font-weight: 700;
}

.top-actions {
    display: flex;
    align-items: center;
    gap: 62px;
    margin-top: 10px;
}

.btn-send {
    background: #1cbac2;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 20px;
}

/* MAIN GRID */
.notif-layout {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 20px;
}

/* LEFT PANEL */
.notif-left {
    background: #fff;
    padding: 15px;
    border-radius: 12px;
}

.search-input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
    margin-bottom: 15px;
}

.section-title {
    font-size: 12px;
    color: #999;
}

/* CONTACT ITEM */
.contact {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    border-radius: 10px;
    margin-top: 8px;
    cursor: pointer;
}

.contact img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.contact small {
    display: block;
    font-size: 12px;
    color: #777;
}

/* ACTIVE */
.contact.active {
    background: #1cbac2;
    color: white;
}

.contact.active small {
    color: white;
}

/* BADGE */
.badge {
    background: red;
    color: white;
    border-radius: 50%;
    padding: 3px 7px;
    margin-left: auto;
}

/* RIGHT PANEL */
.notif-right {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
}

/* TABS */
.tabs {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.tabs span {
    color: #777;
}

.tabs span.active {
    color: #1cbac2;
    border-bottom: 3px solid #1cbac2;
}

.badge-blue {
    background: #3b82f6;
    color: white;
    padding: 3px 7px;
    border-radius: 6px;
    font-size: 12px;
}

/* PROFILE */
.profile {
    text-align: center;
    margin-bottom: 25px;
}

.profile-img {
    width: 90px;
    border-radius: 50%;
}

/* SETTINGS */
.row-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.details p {
    margin-top: 10px;
}

</style>

@endsection