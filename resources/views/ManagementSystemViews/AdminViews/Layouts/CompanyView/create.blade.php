<!DOCTYPE html>
<html>
<head>
    <title>Create Company</title>
</head>
<body>

<h2>Create Company</h2>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <p style="color:red;">{{ $error }}</p>
    @endforeach
@endif

<form action="{{ route('companies.store') }}" method="POST">
    @csrf

    <h3>Company Info</h3>

    <input type="text" name="name" placeholder="Company Name"><br><br>
    <input type="text" name="display_name" placeholder="Display Name"><br><br>
    <input type="text" name="phone" placeholder="Phone"><br><br>
    <input type="email" name="email" placeholder="Email"><br><br>
    <textarea name="address" placeholder="Address"></textarea><br><br>

    <h3>Business Central</h3>

    <input type="text" name="tenant_id" placeholder="Tenant ID"><br><br>
    <input type="text" name="client_id" placeholder="Client ID"><br><br>
    <input type="text" name="client_secret" placeholder="Client Secret"><br><br>
    <input type="text" name="company_bc_id" placeholder="Company BC ID"><br><br>
    <input type="text" name="environment" placeholder="Environment"><br><br>
    <input type="text" name="base_url" placeholder="Base URL"><br><br>
    <input type="text" name="token_url" placeholder="Token URL"><br><br>

    <label>
        <input type="checkbox" name="is_default"> Default
    </label><br><br>

    <label>
        <input type="checkbox" name="status" checked> Active
    </label><br><br>

    <button type="submit">Save</button>
</form>

<br>
<a href="{{ route('companies.index') }}">Back</a>

</body>
</html>
