@extends('layouts.app')

@section('content')
    <h1>Sales History</h1>

    <p>
        <a href="{{ route('sales.create') }}">Record Sale</a>
    </p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Customer</th>
                <th>Staff member</th>
                <th>Quantity</th>
                <th>Total price</th>
                <th>Confirmation</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $sale->product?->name ?? 'Unavailable' }}</td>
                    <td>
                        {{ $sale->customer?->full_name ?? 'Walk-in / deleted customer' }}
                    </td>
                    <td>{{ $sale->staff?->full_name ?? 'Unavailable' }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>{{ number_format($sale->total_price, 2) }}</td>
                    <td>
                        @if ($sale->customer_id === null)
                            No confirmation required
                        @elseif ($sale->confirmed_at)
                            Confirmed
                        @else
                            Awaiting confirmation
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $sales->links() }}
@endsection