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

        User::updateOrCreate([
            'email' => 'supakorn.emch@gmail.com'
        ], [
            'first_name' => 'Supakorn',
            'last_name' => 'Emchananon',
            'email' => 'supakorn@gmail.com',
            'password' => bcrypt('@Lovezaup001'),
            'role' => 'admin'
        ]);

        User::updateOrCreate([
            'email' => 'nittaya@pb.com'
        ], [
            'first_name' => 'Nittaya',
            'last_name' => 'Emchananon',
            'email' => 'nittaya@pb.com',
            'password' => bcrypt('@nittaya.emch'),
            'role' => 'admin'
        ]);

        User::updateOrCreate([
            'email' => 'prabath@pb.com'
        ], [
            'first_name' => 'โรตี',
            'last_name' => 'พระบาท',
            'email' => 'prabath@pb.com',
            'password' => bcrypt('pb@1234'),
            'role' => 'cashier'
        ]);

        User::updateOrCreate([
            'email' => 'pralan@pb.com'
        ], [
            'first_name' => 'โรตี',
            'last_name' => 'พระลาน',
            'email' => 'pralan@pb.com',
            'password' => bcrypt('pb@1234'),
            'role' => 'cashier'
        ]);

        User::updateOrCreate([
            'email' => 'banaoi@pb.com'
        ], [
            'first_name' => 'โรตี',
            'last_name' => 'บ้านอ้อย',
            'email' => 'banaoi@pb.com',
            'password' => bcrypt('pb@1234'),
            'role' => 'cashier'
        ]);
    }
}
