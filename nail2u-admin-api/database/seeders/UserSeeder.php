<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(300)->create();

        User::find(1)->update([
            'email' => 'user1@gmail.com',
            'password' => '$2y$10$BqaWq49Ttt2e0UfIcmfqFeyYd/UBZ38PPIL5ruFV/n/ghPiO2L8u6',
            'email_verified_at' => '2021-12-28 17:53:28',
            'phone_verified_at' => '2021-12-28 17:53:28'
        ]);

        User::find(2)->update([
            'email' => 'user2@gmail.com',
            'password' => '$2y$10$BqaWq49Ttt2e0UfIcmfqFeyYd/UBZ38PPIL5ruFV/n/ghPiO2L8u6',
            'email_verified_at' => '2021-12-28 17:53:28',
            'phone_verified_at' => '2021-12-28 17:53:28'
        ]);

        User::find(101)->update([
            'email' => 'artist1@gmail.com',
            'password' => '$2y$10$BqaWq49Ttt2e0UfIcmfqFeyYd/UBZ38PPIL5ruFV/n/ghPiO2L8u6'
        ]);

        User::find(102)->update([
            'email' => 'artist2@gmail.com',
            'password' => '$2y$10$BqaWq49Ttt2e0UfIcmfqFeyYd/UBZ38PPIL5ruFV/n/ghPiO2L8u6'
        ]);

        User::find(201)->update([
            'email' => 'admin1@gmail.com',
            'password' => '$2y$10$BqaWq49Ttt2e0UfIcmfqFeyYd/UBZ38PPIL5ruFV/n/ghPiO2L8u6'
        ]);

        User::find(202)->update([
            'email' => 'admin2@gmail.com',
            'password' => '$2y$10$BqaWq49Ttt2e0UfIcmfqFeyYd/UBZ38PPIL5ruFV/n/ghPiO2L8u6'
        ]);
    }
}
