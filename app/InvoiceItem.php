<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    //
    use RestTrait;

    protected $fillable = ['price_was','quantity','concern_type','concern_id','invoice_id'];

    protected $dates = ['created_at','updated_at'];

    public static $Concerns = [Offer::class, ProductUnit::class];





    public function getLabel()
    {
        return "$this->price_was FCFA" ;
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }

    public function concern()
    {
        return $this->morphTo('concern');
    }
}
