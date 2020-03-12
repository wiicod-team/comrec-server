<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    //
    use RestTrait;

    protected $fillable = ['pdf','amount','note','bill_id','user_id'];

    protected $dates = ['created_at','updated_at'];

    public function  __construct(array $attributes = [])
    {
        $this->files = ['pdf'];
        parent::__construct($attributes);
    }


    public function getLabel()
    {
        return "$this->amount note: $this->note bill: $this->bill->amount" ;
    }

    public function getPdfAttribute($val)
    {
        if($val==null){
            return null;
        }
        return env('APP_URL').$val;
    }

    public function bill(){
        return $this->belongsTo(Bill::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
