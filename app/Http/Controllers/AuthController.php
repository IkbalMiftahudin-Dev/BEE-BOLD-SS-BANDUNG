<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    private const RECOVERY_SESSION_KEY = 'admin_recovery_verified_at';

    private const RECOVERY_TTL_SECONDS = 300;

    public function showLoginForm(Request $request): View
    {
        return view('auth.login', [
            'isRecoveryVerified' => $this->hasValidRecoverySession($request),
        ]);
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Regenerate session ID to prevent session fixation attacks.
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function verifyBackupCode(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'backup_code' => ['required', 'string', 'max:64'],
        ], [
            'backup_code.required' => 'Backup Code wajib diisi.',
            'backup_code.string' => 'Format Backup Code tidak valid.',
            'backup_code.max' => 'Backup Code maksimal 64 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('login')
                ->withErrors($validator, 'recovery')
                ->with('recovery_step', 'verify');
        }

        $normalizedCode = strtoupper(trim((string) $request->input('backup_code')));
        $configuredHash = (string) config('auth.admin_backup_code_hash', '');

        if ($configuredHash === '' || ! Hash::check($normalizedCode, $configuredHash)) {
            $request->session()->forget(self::RECOVERY_SESSION_KEY);

            return redirect()
                ->route('login')
                ->withErrors([
                    'backup_code' => 'Backup Code yang Anda masukkan tidak valid.',
                ], 'recovery')
                ->with('recovery_step', 'verify');
        }

        $request->session()->put(self::RECOVERY_SESSION_KEY, now()->getTimestamp());

        return redirect()
            ->route('login')
            ->with('recovery_step', 'reset');
    }

    public function resetPasswordWithBackupCode(Request $request): RedirectResponse
    {
        if (! $this->hasValidRecoverySession($request)) {
            $request->session()->forget(self::RECOVERY_SESSION_KEY);

            return redirect()
                ->route('login')
                ->withErrors([
                    'backup_code' => 'Sesi verifikasi Backup Code telah berakhir (maksimal 5 menit). Silakan masukkan kembali Backup Code Anda.',
                ], 'recovery')
                ->with('recovery_step', 'verify');
        }

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.string' => 'Format kata sandi baru tidak valid.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.max' => 'Kata sandi baru maksimal 255 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('login')
                ->withErrors($validator, 'resetPassword')
                ->with('recovery_step', 'reset');
        }

        $admin = User::where('role', UserRole::Admin)->orderBy('id')->first();

        if (! $admin) {
            $request->session()->forget(self::RECOVERY_SESSION_KEY);

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Akun Admin tidak ditemukan di sistem.',
                ]);
        }

        $admin->password = (string) $request->input('password');
        $admin->setRememberToken(Str::random(60));
        $admin->save();

        $request->session()->forget(self::RECOVERY_SESSION_KEY);
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'Kata sandi Admin berhasil diperbarui. Silakan masuk menggunakan kata sandi baru Anda.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function hasValidRecoverySession(Request $request): bool
    {
        $verifiedAt = $request->session()->get(self::RECOVERY_SESSION_KEY);

        if (! is_int($verifiedAt) && ! is_numeric($verifiedAt)) {
            return false;
        }

        $elapsed = now()->getTimestamp() - (int) $verifiedAt;

        if ($elapsed < 0 || $elapsed > self::RECOVERY_TTL_SECONDS) {
            $request->session()->forget(self::RECOVERY_SESSION_KEY);

            return false;
        }

        return true;
    }
}

