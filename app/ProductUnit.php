<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    //
    use RestTrait;

    protected $fillable = ['unit','quantity','amount','product_id'];

    protected $dates = ['created_at','updated_at'];

    public function getLabel()
    {
        return "$this->product->name unit: $this->unit" ;
    }


    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function invoice_items()
    {
        return $this->morphMany(InvoiceItem::class, 'concern');
    }

    public function offer_product_units(){
        return $this->hasMany(OfferProductUnit::class);
    }

    public function offers(){
        return $this->belongsToMany(Offer::class);
    }
}
