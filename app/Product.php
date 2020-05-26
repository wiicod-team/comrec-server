<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    use RestTrait;

    protected $fillable = ['name','picture','category_id'];

    protected $dates = ['created_at','updated_at'];


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

    public function product_units(){
        return $this->hasMany(ProductUnit::class);
    }

}
