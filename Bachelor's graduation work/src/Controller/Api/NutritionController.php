<?php

namespace App\Controller\Api;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

use User;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Event\Event;

class NutritionController extends AppController
{

	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    	$this->Auth->allow();
    	$this->loadModel('Profiles');
    }

	public function index() {

	}

	public function getFoods() {
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$foodcategories = TableRegistry::get("Foodcategory")->find('all', ["contain" => ["foods" => ["conditions" => ["deleted" => 0]]], "conditions" => ["deleted" => 0]]);
			echo (json_encode($foodcategories));	
		}
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

            $id = $user->id;
    		//$program = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine'], 'conditions' => [ 'userId' => $id ] ]);

            $program = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine', "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'userId' => $id] ]);
            	//echo ($trainingprogram);
            	echo (json_encode($program));	
			//echo "OK";
		}	
	}

/**********************************************
Изменение активной программы питания для пользователя.
Параметр eatingprogram - идентификатор активируемой программы.
***********************************************/
	public function active() {
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$data = $this->request->input('json_decode');
			$res = [];
			echo($this->request->input);

			if ($data == null || !property_exists($data, "apiKey") || !property_exists($data, "eatingprogram") ) {
				$res['status'] = "error";
            	$res['mess'] = 'Неверные параметры запроса';
            	$this->response->statusCode(400);
			} else {
				$apiKey = $data->apiKey;
				$users = TableRegistry::get("Users")->find('all', ['conditions' => ['apiKey' => $apiKey]]);
				if ($users->count() == 0)
				{
					$res['status'] = "error";
            		$res['mess'] = 'Нет доступа';
            		$this->response->statusCode(203);
				} else {
					$eatingprograms = TableRegistry::get("Eatingprogram");
					$program = $eatingprograms->get($data->eatingprogram);
					if ($program == null) {
						$res['status'] = "error";
            			$res['mess'] = 'Неверный идентификатор программы питания';
            			$this->response->statusCode(404);
					} else {
						$user = $users->first();
						TableRegistry::get("Eatingprogram")->updateAll(['active' => 0], ['users_id' => $user->id, "routine_id" => $program->routine_id]);
						$program->active = true;
						if ($eatingprograms->save($program)) {
							$res['status'] = "success";
		            		$res['mess'] = "Активная программа изменена";	
						}
						else {
							$res['status'] = "error";
		            		$res['mess'] = "Не удалось изменить активную программу";	
		            		$response = $this->response->statusCode(500);
						}
					}

				}
			}
		} else {
			$res['status'] = "error";
		    $res['mess'] = 'Неверный запрос';
		    $this->response->statusCode(400);
		}
		$this->response->body(json_encode($res));
        return $this->response;	
	}
}