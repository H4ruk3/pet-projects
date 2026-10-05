<?php

namespace App\Controller\Api;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

use User;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Log\Log;

class ProfileController extends AppController
{

	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    	$this->Auth->allow();
    	$this->loadModel('Profiles');
    }

	public function index() {

	}

/********************************************
  Создание нового профиля
***********************************************/
	public function create() {
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$request = file_get_contents('php://input');
			$reqProfile = json_decode($request);
			if ($reqProfile == null || !property_exists($reqProfile, "apiKey") || !property_exists($reqProfile, "name") || !property_exists($reqProfile, "fam") || !property_exists($reqProfile, "sex") || !property_exists($reqProfile, "birthday") || !property_exists($reqProfile, "growth") || !property_exists($reqProfile, "weight")) 
			{
				$res['status'] = "error";
            	$res['mess'] = 'Неверные параметры запроса';
            	$this->response->statusCode(400);
			} else {
				$apiKey = $reqProfile->apiKey;
				$users = TableRegistry::get("Users")->find('all', ['conditions' => ['apiKey' => $apiKey]]);
				if ($users->count() == 0)
				{
					$res['status'] = "error";
            		$res['mess'] = 'Нет доступа';
            		$this->response->statusCode(203);
				} else {
					$user = $users->first();	
					$profile = $this->Profiles->newEntity();
					$profile->userId = $user->id;
					$profile->active = true;
					if ($profile->errors()) {
						$res['status'] = "error";
						$err = $user->errors();
						$message = "";
						foreach ($err as $key => $val)
							foreach ($val as $k => $v)
								$message .= $v . " ";
	            		$res['mess'] = $message;
						$response = $this->response->statusCode(400);
					} else 
						if ($this->Profiles->save($profile)) {
							$res['status'] = "success";
	            			$res['mess'] = "Профиль успешно создан";	
						}
						else {
							$res['status'] = "error";
	            			$res['mess'] = "Не удалось создать профиль";	
	            			$response = $this->response->statusCode(500);
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

/***********************************************
  Получение данных профиля.
**********************************************/
	public function view() {
		$this->autoRender = false;
		if ($this->request->is('post')) {
			$res = [];
			$data = $this->request->input('json_decode');
			if ($data == null || !property_exists($data, "apiKey")) 
			{
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
					$user = $users->first();	

					$profiles = $this->Profiles->find()
						->where(['userId' => $user->id]);
					if ($profiles->count() == 0) {
						$profile = $this->Profiles->newEntity();
						$profile->userId = $user->id;
						if ($this->Profiles->save($profile)) {
							$res = $profile;	
						} else {
							$res['status'] = "error";
	            			$res['mess'] = "Не удалось создать профиль";	
	            			$response = $this->response->statusCode(404);
						}
					}
					$profile = $profiles->first();
					if ($profile->sex == "male")
						$profile->sex = 1;
					else 
						$profile->sex = 2;
					$profile->age = $this->getFullYears($profile->birthday);
					if ($profile->birthday == null) $profile->birthday = "";
					if ($profile->growth == null) $profile->growth = 0;
					if ($profile->weight == null) $profile->weight = 0;
					if ($profile->aimTrain == null) $profile->aimTrain = 0;
					if ($profile->somatotype == null) $profile->somatotype = 0;
					$res = $profile;	
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

/***********************************************
  Вспомогательная функция для вычисления возраста.
**********************************************/
	private function getFullYears($birthdayDate) {
		if ($birthdayDate != null && $birthdayDate != "") {
        $interval = $birthdayDate->diff(new \DateTime(date("Y-m-d")));
        return $interval->format("%Y");
    	}
    	else
    		return 0;
	}

/***********************************************
  Изменение данных профиля.
**********************************************/
	public function edit() {
		$this->autoRender = false;

		if ($this->request->is('post')) {

			$response = [];
			$res = [];
			$data = $this->request->input('json_decode');
			Log::write('debug', $data);
			if ($data == null || !property_exists($data, "apiKey")) 
			{
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
					$user = $users->first();			
					$profiles = $this->Profiles->find()
						->where(['userId' => $user->id]);
					if ($profiles->count() == 0) {
						$res['status'] = "error";
            			$res['mess'] = 'Профиль не создан';
            			$this->response->statusCode(404);
					} else {
						$profile = $profiles->first();
						Log::write('debug', $profile);
						$profile = $this->Profiles->PatchEntity($profile, (array) $data);
						if ($profile->errors()) {
							$res['status'] = "error";
							$err = $user->errors();
							$message = "";
							foreach ($err as $key => $val)
								foreach ($val as $k => $v)
									$message .= $v . " ";
		            		$res['mess'] = $message;
							$response = $this->response->statusCode(400);
						} else { 
							if ($this->Profiles->save($profile)) {
								$res['status'] = "success";
		            			$res['mess'] = "Профиль успешно обновлён";	
							}
							else {
								$res['status'] = "error";
		            			$res['mess'] = "Не удалось обновить профиль";	
		            			$response = $this->response->statusCode(500);
							}
						}
					}
				}
			}
		}
		else {
			$res['status'] = "error";
		    $res['mess'] = 'Неверный запрос';
		    $this->response->statusCode(400);
		}
		$this->response->body(json_encode($res));
        return $this->response;	
	}

}