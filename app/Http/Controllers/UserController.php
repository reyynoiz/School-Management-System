<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index');
    }

    public function data(): JsonResponse
    {
        $data = User::orderBy('name')->get()->map(fn ($u, $i) => [
            'no'         => $i + 1,
            'name'       => $u->name,
            'username'   => $u->username,
            'email'      => $u->email,
            'role'       => ucfirst($u->role),
            'active'     => (bool) $u->status,
            'last_login' => $u->last_login_time ? formatTanggalIndo($u->last_login_time) : '-',
            'is_self'    => $u->id === Auth::id(),
            'status_url' => route('users.toggle-status', $u->id),
            'reset_url'  => route('users.reset-password', $u->id),
        ]);

        return response()->json(['data' => $data]);
    }

    public function toggleStatus(int $id): RedirectResponse
    {
        abort_if($id === Auth::id(), 403, 'Tidak dapat menonaktifkan akun sendiri.');

        $user = User::findOrFail($id);
        $user->status = $user->status ? 0 : 1;
        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'Akun ' . $user->name . ($user->status ? ' diaktifkan.' : ' dinonaktifkan.'));
    }

    public function resetPassword(int $id): RedirectResponse
    {
        abort_if($id === Auth::id(), 403, 'Ubah password akun sendiri lewat halaman Profile.');

        $user = User::findOrFail($id);
        $password = Str::password(10, symbols: false);
        $user->password = $password; // otomatis di-hash oleh cast 'hashed'
        $user->save();

        return redirect()->route('users.index')
            ->with('success', "Password {$user->name} direset. Password baru: {$password} (catat sekarang, hanya tampil sekali).");
    }
}