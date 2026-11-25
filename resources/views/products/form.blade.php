@extends('layouts.app')

@section('content')
    <h1>{{ $product->exists ? 'Edit Product' : 'Add Product' }}</h1>

    <form
        class="form-layout"
        method="POST"
        enctype="multipart/form-data"
        action="{{ $product->exists
            ? route('products.update', $product)
            : route('products.store') }}"
    >
        @csrf

        @if ($product->exists)
            @method('PUT')
        @endif

        <label for="name">Product name</label>
        <input
            id="name"
            name="name"
            maxlength="100"
            value="{{ old('name', $product->name) }}"
            required
        >

        <label for="price">Price</label>
        <input
            id="price"
            name="price"
            type="number"
            min="0.01"
            max="99999999.99"
            step="0.01"
            value="{{ old('price', $product->price) }}"
            required
        >

        <label for="stock_quantity">Stock quantity</label>
        <input
            id="stock_quantity"
            name="stock_quantity"
            type="number"
            min="0"
            value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}"
            required
        >

        <label for="image">Product image (optional, maximum 2 MB)</label>
        <input
            id="image"
            name="image"
            type="file"
            accept="image/jpeg,image/png,image/webp"
        >

        @if ($product->image)
            <p>Current image:</p>
            <img
                src="{{ asset('storage/' . $product->image) }}"
                alt="{{ $product->name }}"
            >
        @endif

        <p><button type="submit">Save Product</button></p>
    </form>
@endsection