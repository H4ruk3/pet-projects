<?php

namespace App\Controller\Api;

use User;
use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;

class AuthController extends \App\Controller\AppController
{

	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    	$this->Auth->allow();
    }
/***********************************
  Авторизация пользователя
***********************************/
	public function login() {

		$this->autoRender = false;

		if ($this->request->is('post')) {

			$data = $this->request->input('json_decode');
			$res = [];

			if ($data == null || !property_exists($data, "username") || !property_exists($data, "password")) {
				$res['status'] = "error";
            	$res['mess'] = 'Неверные параметры запроса';
            	$this->response->statusCode(400);
			} else {
				$username = $data->username;
				$pass =  $data->password;
	            $user = $this->Users->find('all', [
		            		'conditions' => [
		            			'username' => $username,
		            		] 
		            	])
		            	->first();	

	            if ($user && (new DefaultPasswordHasher)->check($pass, $user->password)) {

	            	if (!$user->apiKey)	{

						$user->apiKey = sha1($username.$pass);
						$this->Users->save($user);            	
	            	}

	            	$res['status'] = "success";
	            	$res['apiKey'] = $user->apiKey;
	            }
	            else {
	            	$res['status'] = "error";
	            	$res['mess'] = 'Неверное имя пользователя или логин';
	            	$this->response->statusCode(203);
	            }
	        }
        
            $this->response->body(json_encode($res));
            return $this->response;
		} else {
			$res['status'] = "error";
	        $res['mess'] = 'Неверный запрос';
	        $this->response->statusCode(405);
	        $this->response->body(json_encode($res));
            return $this->response;
		}
	}

/***********************************
  Регистрация нового пользователя.
  !!! При регистрации создаётся пустой профиль пользователя со статусом active=false
***********************************/
	public function registry() {

		$this->autoRender = false;
		if ($this->request->is('post')) {

			$data = $this->request->input('json_decode');
			$res = [];
			if ($data == null || !property_exists($data, "username") || !property_exists($data, "password") || !property_exists($data, "accept")) {
				$res['status'] = "error";
            	$res['mess'] = 'Неверные параметры запроса';
            	$this->response->statusCode(400);
			} else 
				if (!$data->accept) {
					$res['status'] = "error";
            		$res['mess'] = 'Не приняты условия соглашения';
            		$this->response->statusCode(400);
				} else 
				{
					$user = $this->Users->newEntity();
					$user = $this->Users->PatchEntity($user, (array) $data);
					if ($user->errors()) {
							//echo json_encode($user->errors());
						$res['status'] = "error";
						$err = $user->errors();
						$message = "";
						foreach ($err as $key => $val)
							foreach ($val as $k => $v)
								$message .= $v . " ";
	            		$res['mess'] = $message;
						$response = $this->response->statusCode(400);
					}
					else {
						if ($this->Users->save($user)) {
							$profile = $this->Profiles->newEntity();
							$profile->userId = $user->id;
							if ($this->Profiles->save($profile)) {
								$res['status'] = "success";
	            				$res['mess'] = "Пользователь успешно создан";	
							} else {
								$this->Users->delete($user);
								$res['status'] = "error";
	            				$res['mess'] = "Не удалось создать профиль";	
	            				$response = $this->response->statusCode(500);
							}
						}
						else {
							$res['status'] = "error";	
							$res['message'] = "Пользователь с таким именем уже существует";
							$response = $this->response->statusCode(400);
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