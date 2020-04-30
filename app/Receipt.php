<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    //
    use RestTrait;

    protected $fillable = ['amount','note','bill_id','user_id','payment_method'];

    protected $dates = ['created_at','updated_at'];



    public function getLabel()
    {
        return "$this->amount note: $this->note bill: $this->bill->amount" ;
    }



    public function bill(){
        return $this->belongsTo(Bill::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
