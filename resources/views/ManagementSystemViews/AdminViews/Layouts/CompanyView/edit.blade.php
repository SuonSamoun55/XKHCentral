<!DOCTYPE html>
<html>
<head>
    <title>Edit Company</title>
</head>
<body>

<h2>Edit Company</h2>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <p style="color:red;">{{ $error }}</p>
    @endforeach
@endif

<form action="{{ route('companies.update', $company->id) }}" method="POST">
    @csrf
    @method('PUT')

    <h3>Company Info</h3>

    <input type="text" name="name" value="{{ old('name', $company->name) }}" placeholder="Company Name" required><br><br>
    <input type="text" name="display_name" value="{{ old('display_name', $company->display_name) }}" placeholder="Display Name"><br><br>
    <input type="text" name="phone" value="{{ old('phone', $company->phone) }}" placeholder="Phone"><br><br>
    <input type="email" name="email" value="{{ old('email', $company->email) }}" placeholder="Email"><br><br>
    <textarea name="address" placeholder="Address">{{ old('address', $company->address) }}</textarea><br><br>
    <input type="text" name="logo" value="{{ old('logo', $company->logo) }}" placeholder="Logo"><br><br>
    <input type="text" name="tax_number" value="{{ old('tax_number', $company->tax_number) }}" placeholder="Tax Number"><br><br>

    <label>
        <input type="checkbox" name="is_active" {{ $company->is_active ? 'checked' : '' }}>
        Company Active
    </label>

    <h3>Business Central</h3>

    <input type="text" name="tenant_id" value="{{ old('tenant_id', $company->connection->tenant_id ?? '') }}" placeholder="Tenant ID" required><br><br>
    <input type="text" name="client_id" value="{{ old('client_id', $company->connection->client_id ?? '') }}" placeholder="Client ID" required><br><br>
    <input type="text" name="client_secret" placeholder="New Client Secret (leave blank to keep old one)"><br><br>
    <input type="text" name="company_bc_id" value="{{ old('company_bc_id', $company->connection->company_bc_id ?? '') }}" placeholder="Company BC ID" required><br><br>
    <input type="text" name="environment" value="{{ old('environment', $company->connection->environment ?? '') }}" placeholder="Environment"><br><br>
    <input type="text" name="base_url" value="{{ old('base_url', $company->connection->base_url ?? '') }}" placeholder="Base URL"><br><br>
    <input type="text" name="token_url" value="{{ old('token_url', $company->connection->token_url ?? '') }}" placeholder="Token URL"><br><br>

    <label>
        <input type="checkbox" name="status" {{ ($company->connection && $company->connection->status) ? 'checked' : '' }}>
        Connection Active
    </label>

    <br><br>
    <button type="submit">Update Company</button>
</form>

<br>
<a href="{{ route('companies.index') }}">Back</a>

</body>
</html>
