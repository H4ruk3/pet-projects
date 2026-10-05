<?php

namespace App\Controller\Api;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

use User;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Event\Event;

class ExerciseController extends AppController
{

	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    	$this->Auth->allow();
    	$this->loadModel('Profiles');
    }

	public function index() {

	}

	public function view() {
		$this->autoRender = false;
		if ($this->request->is('post')) {

			$response = [];
			$data = $this->request->input('json_decode');
			$apiKey = $data->apiKey;
			$user = $this->Users->find('all', ['conditions' => ['apiKey' => $apiKey]])->first();			
			
			if (!$user) {
				echo '{"status":"error", "mess": "User not found"}';
				exit();
			}

            $exercise = TableRegistry::get("Exercise")
			->find('all', ['contain' => ['Musculgroups' => ['conditions' => ['deleted' => 0]]], 'conditions' => ['deleted' => 0]]);

            	//echo ($trainingprogram);
            	echo (json_encode($exercise));	
			//echo "OK";
		}	
	}
}