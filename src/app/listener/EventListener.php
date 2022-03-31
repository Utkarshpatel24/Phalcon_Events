<?php

// namespace App\Listener;


use Phalcon\Di\Injectable;



class EventListener extends Injectable
{
    public function setDefaultProduct($product)
    {

        // echo "reached";
        // echo $product->getData()->name;
        // // die();
        $product = $product->getData();
        $setting = Setting :: findFirst();
        if ($setting->title == 'with tag') {
            $product->name = $product->name . $product->tags;
        }
        if ($product->price == '' || $product->price == '0') {
            $product->price = $setting->price;
        }
        if ($product->stock == '' || $product->stock == '0') {
            $product->stock = $setting->stock;
        }
        return $product;

    }

    public function setDefaultOrder($order)
    {
        $order = $order->getData();
        $setting = Setting :: findFirst();
        if ($order->zipcode == '') {
            $order->zipcode = $setting->zipcode;
        }
        return $order;
    }

}