<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        User::create(['first_name' => 'brian',
            'surname' => 'etheridge',
            'email' => 'betheridge@gmail.com',
            'password' =>  Hash::make('password'),
            'role' =>  'admin',
            'status' =>  'available']);

        User::create(['first_name' => 'barry',
            'surname' => 'fiddlestone',
            'email' => 'contact_bee@yahoo.com',
            'password' =>  Hash::make('password'),
            'role' =>  'admin',
            'status' =>  'available']);
    }
}