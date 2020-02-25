<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;

class CustomerUser extends Model
{
    //
    use RestTrait;

    protected $fillable = ['customer_id','user_id'];

    protected $dates = ['created_at','updated_at'];

    public function getLabel()
    {
        return "$this->id customer: $this->customer->name user: $this->user->name" ;
    }

    public function customer(){
        $this->belongsTo(Customer::class);
    }

    public function user(){
        $this->belongsTo(User::class);
    }
}
