<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class FoodTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('food');
        	$this->primaryKey('id');

            $this->hasOne('foodcategory');

            $this->hasMany('routineeatingmenu', [
                'foreignKey' => 'food_id',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);

            $this->belongsToMany ('Eatings', array(
                    'className'              => 'Eating',
                 'joinTable'              => 'routineeatingmenu',
                 'with'                   => '',
                'foreignKey'             => 'food_id',
                'associationForeignKey'  => 'eating_id',
                'unique'                 => true
                )
            );
    	}

        public function validationDefault(Validator $validator)
        {
            return $validator
                ->notEmpty('name', 'A number is required');
        }

	}