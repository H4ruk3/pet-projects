<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class ExerciseTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('exercise');
        	$this->primaryKey('id');

            /*$this->hasMany('exercise_musculgroups', [
                'foreignKey' => 'exercise_id',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);*/

            $this->hasMany('exercise_musculgroup', [
                'foreignKey' => 'exercise_id',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);     

            $this->belongsToMany ('Musculgroups', array(
                    'className'              => 'Musculgroup',
                 'joinTable'              => 'exercise_musculgroup',
                 'with'                   => '',
                'foreignKey'             => 'exercise_id',
                'associationForeignKey'  => 'musculgroup_id',
                'unique'                 => true
                )
            );       
    	}

        public function validationDefault(Validator $validator)
        {
            return $validator
                ->notEmpty('name', 'A name is required')
                ->notEmpty('description', 'A description is required');
        }

	}