<?php

// namespace App\Listener;


use Phalcon\Di\Injectable;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;


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

    public function beforeHandleRequest($data)
    {
        //$data = $data->getData();
        
       
        $controller = $this->router->getControllerName();
        if($controller == null)
        $controller = '';
        $action = $this->router->getActionName();
        if($action == null)
        $action = '';
        $aclfile = APP_PATH. '/security/acl.cache';
        if (true != is_file($aclfile)) {
            $acl =new Memory();

            $acl->addRole('manager');
            $acl->addRole('admin');
            $acl->addComponent(
                'order',
                [
                    'orderlist'
                ]
            );
            $acl->allow('admin', '*', '*');
            

            file_put_contents(
                $aclfile,
                serialize($acl)
            );
        } else {
            $acl = unserialize(
                file_get_contents($aclfile)
            );
         
        }
        $role =$this->request->getQuery("role");
        $role = $role == ''? 'admin' : $role;
        if (true === $acl->isAllowed($role, $controller, $action)) {
            echo "Access Granted";
        } else {
            echo "Access Denied";
            die();
        }

    }

}