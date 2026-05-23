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
        <div class="notif-left">

            <input type="text" class="search-input" placeholder="Search username...">

            <p class="section-title">CONTACT</p>

            <div class="contact-list">

                <div class="contact active">
                    <img src="https://i.pravatar.cc/100">
                    <div>
                        <strong>Cody Fisher</strong>
                        <small>Last connect 8 minutes ago</small>
                    </div>
                    <span class="badge">3</span>
                </div>

                <div class="contact">
                    <img src="https://i.pravatar.cc/101">
                    <div>
                        <strong>Ralph Edwards</strong>
                        <small>Last connect 8 minutes ago</small>
                    </div>
                </div>

                <div class="contact">
                    <img src="https://i.pravatar.cc/102">
                    <div>
                        <strong>Esther Howard</strong>
                        <small>Last connect 8 minutes ago</small>
                    </div>
                </div>

            </div>

        </div>

        <!-- RIGHT PANEL -->
        <div class="notif-right">

            <div class="tabs">
                <span>Order Notification</span>
                <span class="active">User Contact</span>
                <span class="badge-blue">1 new</span>
                <span>Out of Stock Alert</span>
            </div>

            <div class="profile">

                <img src="https://i.pravatar.cc/120" class="profile-img">
                <h3>Cody Fisher</h3>

            </div>

            <div class="settings">

                <div class="row-item">
                    <span><i class="bi bi-bell"></i> Notifications</span>
                    <strong>No</strong>
                </div>

                <div class="row-item">
                    <span><i class="bi bi-download"></i> Save to Downloads</span>
                    <strong>Default</strong>
                </div>

                <div class="row-item">
                    <span><i class="bi bi-person"></i> Contact Details</span>
                </div>

                <div class="details">
                    <p><strong>Phone Number</strong><br>+1 (605) 655 2777</p>
                    <p><strong>Email</strong><br>sample@email.com</p>
                </div>

            </div>

        </div>

    </div>

</div>


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