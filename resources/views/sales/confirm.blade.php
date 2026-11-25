<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm Order</title>
    <link rel="stylesheet" href="{{ asset('css/pos.css') }}">
</head>
<body>
    <div class="container" style="max-width: 560px; margin-top: 8vh;">
        <main class="card">
            <h1>Order #{{ $sale->id }}</h1>

            <p><strong>Product:</strong> {{ $sale->product->name }}</p>
            <p><strong>Quantity:</strong> {{ $sale->quantity }}</p>
            <p>
                <strong>Total:</strong>
                {{ number_format($sale->total_price, 2) }}
            </p>

            @if ($sale->confirmed_at)
                <p class="success">
                    Thank you! Your order is confirmed.
                </p>
            @else
                <p>Please confirm that you received your sale receipt.</p>

                <form method="POST" action="{{ $confirmUrl }}">
                    @csrf
                    <button type="submit">Confirm Order</button>
                </form>
            @endif
        </main>
    </div>
</body>
</html>