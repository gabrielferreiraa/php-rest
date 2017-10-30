<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Orders Model
 *
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\OrderProductsTable|\Cake\ORM\Association\HasMany $OrderProducts
 *
 * @method \App\Model\Entity\Order get($primaryKey, $options = [])
 * @method \App\Model\Entity\Order newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Order[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Order|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Order patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Order[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Order findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrdersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('orders');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('OrderProducts', [
            'foreignKey' => 'order_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }

    public function getOrdersByCompany($companyId)
    {
        $response = $this
            ->find()
            ->contain(['OrderProducts.Products'])
            ->leftJoin(['c' => 'companies'], ['c.id = Orders.company_id'])
            ->where([
                'c.id' => $companyId
            ]);

        if (!empty($response)) {
            $response = $response->toArray();

            return $response;
        }

        return [];
    }

    public function search($data)
    {
        $response = $this
            ->find()
            ->contain(['OrderProducts.Products'])
            ->leftJoin(['c' => 'companies'], ['c.id = Orders.company_id']);

        if(isset($data['cnpj']) && !empty($data['cnpj'])) {
            $response->where([
                "c.cnpj::text ILIKE '%" . $data['cnpj'] ."%'"
            ]);
        }

        if(isset($data['order']) && !empty($data['order'])) {
            $response->where([
                "Orders.id::text ILIKE '%" . $data['order'] . "%'"
            ]);
        }

        if (!empty($response)) {
            $response = $response->toArray();

            return $response;
        }

        return [];
    }
}
