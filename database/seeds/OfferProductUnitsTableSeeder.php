<?php

use App\Helpers\FactoryHelper;
use Illuminate\Database\Seeder;

class OfferProductUnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        FactoryHelper::force_seed(\App\OfferProductUnit::class,30);
    }
}
