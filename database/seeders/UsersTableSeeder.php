<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersData = [];

        foreach (range(1, 10) as $counter) {
            $usersData[] = $this->prepareUsersData($counter);
        }

        DB::table('users')->insert($usersData);
    }

    public function prepareUsersData(int $counter): array
    {
        return [
            'name' => "John Doe {$counter}",
            'email' => "johndoe{$counter}@example.com",
            'password' => '$2y$12$ZHDF5kIWtUdBnqQDbbZdQ.z8YVMggGhCKvogqRjrUJL0edas.VAyW',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
