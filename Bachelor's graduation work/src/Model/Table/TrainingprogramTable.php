<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class TrainingprogramTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('trainingprogram');
        	$this->primaryKey('id');

            $this->hasMany('trainingprogramday', [
                'foreignKey' => 'trainingprogram_id',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);
            /*$this->belongsTo( 'trainingprogramdayexcersice', [
            'foreignKey' => 'creator_id',
            'className' => 'Users'
            ] );*/
    	}

        public function validationDefault(Validator $validator)
        {
            return $validator
                ->notEmpty('name', 'A name is required');
        }

	}