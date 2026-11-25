@extends('layouts.app')

@section('content')
    <h1>Record Sale</h1>

    <form class="form-layout" method="POST" action="{{ route('sales.store') }}">
        @csrf

        <label for="product_id">Product</label>
        <select id="product_id" name="product_id" required>
            <option value="">Choose a product</option>
            @foreach ($products as $product)
                <option
                    value="{{ $product->id }}"
                    @selected(old('product_id') == $product->id)
                >
                    {{ $product->name }} — {{ number_format($product->price, 2) }}
                    ({{ $product->stock_quantity }} in stock)
                </option>
            @endforeach
        </select>

        <label for="customer_id">
            Customer
        </label>
        <select id="customer_id" name="customer_id">
            <option value="">Walk-in customer</option>
            @foreach ($customers as $customer)
                <option
                    value="{{ $customer->id }}"
                    @selected(old('customer_id') == $customer->id)
                >
                    {{ $customer->full_name }} — {{ $customer->email }}
                </option>
            @endforeach
        </select>

        <label for="quantity">Quantity</label>
        <input
            id="quantity"
            name="quantity"
            type="number"
            min="1"
            value="{{ old('quantity', 1) }}"
            required
        >

        <p><button type="submit">Record Sale</button></p>
    </form>
@endsection