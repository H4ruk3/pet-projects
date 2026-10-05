<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class TrainingprogramdayTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('trainingprogramday');
        	$this->primaryKey('id');

            /*$this->belongsToMany ('trainingprogramdayexcersice', array(
                    'className'              => 'Excersice',
                 'joinTable'              => 'excersice_musculgroup',
                 'with'                   => '',
                'foreignKey'             => 'musculgroup_id',
                'associationForeignKey'  => 'excersice_id',
                'unique'                 => true
                )
            );*/
            $this->hasMany('trainingprogramday_exercise', [
                'foreignKey' => 'trainingprogramday_id',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);

            $this->hasMany('Diary', [
                'foreignKey' => 'trainingprogramday_id',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);            
    	}

        public function validationDefault(Validator $validator)
        {
            return $validator
                ->notEmpty('number', 'A number is required');
        }

	}