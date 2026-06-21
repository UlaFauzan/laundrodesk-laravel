<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                $role = strtolower(auth()->user()->role?->nama_role ?? '');

                // Hanya pelanggan yang perlu daftar dulu (punya pelanggan_id)
                if ($role === 'pelanggan' && !auth()->user()->pelanggan_id) {
                    Auth::logout();
                    return redirect('/login')->with('message', 'Pelanggan harus daftar dahulu');
                }



                if ($role === 'admin') {
                    return redirect('/admin');
                }

                if ($role === 'kasir') {
                    return redirect('/transaksi');
                }

                if ($role === 'manager') {
                    return redirect('/laporan-pendapatan');
                }

                if ($role === 'pelanggan') {
                    return redirect('/pelanggan/profile');
                }

                Auth::logout();
                return back()->withErrors([
                    'email' => 'Role akun Anda tidak dikenali.',
                ])->withInput();
            }

            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->withInput();
        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat login. Silakan coba lagi.',
            ])->withInput();
        }
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            // Karena form email pelanggan memakai prefix (email_prefix) dan email final dibentuk via JS,
            // rule unique harus dihitung setelah email final terbentuk.
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'email_prefix' => 'nullable|string',
                'password' => 'required|string|min:8|confirmed',
                'telepon' => 'required|string|max:20',
                'alamat' => 'required|string',
            ]);

            // Bentuk email final dari prefix + "@mail.com" (fallback ke email yang dikirim hidden).
            $email = $validated['email'];
            if (isset($request->email_prefix) && trim((string) $request->email_prefix) !== '') {
                $email = trim((string) $request->email_prefix) . '@mail.com';
            }

            $validated['email'] = $email;

            $existingUserByEmail = User::where('email', $validated['email'])->first();
            if ($existingUserByEmail && strtolower($existingUserByEmail->role?->nama_role ?? '') !== 'pelanggan') {
                return back()->withErrors([
                    'email' => 'Email sudah digunakan oleh akun non-pelanggan.',
                ])->withInput();
            }

            // Pastikan pelanggan sudah memiliki transaksi sebelum bisa mendaftar
            $pelanggan = Pelanggan::where('telepon', $validated['telepon'])
                ->whereHas('transaksi')
                ->first();

            if (! $pelanggan) {
                return back()->withErrors([
                    'telepon' => 'Anda belum melakukan transaksi sehingga tidak dapat mendaftar.',
                ])->withInput();
            }

            if ($existingUserByEmail && $existingUserByEmail->pelanggan_id && $existingUserByEmail->pelanggan_id !== $pelanggan->id) {
                return back()->withErrors([
                    'email' => 'Email sudah digunakan oleh pelanggan lain.',
                ])->withInput();
            }

            $userWithPelanggan = User::where('pelanggan_id', $pelanggan->id)->first();
            if ($userWithPelanggan && $existingUserByEmail && $userWithPelanggan->id !== $existingUserByEmail->id) {
                return back()->withErrors([
                    'email' => 'Email sudah digunakan oleh akun pelanggan lain.',
                ])->withInput();
            }

            // Get pelanggan role (id: 4)
            $pelangganRole = Role::where('nama_role', 'pelanggan')->first();

            $pelanggan->update([
                'nama' => $validated['name'],
                'alamat' => $validated['alamat'],
            ]);

            if ($userWithPelanggan) {
                $user = $userWithPelanggan;
            } elseif ($existingUserByEmail) {
                $user = $existingUserByEmail;
            } else {
                $user = null;
            }

            if ($user) {
                $user->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role_id' => $pelangganRole?->id ?? 4,
                    'pelanggan_id' => $pelanggan->id,
                ]);
            } else {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role_id' => $pelangganRole?->id ?? 4,
                    'pelanggan_id' => $pelanggan->id,
                ]);
            }

            Auth::login($user);
            $request->session()->regenerate();

            if (strtolower(auth()->user()->role?->nama_role ?? '') !== 'pelanggan') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini bukan pelanggan.',
                ])->withInput();
            }

            return redirect()->intended('pelanggan/profile');
        } catch (\Exception $e) {
            \Log::error('Register error: ' . $e->getMessage());
            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.',
            ])->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        // Invalidate current session
        $request->session()->invalidate();
        
        // Regenerate CSRF token for next session
        $request->session()->regenerateToken();
        
        // Clear all cookies
        session()->flush();
        
        // Return redirect with explicit cache headers to prevent "page expired" error
        return redirect('/login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0')
            ->with('message', 'Anda telah berhasil logout');
    }
}
