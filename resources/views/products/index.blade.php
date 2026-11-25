@extends('layouts.app')

@section('content')
    <h1>Products</h1>

    <p>
        <a href="{{ route('products.create') }}">Add Product</a>
    </p>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>
                        @if ($product->image)
                            <img
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                            >
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product) }}">Edit</a>

                        <form
                            method="POST"
                            action="{{ route('products.destroy', $product) }}"
                            style="display: inline;"
                            onsubmit="return confirm('Delete this product?')"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $products->links() }}
@endsection