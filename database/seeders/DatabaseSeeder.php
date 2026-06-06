<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create the admin role if it doesn't exist
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);

        if (! User::where('email', 'test@example.com')->exists()) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
            $user->assignRole($adminRole);
        } else {
            $user = User::where('email', 'test@example.com')->first();
            if (!$user->hasRole('admin')) {
                $user->assignRole($adminRole);
            }
        }

        $this->call([
            SettingsSeeder::class,
            ServicesSeeder::class,
            PortfoliosSeeder::class,
            PagesSeeder::class,
        ]);
    }
}
