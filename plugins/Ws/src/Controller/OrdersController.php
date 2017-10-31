<?php
namespace Ws\Controller;

use Aura\Intl\Exception;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
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

    public function add()
    {
        if ($this->request->is(['post'])) {
            $data = $this->request->getData();

            $newData = [];
            $lastCompanyId = '';
            foreach ($data as $value) {
                $isSameCompany = $lastCompanyId !== '' && $lastCompanyId !== $value['company_id'];
                if($isSameCompany) {
                    throw new InternalErrorException('Para cadastrar um pedido é permitido apenas 1 empresa por vez');
                }

                $newData = [
                    'company_id' => $value['company_id'],
                    'products' => [
                        '_ids' => Hash::extract($data, '{n}.product_id')
                    ]
                ];

                $lastCompanyId = $value['company_id'];
            }

            $entity = $this->Orders->newEntity();
            $entity = $this->Orders->patchEntity($entity, $newData);

            if (!$this->Orders->save($entity)) {
                throw new InternalErrorException("Problema ao salvar pedido, por favor tente novamente");
            }

            $response = [
                'status' => 'success',
                'message' => 'Pedido salvo(a) com sucesso',
                'data' => $entity
            ];

            $this->set(compact('response'));
            $this->set('_serialize', ['response']);
        }
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
