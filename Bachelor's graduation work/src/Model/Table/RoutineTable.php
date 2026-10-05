<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class RoutineTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('routine');
        	$this->primaryKey('id');
    	
        	$this->hasMany('Eating', [
            	'foreignKey' => 'routineId',
            	'dependent' => true,
            	'cascadeCallbacks' => true,
        	]);
            $this->hasMany('Eatingprogram', [
                'foreignKey' => 'routine_id',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);
            $this->hasMany('Routineday', [
                'foreignKey' => 'routineId',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);

    	}

        public function validationDefault(Validator $validator)
        {
            return $validator
                ->notEmpty('name', 'Не введено название распорядка дня');
        }

	}