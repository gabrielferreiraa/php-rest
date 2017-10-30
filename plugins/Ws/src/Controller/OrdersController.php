<?php
namespace Ws\Controller;

use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
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

        $this->Orders = TableRegistry::get('Orders');
    }

    public function getOrdersByCompany()
    {
        $query = $this->request->getQuery();

        $isEmpty = !isset($query['companyId']) || empty($query['companyId']);
        if ($isEmpty) {
            throw new NotFoundException("Parâmetro 'companyId' não encontrado");
        }

        $response = $this->Orders->getOrdersByCompany($query['companyId']);

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    public function search()
    {
        $data = $this->request->getData();

        if (!isset($data['values'])) {
            throw new NotFoundException("Parâmetro 'values' não encontrado");
        }

        $response = $this->Orders->search($data['values']);

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
}
