<!-- <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-user-modal">

            <form method="POST" id="userForm" enctype="multipart/form-data">
                @csrf
                <div id="methodBox"></div>

                <div class="modal-header custom-modal-header">
                    <h5 id="modalTitle" class="modal-title">Connect BC Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

<link rel="stylesheet" href="{{ asset('css/views/ManagementSystemViews/AdminViews/Layouts/UserinfoView/UserEdit.css') }}"> -->
