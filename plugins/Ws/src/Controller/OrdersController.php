<?php
namespace Ws\Controller;

use Ws\Controller\AppController;

class OrdersController extends AppController
{
    use Traits\CrudTrait {
        add as public;
        view as public;
        edit as public;
    }

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->setEntity('Orders');
        $this->setEntityContains(['OrderProducts']);
        $this->setLabel('pedido');
    }
}
