<?php

namespace App\Controller\Api;

use User;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Validation\Validator;

class RoutineController extends AppController {

	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    	$this->Auth->allow();
    	$this->loadModel('Routine');
    	$this->loadModel('Eats');
    }

/********************************************
  Получение всех распорядков дня для пользователя
***********************************************/
    public function getAll() {
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
					$model = TableRegistry::get("Routine")
						->find('all', ['contain' => ['Routineday'=>['Eating']], "conditions" => ["userId" => $user->id]]);
					$res = $model;
				}
			}
		}  else {
			$res['status'] = "error";
		    $res['mess'] = 'Неверный запрос';
		    $this->response->statusCode(400);
		}
		$this->response->body(json_encode($res));
        return $this->response;	
	}

/********************************************
  Получение активного распорядка дня для пользователя
***********************************************/
	public function getActive() {
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

					$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $user->id, 'active' => 1]] );

					if ($routines->count() == 0) {
						$res['status'] = "error";
	            		$res['mess'] = 'Активный распорядок не найден';
	            		$this->response->statusCode(404);
	            	} else {
	            		$res = $routines->first();
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

/********************************************
  Получение распорядка дня по id
***********************************************/
    public function get($id) {

    	$this->autoRender = false;

    	$data = $this->request->input('json_decode');
    	$user = $this->getUser($data->apiKey);

    	if ($user == NULL) {

    		exit;
    	}

		$model = TableRegistry::get("Routine")
			->find('all', ['contain' => ['Routineday'=>['Eating']], "conditions" => [ "id" => $id, "userId" => $user->id ]])
			->first();

		echo json_encode($model); 
	}


/********************************************
  Сохранение нового распорядка дня
***********************************************/
	public function create() {

		$this->autoRender = false;
		if ($this->request->is('post')) {
			$res = [];
	    	$data = $this->request->input('json_decode');
	    	if ($data == null || !property_exists($data, "apiKey") || !property_exists($data, "name") || !property_exists($data, "routineday") || count($data->routineday)!=2) 
			{
				$res['status'] = "error";
	            $res['mess'] = '1Неверные параметры запроса';
	            $res['data'] = $data;
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
					$day1times = array();
					list($h, $m) = explode(":", $data->routineday[0]->wakeupTime);
					$day1times['wakeup'] = date("H:i", mktime($h, $m));
					list($h, $m) = split(":", $data->routineday[0]->trainTime);
					$day1times['train'] = date("H:i", mktime($h, $m));
					list($h, $m) = split(":", $data->routineday[0]->trainDuration);
					$day1times['duration'] = date("H:i", mktime($h, $m));
					list($h, $m) = split(":", $data->routineday[0]->sleepTime);
					$day1times['sleep'] = date("H:i", mktime($h, $m));
					$day2times = array();
					list($h, $m) = split(":", $data->routineday[1]->wakeupTime);
					$day2times['wakeup'] = date("H:i", mktime($h, $m));
					list($h, $m) = split(":", $data->routineday[1]->sleepTime);
					$day2times['sleep'] = date("H:i", mktime($h, $m));
					$routines = TableRegistry::get("Routine");
					$routine = $routines->newEntity();
					$routine->name = trim($data->name);
					$routine->userId = $user->id;
					if ($routine->errors()) {
						$res['status'] = "error";
						$err = $user->errors();
						$message = "";
						foreach ($err as $key => $val)
							foreach ($val as $k => $v)
								$message .= $v . " ";
	            		$res['mess'] = $message;
						$response = $this->response->statusCode(400);
					} else {
						if ($routines->save($routine)) {
							//Сохраняем данные первого дня.
							$routinedays = TableRegistry::get("Routineday");
							$routineday = $routinedays->newEntity();
							$routineday->type = 1;
							$routineday->eatCount = $data->routineday[0]->eatCount;
							$routineday->wakeupTime = $day1times['wakeup'];
							$routineday->trainTime = $day1times['train'];
							$routineday->sleepTime = $day1times['sleep'];
							$routineday->trainDuration = $day1times['duration'];
							$routineday->routineId = $routine->id;
							if ($routineday->errors()) {
								$routines->delete($routine);
								$res['status'] = "error";
								$err = $user->errors();
								$message = "";
								foreach ($err as $key => $val)
									foreach ($val as $k => $v)
										$message .= $v . " ";
			            		$res['mess'] = $message;
								$response = $this->response->statusCode(400);
							} else {
								if ($routinedays->save($routineday)) {
									$eatsTable = TableRegistry::get("Eating");
									$isOk = true;
									for ($i = 0; $i < count($data->routineday[0]->eating); $i++) {
										list($h, $m) = split(":", $data->routineday[0]->eating[$i]->time);
										$eatTime = date("H:i", mktime($h, $m));
										$eatTable = $eatsTable->newEntity();
										$eatTable->time = $eatTime;
										$eatTable->routineId = $routine->id;
										$eatTable->routinedayId = $routineday->id;
										if ($eatTable->errors()) {
											$isOk=false;
											$routinedays->delete($routineday);
											$routines->delete($routine);
											$res['status'] = "error";
											$err = $user->errors();
											$message = "";
											foreach ($err as $key => $val)
												foreach ($val as $k => $v)
													$message .= $v . " ";
						            		$res['mess'] = $message;
											$response = $this->response->statusCode(400);
										} else {
											if (!$eatsTable->save($eatTable)) {
												$routinedays->delete($routineday);
												$routines->delete($routine);
												$res['status'] = "error";
												$res['mess'] = "Не удалось сохранить распорядок";	
										        $response = $this->response->statusCode(500);
										        $isOk = false;
											}
										} 
									}
									if ($isOk) {
										//Сохраняем данные второго дня.
										$routineday = $routinedays->newEntity();
										$routineday->type = 2;
										$routineday->eatCount = $data->routineday[1]->eatCount;
										$routineday->wakeupTime = $day2times['wakeup'];
										$routineday->sleepTime = $day2times['sleep'];
										$routineday->routineId = $routine->id;
										if ($routineday->errors()) {
											$routines->delete($routine);
											$res['status'] = "error";
											$err = $user->errors();
											$message = "";
											foreach ($err as $key => $val)
												foreach ($val as $k => $v)
													$message .= $v . " ";
									        $res['mess'] = $message;
											$response = $this->response->statusCode(400);
										} else {
											if ($routinedays->save($routineday)) {
												$eatsTable = TableRegistry::get("Eating");
												for ($i = 0; $i < count($data->routineday[0]->eating); $i++) {
													list($h, $m) = explode(":", $data->routineday[0]->eating[$i]->time);
													$eatTime = date("H:i", mktime($h, $m));
													$eatTable = $eatsTable->newEntity();
													$eatTable->time = $eatTime;
													$eatTable->routineId = $routine->id;
													$eatTable->routinedayId = $routineday->id;
													if ($eatTable->errors()) {
														$res['status'] = "error";
														$err = $user->errors();
														$message = "";
														foreach ($err as $key => $val)
															foreach ($val as $k => $v)
																$message .= $v . " ";
												        $res['mess'] = $message;
														$response = $this->response->statusCode(400);
														$isOk = false;
													} else {
														if (!$eatsTable->save($eatTable)) {
															$routinedays->delete($routineday);
															$routines->delete($routine);
															$res['status'] = "error";
															$res['mess'] = "Не удалось сохранить распорядок";	
														    $response = $this->response->statusCode(500);
														    $isOk = false;
														}
													}
												}
												if ($isOk) {
													$res['status'] = "success";
													$res['mess'] = "Распорядок дня сохранён успешно";
												} else {
													$res['status'] = "error";
													$res['mess'] = "Не удалось сохранить распорядок";
													$response = $this->response->statusCode(500);
												}
											} else {
												$routines->delete($routine);
												$res['status'] = "error";
											    $res['mess'] = "Не удалось сохранить распорядок";	
											    $response = $this->response->statusCode(500);
											}
										}
									} else {
										$res['status'] = "error";
										$res['mess'] = "Не удалось сохранить распорядок";
										$response = $this->response->statusCode(500);
									}
								}	
								else {
									$routines->delete($routine);
									$res['status'] = "error";
							        $res['mess'] = "Не удалось сохранить распорядок";	
							        $response = $this->response->statusCode(500);
								}
							}
						}
						else {
							$routines->delete($routine);
							$res['status'] = "error";
					        $res['mess'] = "Не удалось сохранить распорядок";	
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

	public function edit($id) {

		$this->autoRender = false;
		$data = $this->request->input('json_decode', true);

		$user = $this->getUser($data['apiKey']);
    	if ($user == NULL) {
    		exit;
    	}

    	$routine = TableRegistry::get("Routine")
			->find('all', ['contain' => ['Routineday'=>['Eating']], "conditions" => [ "id" => $id, "userId" => $user->id ]])
			->first();

		$this->Routine->patchEntity($routine, $data);
		$this->Routine->save($routine);
		
		$this->Eats->deleteAll([
    		'routineId' => $routine->id,
		]);

		foreach ($data['eats'] as $time) {
			$eat = $this->Eats->newEntity();
			$eat->time = $time;
			$eat->routineId = $routine->id;
			$this->Eats->save($eat);
		}

		echo $this->ok();
	}

	public function delete($id) {
		
		$this->autoRender = false;
		$data = $this->request->input('json_decode', true);

		$user = $this->getUser($data['apiKey']);
    	if ($user == NULL) {
    		exit;
    	}

    	$model = TableRegistry::get("Routine")
			->find('all', ['contain' => ['Routineday'=>['Eating']], "conditions" => [ "id" => $id, "userId" => $user->id ]])
			->first();

		if ($model) {
			$this->Eats->deleteAll([
				"routineId" => $model->id
			]);
			$this->Routine->delete($model);			
		}

		echo $this->ok();
	}
}