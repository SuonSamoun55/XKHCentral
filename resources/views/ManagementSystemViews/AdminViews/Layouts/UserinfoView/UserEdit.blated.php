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
a{
            text-decoration: none;
        }
.page-card{background:#fff;border-radius:12px;padding:15px;}
.connect-avatar{width:70px;height:70px;border-radius:50%;background:#e2e8f0;
display:flex;align-items:center;justify-content:center;font-weight:bold;font-size:22px;margin:auto;}
</style>
</head>

<body>

<div class="container mt-4">
<div class="page-card">

<h4 class="mb-3">User Management</h4>

<table class="table table-bordered">
<thead>
<tr>
<th>Name</th>
<th>Email</th>
<th>Customer No</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>
@foreach($customers as $customer)
<tr>
<td>{{ $customer->name }}</td>
<td>{{ $customer->email }}</td>
<td>{{ $customer->bc_customer_no }}</td>
<td>{{ $customer->connect_status }}</td>

<td>

@if($customer->connect_status !== 'connected')

<button class="btn btn-success open-user-modal"
data-bs-toggle="modal"
data-bs-target="#userModal"
data-mode="connect"
data-id="{{ $customer->id }}"
data-bcno="{{ $customer->bc_customer_no }}"
data-name="{{ $customer->name }}"
data-email="{{ $customer->email }}"
data-phone="{{ $customer->phone }}">
Connect
</button>

@else

<button class="btn btn-warning open-user-modal"
data-bs-toggle="modal"
data-bs-target="#userModal"
data-mode="edit"
data-id="{{ $customer->id }}"
data-role="{{ $customer->role }}"
data-bcno="{{ $customer->bc_customer_no }}"
data-name="{{ $customer->name }}"
data-email="{{ $customer->email }}"
data-phone="{{ $customer->phone }}">
Edit
</button>

@endif

</td>
</tr>
@endforeach
</tbody>
</table>

</div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="userModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">

<form method="POST" id="userForm">
@csrf
<div id="methodBox"></div>

<div class="modal-header">
<h5 id="modalTitle">Connect</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

<div class="text-center mb-3">
<div class="connect-avatar" id="avatar">U</div>
<h5 id="modalName"></h5>
<p id="modalEmail"></p>
</div>

<p><b>Customer No:</b> <span id="modalBcNo"></span></p>
<p><b>Phone:</b> <span id="modalPhone"></span></p>

{{-- OLD PASSWORD --}}
<div id="oldPasswordGroup" style="display:none;">
<label>Old Password</label>
<input type="password" name="old_password" id="oldPassword" class="form-control">
</div>

<label>Role</label>
<select name="role" id="role" class="form-control" required>
<option value="">Select</option>
<option value="customer">Customer</option>
<option value="admin">Admin</option>
</select>

<label class="mt-2">Password</label>
<input type="password" name="password" id="password" class="form-control">

<label class="mt-2">Confirm</label>
<input type="password" name="password_confirmation" id="password_confirmation" class="form-control">

</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button class="btn btn-primary" id="submitBtn">Save</button>
</div>

</form>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.querySelectorAll('.open-user-modal').forEach(btn => {
btn.addEventListener('click', function(){

let mode = this.dataset.mode;
let id = this.dataset.id;

document.getElementById('modalName').innerText = this.dataset.name;
document.getElementById('modalEmail').innerText = this.dataset.email;
document.getElementById('modalBcNo').innerText = this.dataset.bcno;
document.getElementById('modalPhone').innerText = this.dataset.phone;
document.getElementById('avatar').innerText = this.dataset.name.charAt(0);

document.getElementById('password').value='';
document.getElementById('password_confirmation').value='';
document.getElementById('oldPassword').value='';
document.getElementById('methodBox').innerHTML='';

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
</script>

</body>
</html>
