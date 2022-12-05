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

        Render::create(['submitted_by_user_id' => 1,
            'status' => 'ready']);

        Render::create(['submitted_by_user_id' => 2,
            'status' => 'ready']);
    }
}
