@extends('layouts.app')

@section('content')
    <h1>Staff Accounts</h1>

    <p>
        <a href="{{ route('users.create') }}">Add Staff</a>
    </p>

    <table>
        <thead>
            <tr>
                <th>Avatar</th>
                <th>Username</th>
                <th>Full name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>
                        @if ($user->avatar)
                            <img
                                src="{{ asset('storage/' . $user->avatar) }}"
                                alt="{{ $user->full_name }}"
                            >
                        @endif
                    </td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->full_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}">Edit</a>

                        <form
                            method="POST"
                            action="{{ route('users.destroy', $user) }}"
                            style="display: inline;"
                            onsubmit="return confirm('Delete this staff account?')"
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

    {{ $users->links() }}
@endsection