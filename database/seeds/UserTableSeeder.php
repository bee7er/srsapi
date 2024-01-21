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

        User::create([
            'name' => 'etheridge',
            'email' => 'betheridge@gmail.com',
            'password' =>  Hash::make('password'),
            'role' =>  'admin',
            'status' =>  'available']);

        User::create([
            'name' => 'fiddlestone',
            'email' => 'contact_bee@yahoo.com',
            'password' =>  Hash::make('password'),
            'role' =>  'admin',
            'status' =>  'available']);
    }
}