<?php
namespace Ws\Controller;

use Ws\Controller\AppController;

class CompaniesController extends AppController
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
        $this->setEntity('Companies');
        $this->setLabel('empresa');
    }
}
