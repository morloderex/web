<?php

namespace App\Traits\Model;


use App\Events\Mail\Order\Complete;
use App\Order;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Omnipay;
use Omnipay\Common\CreditCard;

trait purchaseAble
{
    public function purchase(CreditCard $creditCard, int $qty = 1)
    {
        // Card should be set by CreditCard method called in ShopController
        $status = Omnipay::purchase([
            'amount' => $qty,
            'returnUrl' => route('shop.thanks'),
            'cancelUrl' => route('shop.checkout'),
            'card'      => $creditCard->getParameters()
        ]);
        
        if($status) {
            $order = Order::create([
                'name'              =>  $this->name ?: $this->title,
                'user_id'           =>  Auth::user()->id,
                'orderable_type'    =>  get_class($this),
                'orderable_id'      =>  $this->getKey(),
                'orderable_key'     =>  method_exists($this, 'generateKey') ? $this->generateKey() : null
            ]);
            
            if(App::environment('production'))
                event(new Complete($order, Auth::user()));
        }
        
        return $status;
    }
}