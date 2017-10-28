<?php

namespace Ws\Controller\Traits;

use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\TableRegistry;

trait CrudTrait
{
    protected $Entity = null;
    protected $EntityContains = null;
    protected $Label = 'registro';

    public function setEntity($entity)
    {
        $this->Entity = TableRegistry::get($entity);
    }

    public function setEntityContains($contains)
    {
        $this->EntityContains = $contains;
    }

    public function setLabel($label)
    {
        $this->Label = $label;
    }

    public function getEntity()
    {
        return $this->Entity;
    }

    public function getEntityContains()
    {
        return $this->EntityContains;
    }

    public function getLabel()
    {
        return $this->Label;
    }

    public function index()
    {
        $EntityTable = $this->getEntity();
        $find = 'all';

        $query = $this->request->getQuery();

        $hasFinder = isset($query['find']) && !empty($query['find']);
        if($hasFinder) {
            $find = $query['find'];
        }

        $response = $EntityTable->find($find);

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    public function view($id = null)
    {
        $EntityTable = $this->getEntity();

        $entity = $EntityTable
            ->find()
            ->where([
                "{$EntityTable->alias()}.id" => $id
            ])
            ->first();

        $hasRegister = !empty($entity);
        if (!$hasRegister) {
            throw new NotFoundException(ucfirst($this->getLabel()) . " não encontrado(a)");
        }

        $response = [
            'status' => 'success',
            'data' => $entity
        ];

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $EntityTable = $this->getEntity();
            $post = $this->request->getData();

            $entity = $EntityTable->newEntity();
            $entity = $EntityTable->patchEntity($entity, $post);

            if (!$EntityTable->save($entity)) {
                throw new InternalErrorException("Problema ao salvar {$this->getLabel()}, por favor tente novamente");
            }

            $response = [
                'status' => 'success',
                'message' => ucfirst($this->getLabel()) . ' salvo(a) com sucesso',
                'data' => $entity
            ];

            $this->set(compact('response'));
            $this->set('_serialize', ['response']);
        }
    }

    public function edit($id = null)
    {
        if ($this->request->is(['patch', 'post', 'put'])) {
            $EntityTable = $this->getEntity();

            $entity = $EntityTable->find();

            if ($this->getEntityContains()) {
                $entity->contain($this->getEntityContains());
            }

            $post = $this->request->getData();
            $entity = $EntityTable->patchEntity($entity, $post);

            $entity = $entity
                ->where([
                    "{$EntityTable->alias()}.id" => $id
                ])
                ->first();

            $hasRegister = !empty($entity);
            if (!$hasRegister) {
                throw new NotFoundException(ucfirst($this->getLabel()) . " não encontrado(a)");
            }

            if (!$EntityTable->save($entity)) {
                throw new InternalErrorException("Problema ao salvar {$this->getLabel()}, por favor tente novamente");
            }

            $response = [
                'status' => 'success',
                'message' => ucfirst($this->getLabel()) . ' editado(a) com sucesso',
                'data' => $entity
            ];

            $this->set(compact('response'));
            $this->set('_serialize', ['response']);
        }
    }
}