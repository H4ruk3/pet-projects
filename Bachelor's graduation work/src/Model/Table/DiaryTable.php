<?php

	namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class DiaryTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('diary');
        	$this->primaryKey('id');

        	/*$this->belongsTo('Trainingprogramday', array(
        		    'className'              => 'Trainingprogramday',
                'foreignKey'             => 'trainingprogramday_id',
                'associationForeignKey'  => 'id',
                'unique'                 => true )
        );*/
        $this->belongsTo('Trainingprogramday');
        /*$this->hasOne('Trainingprogramday', ['className' => 'Trainingprogramday', 'foreignKey' => 'id', 'bindingKey' => 'trainingprogramday_id']);*/
        
        //$this->hasMany('Doneexercise');
        $this->hasMany('Doneexercise', [
        'foreignKey' => 'diary_id',
        'joinType' => 'LEFT'
    ]);

        }
    }