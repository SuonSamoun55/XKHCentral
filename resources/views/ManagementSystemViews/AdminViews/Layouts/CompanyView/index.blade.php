<!DOCTYPE html>
<html>
<head>
    <title>Company</title>
</head>
<body>

<h2>Company</h2>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

@if($company)
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <td>{{ $company->id }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $company->name }}</td>
        </tr>
        <tr>
            <th>Display Name</th>
            <td>{{ $company->display_name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Phone</th>
            <td>{{ $company->phone ?? '-' }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $company->email ?? '-' }}</td>
        </tr>
        <tr>
            <th>Address</th>
            <td>{{ $company->address ?? '-' }}</td>
        </tr>
        <tr>
            <th>BC Company ID</th>
            <td>{{ $company->connection->company_bc_id ?? '-' }}</td>
        </tr>
        <tr>
            <th>Connection Status</th>
            <td>{{ ($company->connection && $company->connection->status) ? 'Active' : 'Inactive' }}</td>
        </tr>
        <tr>
            <th>Company Status</th>
            <td>{{ $company->is_active ? 'Active' : 'Inactive' }}</td>
        </tr>
    </table>

    <br>

    <a href="{{ route('companies.edit', $company->id) }}">
        <button type="button">Edit Company</button>
    </a>

    <form action="{{ route('companies.destroy', $company->id) }}" method="POST" style="margin-top:10px;">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Are you sure you want to delete this company?')">
            Delete Company
        </button>
    </form>
@else
    <p>No company created yet.</p>

    <a href="{{ route('companies.create') }}">
        <button type="button">Create Company</button>
    </a>
@endif

</body>
</html>
