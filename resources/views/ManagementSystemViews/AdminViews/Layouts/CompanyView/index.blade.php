<!DOCTYPE html>
<html>
<head>
    <title>Company List</title>
</head>
<body>

<h2>Company List</h2>

@if(session('success'))
    <p style="color:green;">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

<a href="{{ route('companies.create') }}">+ Create Company</a>

<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>BC Company ID</th>
        <th>Status</th>
        <th>Selected</th>
        <th>Action</th>
    </tr>

    @foreach($companies as $company)
    <tr>
        <td>{{ $company->id }}</td>
        <td>{{ $company->name }}</td>
        <td>{{ $company->connection->company_bc_id ?? '-' }}</td>
        <td>{{ ($company->connection && $company->connection->status) ? 'Active' : 'Inactive' }}</td>
        <td>
            {{ session('selected_company_id') == $company->id ? 'YES' : 'NO' }}
        </td>
        <td>
            <form action="{{ route('companies.select') }}" method="POST">
                @csrf
                <input type="hidden" name="company_id" value="{{ $company->id }}">
                <button type="submit">Select</button>
            </form>
        </td>
    </tr>
    @endforeach

</table>

</body>
</html>
