<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class FoodcategoryTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('foodcategory');
        	$this->primaryKey('id');

            
            $this->hasMany('foods', [
                'className' => 'Food',
                'foreignKey' => 'category_id',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);
    	}

        public function validationDefault(Validator $validator)
        {
            return $validator
                ->notEmpty('name', 'A name is required');
        }

	}