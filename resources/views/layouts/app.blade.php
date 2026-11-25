<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POS System</title>
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
</head>
<body>
    <div class="container">
        <nav>
            <a href="{{ route('sales.index') }}">Sales History</a>
            <a href="{{ route('sales.create') }}">Record Sale</a>
            <a href="{{ route('products.index') }}">Products</a>
            <a href="{{ route('customers.index') }}">Customers</a>
            <a href="{{ route('users.index') }}">Staff</a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Log out</button>
            </form>
        </nav>

        @if (session('status'))
            <p class="success">{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <div class="error" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <main class="card" style="margin-top: 22px;">
            @yield('content')
        </main>
    </div>
</body>
</html>