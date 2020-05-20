<?php

namespace App\Providers;

use App\Invoice;
use App\InvoiceItem;
use App\Receipt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        InvoiceItem::created(function (InvoiceItem $bp) {
            $this->update_invoice_amount($bp->invoice);
        });

         Receipt::saving(function (Receipt $r) {
             $user = Auth::user();
           if($r->seller_was!=$user->full_name)
               $r->seller_was.=$user->full_name;
        });

        InvoiceItem::creating(function (InvoiceItem $ii) {
            $ii->price_was = $ii->concern->amount;
        });
    }


    private function update_invoice_amount(Invoice $invoice)
    {
        $invoice->amount = $invoice->invoice_items()->get()
            ->sum(function ($p) {
                return $p->price_was * $p->quantity;
            });
//        $invoice->amount *= (1 - $invoice->discount);
        $invoice->save();
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
