<?php

namespace App;

use App\Traits\RestTrait;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    use RestTrait;

    protected $fillable = ['amount','status','total_amount','payment_id', 'payment_responds','payment_method','payment_number','user_id'];

    protected $dates = ['created_at','updated_at'];

    public static $Status = ['new', 'pending', 'book', 'cancel'];

    public static $PaymentMethods = ['om','momo','yup'];

    public $casts=[
        'payment_responds'=>'array'
    ];

    public function getLabel()
    {
        return "$this->amount status: $this->status" ;
    }

    public function delivery(){
        return $this->hasOne(Delivery::class);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }
    

    public function invoice_items(){
        return $this->hasMany(InvoiceItem::class);
    }
}
