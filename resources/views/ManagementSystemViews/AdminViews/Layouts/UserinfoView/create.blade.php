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
                                @foreach($roles as $roleOption)
                                    <option value="{{ $roleOption->name }}">{{ $roleOption->display_name ?? ucfirst($roleOption->name) }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Controls which admin pages this login can reach. Manage roles under Settings &rarr; Roles.</small>
                        </div>

                        <div class="mb-3 form-check form-switch" id="editPasswordToggleGroup" style="display:none;">
                            <input class="form-check-input" type="checkbox" role="switch" id="editPasswordToggle">
                            <label class="form-check-label custom-label" for="editPasswordToggle">Set a new password for this customer</label>
                        </div>

                        <div id="passwordFieldsGroup">
                            <div class="mb-3">
                                <label class="form-label custom-label" id="passwordFieldLabel">Password:</label>
                                <input type="password" name="password" id="password" class="form-control custom-input">
                            </div>

                            <div class="mb-3">
                                <label class="form-label custom-label">Confirm Password:</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control custom-input">
                            </div>
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

<link rel="stylesheet" href="{{ asset('/css/views/Management/userinfo/create.css') }}">

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
    const editPasswordToggleGroup = document.getElementById('editPasswordToggleGroup');
    const editPasswordToggle = document.getElementById('editPasswordToggle');
    const passwordFieldsGroup = document.getElementById('passwordFieldsGroup');
    const passwordFieldLabel = document.getElementById('passwordFieldLabel');

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

        // Edit mode: password is off by default (role-only change keeps
        // the customer's existing password) — the toggle reveals it.
        editPasswordToggleGroup.style.display = 'block';
        editPasswordToggle.checked = false;
        passwordFieldLabel.textContent = 'New Password for this customer:';
        passwordFieldsGroup.style.display = 'none';
        password.removeAttribute('required');
        passwordConfirmation.removeAttribute('required');
    } else {
        modalTitle.textContent = 'Connect BC Customer';
        submitBtn.textContent = 'Connect';
        userForm.action = '/users/store/' + id;
        roleInput.value = '';

        // Connect mode: always needs a password, no toggle involved.
        editPasswordToggleGroup.style.display = 'none';
        passwordFieldLabel.textContent = 'Password:';
        passwordFieldsGroup.style.display = 'block';
        password.setAttribute('required', 'required');
        passwordConfirmation.setAttribute('required', 'required');
    }
});

// Edit mode's "set a new password" toggle — shows/hides and (un)requires
// the password fields. Bound once here since the toggle element is a
// permanent part of the modal, not re-created per open-user-modal click.
document.getElementById('editPasswordToggle')?.addEventListener('change', function () {
    const passwordFieldsGroup = document.getElementById('passwordFieldsGroup');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    if (this.checked) {
        passwordFieldsGroup.style.display = 'block';
        password.setAttribute('required', 'required');
        passwordConfirmation.setAttribute('required', 'required');
    } else {
        passwordFieldsGroup.style.display = 'none';
        password.removeAttribute('required');
        passwordConfirmation.removeAttribute('required');
        password.value = '';
        passwordConfirmation.value = '';
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
