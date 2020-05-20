<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    //
    use RestTrait;

    protected $fillable = ['name','status','picture','type','amount','description','category_id'];

    protected $dates = ['created_at','updated_at'];

    public static $Status = ['enable', 'disable', 'upcomming'];

    public static $Type = ['DUO', 'TRIO','QUATOR'];

    public function  __construct(array $attributes = [])
    {
        $this->files = ['picture'];
        parent::__construct($attributes);
    }


    public function getLabel()
    {
        return "$this->name" ;
    }

    public function getPictureAttribute($val)
    {
        if($val==null){
            return null;
        }
        return env('APP_URL').$val;
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function invoice_items()
    {
        return $this->morphMany(InvoiceItem::class, 'concern');
    }

    public function offer_product_units(){
        return $this->hasMany(OfferProductUnit::class);
    }

    public function product_units(){
        return $this->belongsToMany(ProductUnit::class,'offer_product_units');
    }
}
