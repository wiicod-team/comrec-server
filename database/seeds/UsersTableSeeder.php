<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $path =base_path("database/seeds/json/users.json");
        $items = json_decode(file_get_contents($path),true);
        foreach ($items as $item){
//            $item['password']= bcrypt($item['password']);
            $roles =isset($item["roles"])?$item["roles"]:null;
            unset($item["roles"]);
            $u= \App\User::create($item);

            if(is_array($roles)){
                $roles = \App\Role::whereIn('name',$roles)->get();
                /*foreach ($ro as $r){
                    $u->attachRole($r);
                - option_name : BVS_USERNAME
    value : admin
  - option_name : BVS_PASSWORD
    value : admin
  - option_name : BVS_URL
    value : 'http://10.10.200.26:8124/api1/x3/erp/BVSTEST/'
                }*/
                $u->attachRoles($roles);
            }

        }
//        factory(\App\User::class,5)->create();
    }
}
