@include('ManagementSystemViews.AdminViews.Layouts.navbar')

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>User List</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>

body{
    background:#eef2f7;
    font-family:system-ui;
}

/* ===== Layout ===== */

.app-layout{
    display:flex;
    gap:20px;
    padding:20px;
}

.app-content{
    flex:1;
}

/* ===== User table container ===== */

.container-box{
    background:white;
    padding:25px;
    border-radius:14px;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

/* ===== Table ===== */

.table{
    margin-bottom:0;
}

.table thead{
    background:#f5f7fb;
}


.action-btn{
    display:inline-flex;
    align-items:center;
    gap:5px;
}

/* ===== Responsive ===== */

@media(max-width:768px){

.app-layout{
flex-direction:column;
}

}

</style>

</head>

<body>

<div class="app-layout">

    @include('ManagementSystemViews.AdminViews.Layouts.aside')

<div class="app-content">

<div class="container-box">

<div class="d-flex justify-content-between align-items-center mb-4">

<h3 class="fw-bold">User List</h3>

<a href="/users/create" class="btn btn-success">
<i class="bi bi-person-plus"></i> Create User
</a>

</div>

<table class="table table-hover align-middle">

<thead>

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th width="240">Actions</th>
</tr>

</thead>

<tbody>

@foreach($users as $user)

<tr>

<td>{{ $user->id }}</td>

<td>{{ $user->name }}</td>

<td>{{ $user->email }}</td>

<td>
<span class="badge bg-primary">
{{ $user->role }}
</span>
</td>

<td>

<a href="/users/{{ $user->id }}" class="btn btn-sm btn-primary action-btn">
<i class="bi bi-eye"></i> View
</a>

<a href="/users/{{ $user->id }}/edit" class="btn btn-sm btn-warning action-btn">
<i class="bi bi-pencil"></i> Edit
</a>

<form action="/users/{{ $user->id }}" method="POST" style="display:inline;">
@csrf
@method('DELETE')

<button class="btn btn-sm btn-danger action-btn"
onclick="return confirm('Delete this user?')">

<i class="bi bi-trash"></i> Delete

</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

</div>

</body>
</html>
