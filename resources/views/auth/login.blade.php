<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>POS Staff Login</title>
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
</head>
<body>
    <div class="container" style="max-width: 440px; margin-top: 8vh;">
        <main class="card">
            <h1>Staff Login</h1>

            @if ($errors->any())
                <p class="error" role="alert">
                    {{ $errors->first() }}
                </p>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <label for="username">Username</label>
                <input
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    autofocus
                >

                <label for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                >

                <p>
                    <button type="submit">Sign in</button>
                </p>
            </form>
        </main>
    </div>
</body>
</html>