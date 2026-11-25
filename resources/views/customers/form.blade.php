@extends('layouts.app')

@section('content')
    <h1>{{ $customer->exists ? 'Edit Customer' : 'Add Customer' }}</h1>

    <form
        class="form-layout"
        method="POST"
        action="{{ $customer->exists
            ? route('customers.update', $customer)
            : route('customers.store') }}"
    >
        @csrf

        @if ($customer->exists)
            @method('PUT')
        @endif

        <label for="full_name">Full name</label>
        <input
            id="full_name"
            name="full_name"
            maxlength="100"
            value="{{ old('full_name', $customer->full_name) }}"
            required
        >

        <label for="email">Email</label>
        <input
            id="email"
            name="email"
            type="email"
            maxlength="100"
            value="{{ old('email', $customer->email) }}"
            required
        >

        <label for="phone">Phone (optional)</label>
        <input
            id="phone"
            name="phone"
            maxlength="20"
            value="{{ old('phone', $customer->phone) }}"
        >

        <p><button type="submit">Save Customer</button></p>
    </form>
@endsection