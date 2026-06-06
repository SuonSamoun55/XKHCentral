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
<!-- View User Modal -->
<div class="modal fade" id="userViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content view-user-modal-content">
            <div class="modal-header view-user-modal-header">
                <h5 class="modal-title view-user-modal-title">
                    <i class="bi bi-person-circle" style="margin-right: 8px; color: #18bfd0;"></i>
                    User Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body view-user-modal-body" id="modalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/AdminViews/Layouts/UserinfoView/create.css') }}">

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
