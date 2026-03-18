@include('ManagementSystemViews.AdminViews.Layouts.navbar')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background:#eef2f7;
            font-family:system-ui;
            margin:0;
        }

        .app-layout{
            display:flex;
            gap:20px;
            padding:20px;
        }

        .app-content{
            flex:1;
        }

        .box{
            background:#fff;
            border-radius:14px;
            padding:24px;
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
            margin-bottom:20px;
        }
    </style>
</head>
<body>

<div class="app-layout">
    @include('ManagementSystemViews.AdminViews.Layouts.aside')

    <div class="app-content">
        <div class="box">
            <h3>User Detail</h3>

            <p><strong>ID:</strong> {{ $user->id }}</p>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ $user->role }}</p>
            <p><strong>BC Customer No:</strong> {{ $user->BCcustomer_no }}</p>
        </div>

        <div class="box">
            <h3>BC Customer Background</h3>

            @if($customer)
                <p><strong>Customer No:</strong> {{ $customer['number'] ?? '' }}</p>
                <p><strong>Name:</strong> {{ $customer['displayName'] ?? '' }}</p>
                <p><strong>Email:</strong> {{ $customer['email'] ?? '' }}</p>
                <p><strong>Phone:</strong> {{ $customer['phoneNumber'] ?? '' }}</p>
                <p><strong>City:</strong> {{ $customer['city'] ?? '' }}</p>
                <p><strong>Country:</strong> {{ $customer['country'] ?? '' }}</p>
            @else
                <div class="alert alert-warning mb-0">
                    No matching BC customer found.
                </div>
            @endif
        </div>
    </div>
</div>

</body>
</html>
