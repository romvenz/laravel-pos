@extends('layouts.app')

@section('content')
    <h1>Customers</h1>

    <p>
        <a href="{{ route('customers.create') }}">Add Customer</a>
    </p>

    <table>
        <thead>
            <tr>
                <th>Full name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->full_name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>
                        <a href="{{ route('customers.edit', $customer) }}">
                            Edit
                        </a>

                        <form
                            method="POST"
                            action="{{ route('customers.destroy', $customer) }}"
                            style="display: inline;"
                            onsubmit="return confirm('Delete this customer?')"
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

    {{ $customers->links() }}
@endsection