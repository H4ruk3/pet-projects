<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class MusculgroupTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('musculgroup');
        	$this->primaryKey('id');

            $this->belongsToMany ('Exercises', array(
                    'className'              => 'Exercise',
                 'joinTable'              => 'exercise_musculgroup',
                 'with'                   => '',
                'foreignKey'             => 'musculgroup_id',
                'associationForeignKey'  => 'excersice_id',
                'unique'                 => true
                )
            );
            /*$this->hasMany('excersise_musculgroups', [
                'foreignKey' => 'musculgroup_id',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);*/
    	}

        public function validationDefault(Validator $validator)
        {
            return $validator
                ->notEmpty('name', 'A name is required');
        }

	}