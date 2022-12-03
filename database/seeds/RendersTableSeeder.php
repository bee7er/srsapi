<?php

use Illuminate\Database\Seeder;
use App\Render;

class RendersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('renders')->delete();

        Render::create(['user_id' => 1,
            'description' => 'Render of my first key frame',
            'c4d_action' => 'Some render request string',
            'status' => 'open']);

        Render::create(['user_id' => 2,
            'description' => 'Render of my interesting key frame',
            'c4d_action' => 'Another render request string',
            'status' => 'open']);
    }
}
