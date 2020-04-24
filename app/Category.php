<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    use RestTrait;

    protected $fillable = ['name','description'];

    protected $dates = ['created_at','updated_at'];


    public function getLabel()
    {
        return "$this->name" ;
    }

    public function offers(){
        return $this->hasMany(Offer::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

}
