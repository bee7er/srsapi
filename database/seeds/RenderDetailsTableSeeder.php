<?php

use App\RenderDetail;
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

        RenderDetail::create(['render_id' => 1,
            'allocated_to_user_id' => 0,
            'status' => 'ready']);

        RenderDetail::create(['render_id' => 2,
            'allocated_to_user_id' => 0,
            'status' => 'ready']);
    }
}
