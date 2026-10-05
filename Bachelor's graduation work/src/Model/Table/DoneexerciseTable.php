<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;
    

	class DoneexerciseTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('doneexercise');
        	$this->primaryKey('id');

        	//$this->belongsToMany('Exercise');
        	//$this->belongsTo('Diary');
            $this->belongsTo('Diary', [
        'foreignKey' => 'diary_id',
        'joinType' => 'LEFT'
    ]);
            /*$this->hasMany('Doneset', [
                'table' => 'doneset'
            ]);*/
            $this->hasMany('Doneexerciseset', [
                'foreignKey' => 'doneexercise_id',
                //'dependent' => true,
                //'cascadeCallbacks' => true,
            ]);
        }
    }