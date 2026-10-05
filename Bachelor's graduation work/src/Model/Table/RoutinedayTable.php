<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class RoutinedayTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('routineday');
        	$this->primaryKey('id');
    	
        	$this->hasOne('Routine', ['className' => 'Routine', 'foreignKey' => 'id', 'bindingKey' => 'routineId']);

            $this->hasMany('Eating', [
                'foreignKey' => 'routinedayId',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);

    	}

        public function validationDefault(Validator $validator)
        {
            return $validator
                ->notEmpty('type', 'Не указан тип дня');
        }

	}