<?php

use Illuminate\Database\Seeder;

class InvoiceItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        factory(\App\InvoiceItem::class,40)->create();
    }
}
