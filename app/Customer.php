<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    use RestTrait;

    protected $fillable = ['name','status','sale_network',];

    protected $dates = ['created_at','updated_at'];

    public static $Status = ['solvent', 'insolvent'];

    public function getLabel()
    {
        return "$this->name" ;
    }

    public function bills(){
        return $this->hasMany(Bill::class);
    }

    public function customer_users(){
        return $this->hasMany(CustomerUser::class);
    }

    public function users(){
        $this->belongsToMany(User::class,'customer_users');
    }


}
