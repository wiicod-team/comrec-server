<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        App\Helpers\FactoryHelper::clear();
        Model::unguard();
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);

//        $this->call(CustomersTableSeeder::class);
//        $this->call(BillsTableSeeder::class);
//        $this->call(CustomerUsersTableSeeder::class);

//        $this->call(ReceiptsTableSeeder::class);
        Model::reguard();
    }
}
