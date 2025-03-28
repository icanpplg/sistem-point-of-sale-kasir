<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Menangani request autentikasi.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // Validasi input login
        $request->validate([
            'name'     => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Cek apakah user dengan nama yang diberikan ada dan password cocok
        if (! Auth::attempt(['name' => $request->name, 'password' => $request->password], $request->boolean('remember'))) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('auth.failed'),
                ], 422);
            }
            throw ValidationException::withMessages([
                'name' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        // Redirect berdasarkan tipe user (contoh: admin)
        if ($request->user()->usertype == 'admin') {
            $redirectUrl = '/admin/dashboard';
            $request->session()->flash('status', 'Login berhasil!');
            if ($request->expectsJson()) {
                return response()->json([
                    'success'  => true,
                    'redirect' => $redirectUrl,
                ]);
            }
            return redirect($redirectUrl);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        return redirect()->intended(route('login', false));
    }

    /**
     * Menghancurkan session yang telah terautentikasi (logout) tanpa reload halaman.
     */
    public function destroy(Request $request): RedirectResponse|JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'redirect' => '/login',
            ]);
        }
        return redirect('/login');
    }
}
