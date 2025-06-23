<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User; // Asegúrate de que User esté importado

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AuthorSeeder::class,
            BookSeeder::class,
        ]);
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}