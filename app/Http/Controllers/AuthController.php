<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\LogAktivitas;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['username' => 'Akun Anda tidak aktif. Hubungi admin.'])->withInput();
            }

            $request->session()->regenerate();

            LogAktivitas::log('login', 'Login berhasil sebagai ' . $user->role);

            if ($user->is_first_login) {
                return redirect()->route('ganti-password');
            }

            return $this->redirectByRole();
        }

        LogAktivitas::log('login_gagal', 'Login gagal untuk username: ' . $request->username, null);

        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }

    public function logout(Request $request)
    {
        LogAktivitas::log('logout', 'Logout');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showGantiPassword()
    {
        return view('auth.ganti-password');
    }

    public function gantiPassword(Request $request)
    {
        $rules = [
            'password_baru' => 'required|min:6|confirmed',
        ];

        if (!auth()->user()->is_first_login) {
            $rules['password_lama'] = 'required';
        }

        $request->validate($rules);

        $user = auth()->user();

        if (!$user->is_first_login && !Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama salah.']);
        }

        $user->password = Hash::make($request->password_baru);
        $user->is_first_login = false;
        $user->save();

        LogAktivitas::log('ganti_password', 'Password berhasil diubah');

        return redirect($this->getRedirectUrl())->with('success', 'Password berhasil diubah!');
    }

    private function redirectByRole()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('siswa.dashboard');
    }

    private function getRedirectUrl()
    {
        if (auth()->user()->isAdmin()) {
            return route('admin.dashboard');
        }
        return route('siswa.dashboard');
    }
}
