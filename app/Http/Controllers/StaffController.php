<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'users' => User::latest()->paginate(15),
        ]);
    }

    public function create()
    {
        return view('users.form', [
            'user' => new User(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['name'] = $data['full_name'];

        if ($request->hasFile('avatar')) {
            $data['avatar'] = Photo::save(
                $request->file('avatar'),
                'avatars',
                'avatar'
            );
        }

        try {
            User::create($data);
        } catch (\Throwable $exception) {
            Photo::delete($data['avatar'] ?? null);
            throw $exception;
        }

        return redirect()
            ->route('users.index')
            ->with('status', 'Staff account created.');
    }

    public function edit(User $user)
    {
        return view('users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validatedData($request, $user);
        $data['name'] = $data['full_name'];

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $oldAvatar = $user->avatar;

        if ($request->hasFile('avatar')) {
            $data['avatar'] = Photo::save(
                $request->file('avatar'),
                'avatars',
                'avatar'
            );
        }

        try {
            $user->update($data);
        } catch (\Throwable $exception) {
            if (isset($data['avatar'])) {
                Photo::delete($data['avatar']);
            }

            throw $exception;
        }

        if (isset($data['avatar'])) {
            Photo::delete($oldAvatar);
        }

        return redirect()
            ->route('users.index')
            ->with('status', 'Staff account updated.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->withErrors([
                'delete' => 'You cannot delete your own account.',
            ]);
        }

        try {
            $user->delete();
        } catch (\Illuminate\Database\QueryException $exception) {
            return back()->withErrors([
                'delete' => 'Staff with recorded sales cannot be deleted.',
            ]);
        }

        Photo::delete($user->avatar);

        return redirect()
            ->route('users.index')
            ->with('status', 'Staff account deleted.');
    }

    private function validatedData(
        Request $request,
        ?User $user = null
    ): array {
        return $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users')->ignore($user?->id),
            ],
            'full_name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user?->id),
            ],
            'password' => [
                $user ? 'nullable' : 'required',
                'string',
                'min:12',
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,webp',
                'max:2048',
            ],
        ]);
    }
}