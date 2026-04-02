    @include('ManagementSystemViews.AdminViews.Layouts.navbar')
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
    body{background:#f4f6f8;font-family:Arial;}
    .page-card{background:#fff;border-radius:12px;padding:15px;}
    .connect-avatar{width:70px;height:70px;border-radius:50%;background:#e2e8f0;
    display:flex;align-items:center;justify-content:center;font-weight:bold;font-size:22px;margin:auto;}
    </style>
    </head>

    <body>
        <div class="modal fade" id="userModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<form method="POST" id="userForm" enctype="multipart/form-data">
@csrf
<div id="methodBox"></div>

<div class="modal-header">
<h5 id="modalTitle">Connect</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

{{-- PROFILE --}}
<div class="text-center mb-3">
    <div style="width:80px;height:80px;border-radius:50%;overflow:hidden;margin:auto;border:1px solid #ddd;">
        <img id="avatarPreview" src="" style="width:100%;height:100%;object-fit:cover;display:none;">
        <div id="avatarText" style="line-height:80px;font-weight:bold;">U</div>
    </div>
    <h5 id="modalName" class="mt-2"></h5>
    <p id="modalEmail"></p>
</div>

<p><b>Customer No:</b> <span id="modalBcNo"></span></p>
<p><b>Phone:</b> <span id="modalPhone"></span></p>

{{-- OLD PASSWORD --}}
<div id="oldPasswordGroup" style="display:none;">
<label>Old Password</label>
<input type="password" name="old_password" id="oldPassword" class="form-control">
</div>

{{-- ROLE --}}
<label class="mt-2">Role</label>
<select name="role" id="role" class="form-control" required>
<option value="">Select</option>
<option value="customer">Customer</option>
<option value="admin">Admin</option>
</select>

{{-- IMAGE UPLOAD --}}
<label class="mt-3">Upload Profile Image</label>
<input type="file" name="profile_image" id="profileImage" class="form-control">

<label class="mt-2">OR Image URL</label>
<input type="text" name="profile_image_url" id="profileImageUrl" class="form-control" placeholder="https://...">

{{-- PASSWORD --}}
<label class="mt-2">Password</label>
<input type="password" name="password" id="password" class="form-control">

<label class="mt-2">Confirm Password</label>
<input type="password" name="password_confirmation" id="password_confirmation" class="form-control">

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-primary" id="submitBtn">Save</button>
</div>

</form>

</div>
</div>
</div>

<script>
document.querySelectorAll('.open-user-modal').forEach(btn => {
btn.addEventListener('click', function(){

let mode = this.dataset.mode;
let id = this.dataset.id;
let name = this.dataset.name || 'User';
let imageUrl = this.dataset.imageUrl || '';

document.getElementById('modalName').innerText = name;
document.getElementById('modalEmail').innerText = this.dataset.email || '';
document.getElementById('modalBcNo').innerText = this.dataset.bcno || '';
document.getElementById('modalPhone').innerText = this.dataset.phone || '';

document.getElementById('avatarText').innerText = name.charAt(0).toUpperCase();

document.getElementById('password').value='';
document.getElementById('password_confirmation').value='';
document.getElementById('oldPassword').value='';
document.getElementById('profileImage').value='';
document.getElementById('profileImageUrl').value=imageUrl;
document.getElementById('methodBox').innerHTML='';

let avatarPreview = document.getElementById('avatarPreview');
let avatarText = document.getElementById('avatarText');

if(imageUrl){
    avatarPreview.src = imageUrl;
    avatarPreview.style.display='block';
    avatarText.style.display='none';
}else{
    avatarPreview.style.display='none';
    avatarText.style.display='block';
}

if(mode === 'edit'){
document.getElementById('modalTitle').innerText='Edit User';
document.getElementById('submitBtn').innerText='Update';

document.getElementById('userForm').action='/users/update/'+id;
document.getElementById('methodBox').innerHTML='<input type="hidden" name="_method" value="PUT">';

document.getElementById('role').value=this.dataset.role;

document.getElementById('oldPasswordGroup').style.display='block';
document.getElementById('oldPassword').setAttribute('required','required');

}else{

document.getElementById('modalTitle').innerText='Connect';
document.getElementById('submitBtn').innerText='Connect';

document.getElementById('userForm').action='/users/store/'+id;

document.getElementById('role').value='';

document.getElementById('oldPasswordGroup').style.display='none';
document.getElementById('oldPassword').removeAttribute('required');
}
});
});

document.getElementById('profileImage').addEventListener('change', function(e){
let file = e.target.files[0];
let avatarPreview = document.getElementById('avatarPreview');
let avatarText = document.getElementById('avatarText');

if(file){
avatarPreview.src = URL.createObjectURL(file);
avatarPreview.style.display='block';
avatarText.style.display='none';
}
});
</script>
    </body>
    </html>
