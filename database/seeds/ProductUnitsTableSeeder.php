<?php

use App\Helpers\FactoryHelper;
use Illuminate\Database\Seeder;

class ProductUnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
//        factory(\App\ProductUnit::class,40)->create();
        FactoryHelper::force_seed(\App\ProductUnit::class,40);

    }
}
