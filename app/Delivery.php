<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    //
    use RestTrait;

    protected $fillable = ['amount','status','town','road','district','note','invoice_id'];

    protected $dates = ['created_at','updated_at'];

    public static $Status = ['new', 'pending', 'book', 'cancel'];

    public function getLabel()
    {
        return "delivery $this->road status: $this->status" ;
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
}
