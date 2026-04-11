<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-user-modal">

            <form method="POST" id="userForm" enctype="multipart/form-data">
                @csrf
                <div id="methodBox"></div>

                <div class="modal-header custom-modal-header">
                    <h5 id="modalTitle" class="modal-title">Connect BC Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body custom-modal-body">

                    <div class="profile-top-wrap">
                        <div class="profile-image-box">
                            <div class="profile-image-circle">
                                <img id="avatarPreview" src="" alt="User" class="profile-preview-img" style="display:none;">
                                <div id="avatarText" class="profile-fallback-text">U</div>
                            </div>

                            <label for="profileImage" class="upload-image-btn" title="Upload image">
                                <i class="bi bi-camera-fill"></i>
                            </label>

                            <input type="file" name="profile_image" id="profileImage" accept="image/*" hidden>
                        </div>
                    </div>

                    <div class="user-info-grid">
                        <div class="info-row">
                            <label class="info-label">Customer BC ID:</label>
                            <div class="info-value" id="modalBcNo">-</div>
                        </div>

                        <div class="info-row">
                            <label class="info-label">Full Name:</label>
                            <div class="info-value" id="modalName">-</div>
                        </div>

                        <div class="info-row">
                            <label class="info-label">Email:</label>
                            <div class="info-value" id="modalEmail">-</div>
                        </div>

                        <div class="info-row">
                            <label class="info-label">Phone:</label>
                            <div class="info-value" id="modalPhone">-</div>
                        </div>
                    </div>

                    <div class="form-section mt-3">
                        <div class="mb-3">
                            <label class="form-label custom-label">Role:</label>
                            <select name="role" id="role" class="form-select custom-input" required>
                                <option value="">Select Role</option>
                                <option value="customer">Customer</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <div class="mb-3" id="oldPasswordGroup" style="display:none;">
                            <label class="form-label custom-label">Old Password:</label>
                            <input type="password" name="old_password" id="oldPassword" class="form-control custom-input">
                        </div>

                        <div class="mb-3">
                            <label class="form-label custom-label">Password:</label>
                            <input type="password" name="password" id="password" class="form-control custom-input">
                        </div>

                        <div class="mb-3">
                            <label class="form-label custom-label">Confirm Password:</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control custom-input">
                        </div>
                    </div>
                </div>

                <div class="modal-footer custom-modal-footer">
                    <button type="button" class="btn modal-cancel-btn" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn modal-save-btn" id="submitBtn">Connect</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="userViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
    .custom-user-modal{
        border-radius:10px;
        overflow:hidden;
        border:1px solid #d9e2ec;
    }

    .custom-modal-header{
        border-bottom:none;
        padding:14px 18px 8px;
    }

    .custom-modal-header .modal-title{
        font-size:16px;
        font-weight:700;
        color:#10b8c7;
    }

    .custom-modal-body{
        padding:8px 22px 18px;
    }

    .profile-top-wrap{
        display:flex;
        justify-content:flex-start;
        margin-bottom:14px;
    }

    .profile-image-box{
        position:relative;
        width:78px;
        height:78px;
    }

    .profile-image-circle{
        width:78px;
        height:78px;
        border-radius:50%;
        overflow:hidden;
        border:1px solid #d6dde5;
        background:#eef2f7;
        display:flex;
        align-items:center;
        justify-content:center;
    }

    .profile-preview-img{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
    }

    .profile-fallback-text{
        font-size:24px;
        font-weight:700;
        color:#475569;
    }

    .upload-image-btn{
        position:absolute;
        right:-2px;
        bottom:-2px;
        width:28px;
        height:28px;
        border-radius:50%;
        background:#10b8c7;
        color:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        text-decoration:none;
        cursor:pointer;
        border:2px solid #fff;
        box-shadow:0 2px 8px rgba(0,0,0,0.12);
        transition:0.2s ease;
    }

    .upload-image-btn:hover{
        background:#0aa2b0;
        color:#fff;
    }

    .upload-image-btn i{
        font-size:12px;
    }

    .user-info-grid{
        display:flex;
        flex-direction:column;
        gap:8px;
    }

    .info-row{
        display:grid;
        grid-template-columns:120px 1fr;
        align-items:center;
        gap:8px;
    }

    .info-label{
        font-size:13px;
        font-weight:500;
        color:#1e293b;
        margin:0;
    }

    .info-value{
        font-size:13px;
        color:#334155;
        word-break:break-word;
    }

    .custom-label{
        font-size:13px;
        font-weight:500;
        color:#1e293b;
        margin-bottom:6px;
    }

    .custom-input{
        height:36px;
        font-size:13px;
        border:1px solid #cfd8e3;
        border-radius:4px;
        box-shadow:none !important;
    }

    .custom-input:focus{
        border-color:#10b8c7;
    }

    .custom-modal-footer{
        border-top:none;
        justify-content:center;
        gap:28px;
        padding:18px 22px 22px;
    }

    .modal-cancel-btn,
    .modal-save-btn{
        min-width:104px;
        height:38px;
        border:none;
        border-radius:4px;
        font-size:13px;
        font-weight:700;
        color:#fff;
    }

    .modal-cancel-btn{
        background:#ff6464;
    }

    .modal-cancel-btn:hover{
        background:#f05151;
        color:#fff;
    }

    .modal-save-btn{
        background:#10b8c7;
    }

    .modal-save-btn:hover{
        background:#0aa7b6;
        color:#fff;
    }
</style>

<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.open-user-modal');
    if (!btn) return;

    const mode = btn.dataset.mode || 'connect';
    const id = btn.dataset.id || '';
    const name = btn.dataset.name || 'User';
    const email = btn.dataset.email || '-';
    const phone = btn.dataset.phone || '-';
    const bcno = btn.dataset.bcno || '-';
    const role = btn.dataset.role || '';
    const imageUrl = btn.dataset.imageUrl || '';

    const modalTitle = document.getElementById('modalTitle');
    const submitBtn = document.getElementById('submitBtn');
    const userForm = document.getElementById('userForm');
    const methodBox = document.getElementById('methodBox');
    const oldPasswordGroup = document.getElementById('oldPasswordGroup');
    const oldPassword = document.getElementById('oldPassword');

    const modalName = document.getElementById('modalName');
    const modalEmail = document.getElementById('modalEmail');
    const modalPhone = document.getElementById('modalPhone');
    const modalBcNo = document.getElementById('modalBcNo');

    const avatarPreview = document.getElementById('avatarPreview');
    const avatarText = document.getElementById('avatarText');
    const profileImage = document.getElementById('profileImage');

    const roleInput = document.getElementById('role');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    modalName.textContent = name;
    modalEmail.textContent = email;
    modalPhone.textContent = phone;
    modalBcNo.textContent = bcno;

    avatarText.textContent = (name.trim().charAt(0) || 'U').toUpperCase();

    profileImage.value = '';
    password.value = '';
    passwordConfirmation.value = '';
    oldPassword.value = '';
    methodBox.innerHTML = '';

    if (imageUrl) {
        avatarPreview.src = imageUrl;
        avatarPreview.style.display = 'block';
        avatarText.style.display = 'none';
    } else {
        avatarPreview.removeAttribute('src');
        avatarPreview.style.display = 'none';
        avatarText.style.display = 'flex';
    }

    avatarPreview.onerror = function () {
        avatarPreview.style.display = 'none';
        avatarText.style.display = 'flex';
    };

    if (mode === 'edit') {
        modalTitle.textContent = 'Edit User';
        submitBtn.textContent = 'Update';
        userForm.action = '/users/update/' + id;
        methodBox.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        roleInput.value = role;
        oldPasswordGroup.style.display = 'block';
        oldPassword.setAttribute('required', 'required');
        password.removeAttribute('required');
        passwordConfirmation.removeAttribute('required');
    } else {
        modalTitle.textContent = 'Connect BC Customer';
        submitBtn.textContent = 'Connect';
        userForm.action = '/users/store/' + id;
        roleInput.value = '';
        oldPasswordGroup.style.display = 'none';
        oldPassword.removeAttribute('required');
        password.setAttribute('required', 'required');
        passwordConfirmation.setAttribute('required', 'required');
    }
});

document.getElementById('profileImage').addEventListener('change', function (e) {
    const file = e.target.files[0];
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarText = document.getElementById('avatarText');

    if (file) {
        avatarPreview.src = URL.createObjectURL(file);
        avatarPreview.style.display = 'block';
        avatarText.style.display = 'none';
    }
});
</script>
