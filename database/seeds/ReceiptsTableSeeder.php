<?php

use Illuminate\Database\Seeder;

class ReceiptsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(\App\Receipt::class,10)->create();
    }
}
