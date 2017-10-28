<?php
namespace Ws\Controller;

use Ws\Controller\AppController;

class ProductsController extends AppController
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
        $this->setEntity('Products');
        $this->setLabel('produto');
    }
}
