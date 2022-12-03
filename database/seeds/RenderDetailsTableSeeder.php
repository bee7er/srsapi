<?php

use App\RenderDetails;
use Illuminate\Database\Seeder;

class RenderDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('render_details')->delete();

        RenderDetails::create(['allocated_to_user_id' => 1,
            'status' => 'ready']);

        RenderDetails::create(['allocated_to_user_id' => 2,
            'status' => 'ready']);
    }
}
