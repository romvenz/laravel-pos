@php
    $confirmationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
        'sales.confirm.show',
        now()->addDays(7),
        ['sale' => $sale->id]
    );
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sale Receipt</title>
</head>
<body style="margin: 0; padding: 32px 16px; background: #f7f8ff; font-family: Arial, sans-serif; color: #29304b;">
    <div style="max-width: 560px; margin: 0 auto; padding: 28px; background: #ffffff; border: 1px solid #dfe3f5; border-radius: 14px;">
        <h1 style="margin: 0 0 20px; color: #4e5d9f; font-size: 26px;">
            Sale Receipt
        </h1>

        <p>Hello {{ $sale->customer->full_name }},</p>

        <p>
            Your sale <strong>#{{ $sale->id }}</strong> was recorded on
            {{ $sale->created_at->format('Y-m-d H:i') }}.
        </p>

        <div style="margin: 24px 0; padding: 18px; background: #eef0ff; border-radius: 10px;">
            <p style="margin: 0 0 10px;">
                <strong>Product:</strong> {{ $sale->product->name }}
            </p>
            <p style="margin: 0 0 10px;">
                <strong>Quantity:</strong> {{ $sale->quantity }}
            </p>
            <p style="margin: 0;">
                <strong>Total:</strong>
                {{ number_format($sale->total_price, 2) }}
            </p>
        </div>

        <p>Processed by {{ $sale->staff->full_name }}.</p>

        <p>Please confirm your order:</p>

        <p style="margin: 24px 0;">
            <a
                href="{{ $confirmationUrl }}"
                style="display: inline-block; padding: 12px 20px; background: #6574ba; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold;"
            >
                Confirm Order
            </a>
        </p>

        <p style="color: #687296; font-size: 13px;">
            This confirmation link expires after 7 days.
        </p>
    </div>
</body>
</html>