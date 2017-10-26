<?php

namespace Ws\Controller\Traits;

use Cake\Network\Exception\InternalErrorException;
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

    public function index() {
        $EntityTable = $this->getEntity();

        $response = $EntityTable->find('all');

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $TableEntity = $this->getEntity();
            $post = $this->request->getData();

            $entity = $TableEntity->newEntity();
            $entity = $TableEntity->patchEntity($entity, $post);

            if(!$TableEntity->save($entity)) {
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
        $EntityTable = $this->getEntity();

        $entity = $EntityTable->find();

        if ($this->getEntityContains()) {
            $entity->contain($this->getEntityContains());
        }

        $entity = $entity
            ->where([
                "{$EntityTable->alias()}.id" => $id
            ])
            ->first();

        $hasRegister = !empty($entity);

        $response = [
            'status' => 'error',
            'message' => "Problema ao editar {$this->getLabel()}, por favor tente novamente",
            'data' => []
        ];

        if ($hasRegister) {
            if ($this->request->is(['patch', 'post', 'put'])) {
                $post = $this->request->getData();

                $entity = $EntityTable->patchEntity($entity, $post);

                if ($EntityTable->save($entity)) {
                    $response = [
                        'status' => 'success',
                        'message' => ucfirst($this->getLabel()) . ' editada com sucesso',
                        'data' => $entity
                    ];
                }
            }
        }

        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
}