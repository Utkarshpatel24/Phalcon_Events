<?php

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;



class SecureController extends Controller
{
    
    public function indexAction($data = [])
    {

        // $controller = $this->router->getControllerName();
        // $action = $this->router->getActionName();
        $aclfile = APP_PATH. '/security/acl.cache';
        if (true != is_file($aclfile)) {
            $acl =new Memory();

            
            $acl->addRole('admin');
            
            $acl->allow('admin', '*', '*');
            

            file_put_contents(
                $aclfile,
                serialize($acl)
            );
        } else {
            $acl = unserialize(
                file_get_contents($aclfile)
            );

            if (count($data) > 0) {

                $comp = Components :: find();

                foreach($data as $key=>$val)
                {
                    //echo $key;

                    if ($key == 'role') {
                        $acl->addRole($val);
                    } else {
                        foreach($comp as $key1=>$val1)
                        {
                            // echo $val1->action."<br>";
                            $acl->addComponent(
                                $val1->controller,
                                [
                                    $val1->action
                                ]
                            );
                            if ($val1->id == $val) {

                                $acl->allow($data['role'], $val1->controller, $val1->action);

                            } else {
                                $acl->deny($data['role'], $val1->controller, $val1->action);
                            }
                        }
                    }

                }
                file_put_contents(
                    $aclfile,
                    serialize($acl)
                );
               // die;
            }
         
        }
        // $role =$this->request->getQuery("role");
        // $role = $role == ''? 'admin' : $role;
        // if (true === $acl->isAllowed($role, $controller, $action)) {
        //     echo "Access Granted";
        // } else {
        //     echo "Access Denied";
        //     die();
        // }
    
    }

    
}