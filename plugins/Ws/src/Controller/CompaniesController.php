<?php
namespace Ws\Controller;

use Cake\ORM\TableRegistry;
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

        $this->Companies = TableRegistry::get('Companies');
    }

    public function getCompanyAndOrders()
    {
        $response = $this->Companies->getCompanyAndOrders();

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
}
