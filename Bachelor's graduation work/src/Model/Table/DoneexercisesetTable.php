<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class DoneexercisesetTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('doneexerciseset');
        	$this->primaryKey('id');

            $this->belongsTo('Doneexercise', [
                'foreignKey' => 'doneexercise_id',
                'className' => 'Doneexercise'
               ]
           );

        	//$this->belongsToMany('Doneexercise');
        }
    }