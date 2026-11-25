@extends('layouts.app')

@section('content')
    <h1>{{ $user->exists ? 'Edit Staff' : 'Add Staff' }}</h1>

    <form
        class="form-layout"
        method="POST"
        enctype="multipart/form-data"
        action="{{ $user->exists
            ? route('users.update', $user)
            : route('users.store') }}"
    >
        @csrf

        @if ($user->exists)
            @method('PUT')
        @endif

        <label for="username">Username</label>
        <input
            id="username"
            name="username"
            maxlength="50"
            value="{{ old('username', $user->username) }}"
            required
        >

        <label for="full_name">Full name</label>
        <input
            id="full_name"
            name="full_name"
            maxlength="100"
            value="{{ old('full_name', $user->full_name) }}"
            required
        >

        <label for="email">Email</label>
        <input
            id="email"
            name="email"
            type="email"
            value="{{ old('email', $user->email) }}"
            required
        >

        <label for="password">
            Password
            @if ($user->exists)
                (leave blank to keep the current password)
            @endif
        </label>
        <input
            id="password"
            name="password"
            type="password"
            minlength="12"
            @if (! $user->exists) required @endif
        >

        <label for="avatar">Avatar (optional, maximum 2 MB)</label>
        <input
            id="avatar"
            name="avatar"
            type="file"
            accept="image/jpeg,image/png,image/webp"
        >

        @if ($user->avatar)
            <p>Current avatar:</p>
            <img
                src="{{ asset('storage/' . $user->avatar) }}"
                alt="{{ $user->full_name }}"
            >
        @endif

        <p><button type="submit">Save Staff</button></p>
    </form>
@endsection