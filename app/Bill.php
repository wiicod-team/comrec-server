<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    //
    use RestTrait;

    protected $fillable = ['amount','status','creation_date','customer_id','bvs_id'];

    protected $dates = ['created_at','updated_at','creation_date'];

    public static $Status = ['new', 'paid', 'pending', 'cancel'];

    public function getLabel()
    {
        return "$this->amount status: $this->status" ;
    }

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function receipts(){
        return $this->hasMany(Receipt::class);
    }
}
