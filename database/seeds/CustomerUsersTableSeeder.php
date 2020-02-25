<?php

use App\Helpers\FactoryHelper;
use Illuminate\Database\Seeder;

class CustomerUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
//        factory(\App\CustomerUser::class,10)->create();
        FactoryHelper::force_seed(\App\CustomerUser::class,10);
    }
}
