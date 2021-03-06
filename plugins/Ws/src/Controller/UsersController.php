<?php
namespace Ws\Controller;

use Ws\Controller\AppController;

class UsersController extends AppController
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
        $this->setEntity('Users');
        $this->setLabel('usuário');
    }
}
