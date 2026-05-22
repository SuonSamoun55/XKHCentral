@extends('ManagementSystemViews.UserViews.Layouts.app')

<div class="contact-page">

    <!-- Header -->
    <div class="contact-header">
        <a href="{{ route('user.notifications') }}" class="back-btn">←</a>
        <span>Contact</span>
    </div>

    <!-- Profile -->
    <div class="contact-profile">
       <img src="{{ asset('storage/' . $contact->chat_avatar) }}"
     class="profile-img"
     onerror="this.onerror=null;this.src='{{ asset('images/pos/Rectangle 2.png') }}';">

        <h2 class="contact-name">
            {{ $contact->name }}
            <span class="verified"></span>
        </h2>

        <a href="{{ route('user.chat.index', ['admin_id' => $contact->id]) }}"
           class="btn-message">
            ✎ New Message
        </a>
    </div>

    <!-- Info -->
    <div class="contact-info">

        <div class="info-row">
            <span>Location</span>
            <strong>{{ $contact->location ?? '' }}</strong>
        </div>

        <div class="info-row">
            <span>Birthday</span>
            <strong>{{ $contact->birthday ?? '' }}</strong>
        </div>

        <div class="info-row">
            <span>Phone Number</span>
            <strong>{{ $contact->phone ?? '' }}</strong>
        </div>

        <div class="info-row">
            <span>Email</span>
            <strong>{{ $contact->email ?? '' }}</strong>
        </div>

    </div>

</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        function checkScreenAndRedirect() {

            // when switch to desktop screen
            if (window.innerWidth >= 768) {

                // redirect to desktop notification page
                window.location.href = "/pos-system/notifications";
            }
        }

        // run when page loads
        checkScreenAndRedirect();

        // run again when screen size changes
        window.addEventListener("resize", checkScreenAndRedirect);

    });
</script>
<style>
    .app-shell,
    .sidebar,
    .sidebar-wrap{
        display: none !important;
    }
  body {
    margin: 0;
    background: #f4f6f8;
    font-family: Arial, sans-serif;
}

/* FULL WIDTH */
.contact-page {
    width: 100%;
    min-height: 100vh;
    background: #f4f6f8;
}

/* Header */
.contact-header {
    position: relative;
    text-align: center;
    padding: 16px;
    font-weight: 600;
    font-size: 18px;
    background: #ffffff;
}

.back-btn {
    position: absolute;
    left: 15px;
    top: 16px;
    font-size: 18px;
    text-decoration: none;
    color: #333;
}

/* Profile */
.contact-profile {
    text-align: center;
    padding: 30px 20px;
    background: #ffffff;
}

.profile-img {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
}

/* Name */
.contact-name {
    margin-top: 15px;
    font-size: 24px;
    font-weight: bold;
}

.verified {
    color: green;
    font-size: 18px;
    margin-left: 5px;
}

/* Button */
.btn-message {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 30px;
    background: linear-gradient(90deg, #44c0f0, #2a8df3);
    border-radius: 30px;
    color: white;
    text-decoration: none;
    font-size: 15px;
}

/* Info section */
.contact-info {
    margin-top: 10px;
    background: #ffffff;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 18px 20px;
    border-bottom: 1px solid #eee;
    font-size: 15px;
}

.info-row span {
    color: #777;
}

.info-row strong {
    color: #000;
    font-weight: 500;
}

</style>