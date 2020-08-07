<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;

class OfferProductUnit extends Model
{
    //
    use RestTrait;

    protected $fillable = ['offer_id','product_unit_id'];

    protected $dates = ['created_at','updated_at'];

    public function getLabel()
    {
        return "$this->offer_id offer: $this->offer->name product: $this->product_unit->product->name $this->product_unit->unit" ;
    }

    public function offer(){
        return $this->belongsTo(Offer::class);
    }

    public function product_unit(){
        return $this->belongsTo(ProductUnit::class);
    }


}
