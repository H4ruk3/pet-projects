<?php

namespace App\Controller\Api;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

use User;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Event\Event;

class TrainingprogramController extends AppController
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

            $trainingprogram = TableRegistry::get("Trainingprogram")
			->find('all', ['contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]] , "conditions" => ["users_id" => $user->id]]);

            	//echo ($trainingprogram);
            	echo (json_encode($trainingprogram));	
			//echo "OK";
		}	
	}

/**********************************************
Изменение активной программы тренировок для пользователя.
Параметр trainingprogram - идентификатор активируемой программы.
***********************************************/
	public function active() {
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$data = $this->request->input('json_decode');
			$res = [];
			echo($this->request->input);

			if ($data == null || !property_exists($data, "apiKey") || !property_exists($data, "trainingprogram") ) {
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
					$program = $this->Trainingprogram->get($data->trainingprogram);
					if ($program == null) {
						$res['status'] = "error";
            			$res['mess'] = 'Неверный идентификатор программы тренировок';
            			$this->response->statusCode(404);
					} else {
						$user = $users->first();
						TableRegistry::get("Trainingprogram")->updateAll(['active' => 0], ['users_id =' => $user->id]);
						$program->active = true;
						if ($this->Trainingprogram->save($program)) {
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

	public function getcurrent() {
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
			$trainingprogram = TableRegistry::get("Trainingprogram")
			->find('all', ['contain' => ['trainingprogramday'=>[
            'trainingprogramday_exercise' => ['Exercise' => ['Musculgroups']]]] , "conditions" => ["users_id" => $user->id, "active" => 1]]);

            	echo (json_encode($trainingprogram->first()));	
            }
	}
}