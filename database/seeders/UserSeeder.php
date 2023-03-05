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
        User::updateOrCreate([
            'email' => 'nuttagorn@pb.com'
        ], [
            'first_name' => 'Nuttagorn',
            'last_name' => 'Emchananon',
            'email' => 'nuttagorn@pb.com',
            'password' => bcrypt('@boy2505'),
            'role' => 'admin'
        ]);
    }
}
