<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Credentials come from environment variables; a random password is generated when absent
     * so no default password is ever hardcoded in the repository.
     */
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Admin Bee Bold',
                'email' => env('SEED_ADMIN_EMAIL', 'admin@beeboldss.local'),
                'password' => env('SEED_ADMIN_PASSWORD'),
                'role' => UserRole::Admin,
            ],
            [
                'name' => 'Tim Redaksi Bee Bold',
                'email' => env('SEED_EDITOR_EMAIL', 'editor@beeboldss.local'),
                'password' => env('SEED_EDITOR_PASSWORD'),
                'role' => UserRole::Editor,
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::firstOrNew(['email' => $account['email']]);

            // Existing accounts keep their current password when the seeder is re-run.
            if (! $user->exists) {
                $plainPassword = $account['password'] ?: Str::password(16);
                $user->password = $plainPassword;

                if (! $account['password']) {
                    $this->command?->warn("Akun {$account['email']} dibuat dengan password acak: {$plainPassword}");
                    $this->command?->warn('Simpan password ini sekarang; password tidak akan ditampilkan lagi.');
                }
            }

            $user->name = $account['name'];
            $user->forceFill([
                'role' => $account['role'],
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        }
    }
}

