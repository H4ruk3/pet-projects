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

class DiaryController extends AppController
{

	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    	$this->Auth->allow();
    	$this->loadModel('Diary');
    	$this->loadModel('Doneexercise');
    	$this->loadModel('Doneexerciseset');
    }

    public function adddiaryday() {
    	$this->autoRender = false;
    	$res = [];
		if ($this->request->is('post')) {
			$request = file_get_contents('php://input');
			Log::write('debug', $request);
			$diarydaydata = json_decode($request);
			if ($diarydaydata == null || !property_exists($diarydaydata, "apiKey") || !property_exists($diarydaydata, "date") || !property_exists($diarydaydata, "trainingprogramday_id")) 
			{
				$res['status'] = "error";
            	$res['mess'] = 'Неверные параметры запроса';
            	$this->response->statusCode(400);
			} else {
				$apiKey = $diarydaydata->apiKey;
				$users = TableRegistry::get("Users")->find('all', ['conditions' => ['apiKey' => $apiKey]]);
				if ($users->count() == 0)
				{
					$res['status'] = "error";
            		$res['mess'] = 'Нет доступа';
            		$this->response->statusCode(203);
				} else {
					$user = $users->first();	
					$diaryday = $this->Diary->find("all", ["conditions" => ["date" => $diarydaydata->date]]);
					$day = null;
					if ($diaryday->count() > 0)
						$day = $diaryday->first();
					else
						$day = $this->Diary->newEntity();
					$day->users_id = $user->id;
					$day->trainingprogramday_id = $diarydaydata->trainingprogramday_id;
					$day->date = $diarydaydata->date;
					$this->Diary->save($day);
					$res['status'] = "success";
		    		$res['mess'] = 'День добавлен к дневнику.';
		    		$res['id'] = $day->id;
				}
			}
		} else {
			$res['status'] = "error";
		    $res['mess'] = 'Неверный запрос';
		    $this->response->statusCode(400);
		}
		$this->response->body(json_encode($res));
		Log::write('debug', $this->Doneexercise->lastQuery());
        return $this->response;	
    }

    public function adddiarydayresults() {
    	$this->autoRender = false;
    	$res = [];
		if ($this->request->is('post')) {

			$request = file_get_contents('php://input');
						Log::write('debug', $request);
			$diarydaydata = json_decode($request);
			if ($diarydaydata == null || !property_exists($diarydaydata, "apiKey") || !property_exists($diarydaydata, "id") || !property_exists($diarydaydata, "date")) 
			{
				$res['status'] = "error";
            	$res['mess'] = 'Неверные параметры запроса';
            	$this->response->statusCode(400);
			} else {
				$apiKey = $diarydaydata->apiKey;
				$users = TableRegistry::get("Users")->find('all', ['conditions' => ['apiKey' => $apiKey]]);
				if ($users->count() == 0)
				{
					$res['status'] = "error";
            		$res['mess'] = 'Нет доступа';
            		$this->response->statusCode(203);
				} else {
					$user = $users->first();	
					$diaryday = $this->Diary->find("all", ["conditions" => ["id" => $diarydaydata->id]]);
					if ($diaryday->count() == 0) {
						$res['status'] = "error";
            			$res['mess'] = 'День тренировки не найден';
            			$this->response->statusCode(404);
					}
					else {
						//$res['status'] = "success";
		    			//$res['mess'] = 'Всё ОК!!!';
		    			$day = $diaryday->first();
		    			//Очищаем все данные, связанные с данным днём
		    			$doneex = $this->Doneexercise->find("all", ["conditions" => ["diary_id" => $day->id]]);
		    			foreach ($doneex as $key => $ex) {
		    				$this->Doneexerciseset->deleteAll(["doneexercise_id" => $ex->id]);
		    			}
		    			$this->Doneexercise->deleteAll(["diary_id" => $day->id]);
		    			//Начинаем добавлять данные 
		    			
		    			foreach ($diarydaydata->doneexercise as $key => $ex) {
		    				$doneex = $this->Doneexercise->newEntity();
		    				$doneex->diary_id = $diarydaydata->id;
		    				$doneex->exercise_id = $ex->exercise_id;
		    				$doneex->trainingdayexercise_id = $ex->trainingdayexercise_id;

		    				$this->Doneexercise->save($doneex);
		    				foreach ($ex->doneexerciseset as $key => $set) {
		    					$newset = $this->Doneexerciseset->newEntity();
		    					$newset->doneexercise_id = $doneex->id;
		    					$newset->approach = $set->approach;
		    					$newset->repeat = $set->repeat;
		    					$newset->weight = $set->weight;
		    					$this->Doneexerciseset->save($newset);		
		    				}	
		    			}
		    			$day->trainingprogramday_id = $diarydaydata->trainingprogramday_id;
		    			$day->mark = $diarydaydata->mark;
		    			$this->Diary->save($day);
		    			$res['status'] = "success";
		    			$res['mess'] = 'Данные дневника успешно добавлены.';
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

    public function adddiarydayresultsbydate() {
    	$this->autoRender = false;
    	$res = [];
		if ($this->request->is('post')) {
			$request = file_get_contents('php://input');
			Log::write('debug', $request);
			$diarydaydata = json_decode($request);
			if ($diarydaydata == null || !property_exists($diarydaydata, "apiKey") || !property_exists($diarydaydata, "trainingprogramday_id") || !property_exists($diarydaydata, "date")) 
			{
				$res['status'] = "error";
            	$res['mess'] = 'Неверные параметры запроса';
            	$this->response->statusCode(400);
			} else {
				$apiKey = $diarydaydata->apiKey;
				$users = TableRegistry::get("Users")->find('all', ['conditions' => ['apiKey' => $apiKey]]);
				if ($users->count() == 0)
				{
					$res['status'] = "error";
            		$res['mess'] = 'Нет доступа';
            		$this->response->statusCode(203);
				} else {
					$user = $users->first();	
					$diaryday = $this->Diary->find("all", ["conditions" => ["date" => $diarydaydata->date, "users_id" => $user->id]]);
					$day = null;
					if ($diaryday->count() == 0) {
						$day = $this->Diary->newEntity();
						$day->users_id = $user->id;
						$day->trainingprogramday_id = $diarydaydata->trainingprogramday_id;
						$day->date = $diarydaydata->date;
						$this->Diary->save($day);
						/*$res['status'] = "error";
            			$res['mess'] = 'День тренировки не найден';
            			$this->response->statusCode(404);*/
					}
					else {
						//$res['status'] = "success";
		    			//$res['mess'] = 'Всё ОК!!!';
		    			$day = $diaryday->first();
		    		}
		    			//Очищаем все данные, связанные с данным днём
		    			$doneex = $this->Doneexercise->find("all", ["conditions" => ["diary_id" => $day->id]]);
		    			foreach ($doneex as $key => $ex) {
		    				$this->Doneexerciseset->deleteAll(["doneexercise_id" => $ex->id]);
		    			}
		    			$this->Doneexercise->deleteAll(["diary_id" => $day->id]);
		    			//Начинаем добавлять данные 
		    			
		    			foreach ($diarydaydata->doneexercise as $key => $ex) {
		    				$doneex = $this->Doneexercise->newEntity();
		    				$doneex->diary_id = $day->id;
		    				$doneex->exercise_id = $ex->exercise_id;
		    				$doneex->trainingdayexercise_id = $ex->trainingdayexercise_id;
		    				$this->Doneexercise->save($doneex);
		    			//Log::write('debug', $this->Doneexercise->lastQuery());
		    				foreach ($ex->doneexerciseset as $key => $set) {
		    					$newset = $this->Doneexerciseset->newEntity();
		    					$newset->doneexercise_id = $doneex->id;
		    					$newset->approach = $set->approach;
		    					$newset->repeat = $set->repeat;
		    					$newset->weight = $set->weight;
		    					$this->Doneexerciseset->save($newset);		
		    				}	
		    			}
		    			$day->mark = $diarydaydata->mark;
		    			$this->Diary->save($day);
		    			$res['status'] = "success";
		    			$res['mess'] = 'Данные дневника успешно добавлены.';
					
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


    public function getdiaryresults() {
    	$this->autoRender = false;
    	$res = [];
		if ($this->request->is('post')) {
			$request = file_get_contents('php://input');
			$diarydaydata = json_decode($request);
			if ($diarydaydata == null || !property_exists($diarydaydata, "apiKey") || !property_exists($diarydaydata, "startdate") || !property_exists($diarydaydata, "enddate")) 
			{
				$res['status'] = "error";
            	$res['mess'] = 'Неверные параметры запроса';
            	$this->response->statusCode(400);
			} else {
				$apiKey = $diarydaydata->apiKey;
				$users = TableRegistry::get("Users")->find('all', ['conditions' => ['apiKey' => $apiKey]]);
				if ($users->count() == 0)
				{
					$res['status'] = "error";
            		$res['mess'] = 'Нет доступа';
            		$this->response->statusCode(203);
				} else {
					$user = $users->first();
					$diarydays = $this->Diary->find("all", ["conditions" => ["date >=" => $diarydaydata->startdate, "date <=" => $diarydaydata->enddate, "users_id" => $user->id], "contain" => ["Doneexercise" => ["Doneexerciseset"]]]);
					//$diaryday = $this->Diary->find("all", ["conditions" => ["id" => $diarydaydata->id]]);
				
		    		$res['data'] = $diarydays->toArray();
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

    public function getnewdiarydays() {
    	$this->autoRender = false;
    	$res = [];
		if ($this->request->is('post')) {
			$request = file_get_contents('php://input');
			$diarydaydata = json_decode($request);
			if ($diarydaydata == null || !property_exists($diarydaydata, "apiKey")) 
			{
				$res['status'] = "error";
            	$res['mess'] = 'Неверные параметры запроса';
            	$this->response->statusCode(400);
			} else {
				$apiKey = $diarydaydata->apiKey;
				$users = TableRegistry::get("Users")->find('all', ['conditions' => ['apiKey' => $apiKey]]);
				if ($users->count() == 0)
				{
					$res['status'] = "error";
            		$res['mess'] = 'Нет доступа';
            		$this->response->statusCode(203);
				} else {
					$user = $users->first();
					$diarydays = $this->Diary->find("all", ["conditions" => ["date >=" => date("Y-m-d"), "users_id" => $user->id]]);
					//$diaryday = $this->Diary->find("all", ["conditions" => ["id" => $diarydaydata->id]]);
				
		    		$res['data'] = $diarydays->toArray();
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

    public function addeating() {
    	$this->autoRender = false;
    	$res = [];
		if ($this->request->is('post')) {
			$request = file_get_contents('php://input');
			$diarydaydata = json_decode($request);
			if ($diarydaydata == null || !property_exists($diarydaydata, "apiKey") || !property_exists($diarydaydata, "eatings")) 
			{
				$res['status'] = "error";
            	$res['mess'] = 'Неверные параметры запроса';
            	$this->response->statusCode(400);
			} else {
				$apiKey = $diarydaydata->apiKey;
				$users = TableRegistry::get("Users")->find('all', ['conditions' => ['apiKey' => $apiKey]]);
				if ($users->count() == 0)
				{
					$res['status'] = "error";
            		$res['mess'] = 'Нет доступа';
            		$this->response->statusCode(203);
				} else {
					$user = $users->first();
					$eatingdiary = TableRegistry::get("Eatingdiary");
					foreach($diarydaydata->eatings as $key => $eating) {
						$eatingrecord = $eatingdiary->newEntity();
						$eatingrecord->date = $eating->date;
						$eatingrecord->eating_id = $eating->eating_id;
						$eatingrecord->food_id = $eating->food_id;
						$eatingrecord->eatingprogram_id = $eating->eatingprogram_id;
						$eatingrecord->day_number = $eating->day_number;
						$eatingrecord->cnt = $eating->cnt;
						$eatingrecord->time = $eating->time;
						$eatingrecord->users_id = $user->id;
						$eatingdiary->save($eatingrecord);
					}
					//$diaryday = $this->Diary->find("all", ["conditions" => ["id" => $diarydaydata->id]]);
					$res['status'] = "success";
		    		$res['mess'] = 'Данные о питании успешно добавлены.';
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

    private function formateatingprogram($program, $bgunorm) {
        $obj = new \stdClass;
            $obj->id = $program->id;
            $obj->name = $program->name;
            $obj->active = $program->active;
            $obj->routine_id = $program->routine_id;
            $key2 = [];
            $key2[$program->routine->routineday[0]->id] = 0;
            $key2[$program->routine->routineday[1]->id] = 1;
            $key3 = [];
            $key4 = [];
            for ($ii=0; $ii < 2; $ii++)
                foreach ($program->routine->routineday[$ii]->eating as $key => $value) 
                    $key3[$value->id] = $program->routine->routineday[$ii]->id;        
                

            $days=[];
            $foods = [];
            foreach ($program->routineeatingmenu as $key => $menu) {
                if (!array_key_exists($menu->day_number, $key4)) {
                    //for ($ii=0; $ii < 2; $ii++)
                    $ii = $key2[$key3[$menu->eating_id]];
                    $data = [];
                    $key4[$menu->day_number] = count($days);
                    foreach ($program->routine->routineday[$ii]->eating as $key => $value) {
                        //$days[$menu->day_number][(int)$value->id] = (object)['foods' => [], 'eating' => $value, "number" => $key];
                        $data[$key] = (object)['foods' => [], 'eating' => $value, "number" => $key, "norm" => $bgunorm];
                        /*$days[$menu->day_number][$key] = (object)['foods' => [], 'eating' => $value, "number" => $key];*/
                        $keys[$value->id] = $key;
                    }
                    $days[count($days)] = (object)["day_number" => $menu->day_number, "data" => $data];
                }
                //$days[$menu->day_number][(int)$menu->eating_id]->foods[count($days[$menu->day_number][(int)$menu->eating_id]->foods)] = $menu;
                if (!array_key_exists($menu->food->id, $foods)) 
                    $foods[$menu->food->id] = clone $menu->food;
                $menu->food->colories = round($menu->cnt/100 * $menu->food->colories, 2);
                $menu->food->hidrocarbonats = round($menu->cnt/100 * $menu->food->hidrocarbonats, 2);
                $menu->food->fats = round($menu->cnt/100 * $menu->food->fats, 2);
                $menu->food->proteins = round($menu->cnt/100 * $menu->food->proteins, 2);
                
                //$days[$menu->day_number][$keys[$menu->eating_id]]->foods[count($days[$menu->day_number][$keys[$menu->eating_id]]->foods)] = $menu;
                $days[$key4[$menu->day_number]]->data[$keys[$menu->eating_id]]->foods[count($days[$key4[$menu->day_number]]->data[$keys[$menu->eating_id]]->foods)] = ["plan" => $menu];
            }
            $obj->days = $days;
            $obj->norm = $bgunorm;
            foreach($obj->days[0]->data as $key => $value) {
                $total = (object)["fats" => 0, "hidrocarbonats" => 0, "proteins" => 0, "colories" => 0];
                /*foreach($value->foods as $key2 => $value2) {
                    $total->fats += $value2["plan"]->food->fats;
                    $total->hidrocarbonats += $value2["plan"]->food->hidrocarbonats;
                    $total->proteins += $value2["plan"]->food->proteins;
                    $total->colories += $value2["plan"]->food->colories;
                }*/
                $value->total = $total;
            }
            //$eatings[$program->id] = $obj;
            $obj->foods = $foods;
            return $obj;
    }

    private function formatresults($data, $norm) {
        $obj = new \stdClass;
            $obj->id = $data[0]->eatingprogram->id;
            $obj->name = $data[0]->eatingprogram->name;
            $obj->active = $data[0]->eatingprogram->active;
            $obj->routine_id = $data[0]->eatingprogram->routine_id;
            /*$key2 = [];
            $key2[$data[0]->eatingprogram->routine->routineday[0]->id] = 0;
            $key2[$data[0]->eatingprogram->routine->routineday[1]->id] = 1;
            $key3 = [];
            $key4 = [];*/
            /*for ($ii=0; $ii < 2; $ii++)
                foreach ($data[0]->eatingprogram->routine->routineday[$ii]->eating as $key => $value) 
                    $key3[$value->id] = $data->eatingprogram[0]->routine->routineday[$ii]->id;        */
            $days=[];
            $day = new \stdClass;
            $day->day_number = $data[0]->day_number;
            $eatingdata=[];
            $eatings = [];
            $foods = [];
            foreach($data as $key => $value) {
                if (!array_key_exists($value->eating_id, $eatings)) {
                    $eatings[$value->eating_id] = count($eatingdata);   
                    $eatingdata[count($eatingdata)] = (object)[];
                    $eatingdata[$eatings[$value->eating_id]]->eating = $value->eating;
                    $eatingdata[$eatings[$value->eating_id]]->foods = [];
                    $eatingdata[$eatings[$value->eating_id]]->norm = $norm;
                    $eatingdata[$eatings[$value->eating_id]]->number = $eatings[$value->eating_id];
                }
                $foodobjreal = (object)[];
                if (!array_key_exists($value->food->id, $foods)) 
                    $foods[$value->food->id] = clone $value->food;
                $foodobjreal->food = $value->food;
                $foodobjreal->cnt = $value->cnt;
                $foodobjreal->food->colories = round($foodobjreal->cnt/100 * $foodobjreal->food->colories, 2);
                $foodobjreal->food->proteins = round($foodobjreal->cnt/100 * $foodobjreal->food->proteins, 2);
                $foodobjreal->food->hidrocarbonats = round($foodobjreal->cnt/100 * $foodobjreal->food->hidrocarbonats, 2);
                $foodobjreal->food->fats = round($foodobjreal->cnt/100 * $foodobjreal->food->fats, 2);

                $foodobjplan = null;

                $eatingprogram = TableRegistry::get("Routineeatingmenu")->find('all', ['contain' => ["Food"], 'conditions' => [ 'eating_id' => $value->eating_id, 'food_id' => $value->food_id, 'day_number' => $value->day_number, "eatingprogram_id" => $data[0]->eatingprogram->id] ]);
                if ($eatingprogram->count() > 0) {

                    

                    $eatingprogram = $eatingprogram->first();
                    $foodobjplan = (object)[];
                    $foodobjplan->food = $eatingprogram->food;
                    $foodobjplan->cnt = $eatingprogram->cnt;
                    /*echo($value->eating_id . "   " . $value->food_id . "   " . $value->day_number. "   =>    ");
                    echo($eatingprogram->cnt . "\n");    */
                    $foodobjplan->food->colories = round($foodobjplan->cnt/100 * $foodobjplan->food->colories, 2);
                $foodobjplan->food->proteins = round($foodobjplan->cnt/100 * $foodobjplan->food->proteins, 2);
                $foodobjplan->food->hidrocarbonats = round($foodobjplan->cnt/100 * $foodobjplan->food->hidrocarbonats, 2);
                $foodobjplan->food->fats = round($foodobjplan->cnt/100 * $foodobjplan->food->fats, 2);
                }

                
                $arr = ["real" => $foodobjreal];
                if ($foodobjplan != null) {
                    $arr["plan"] = $foodobjplan;
                }


                $eatingdata[$eatings[$value->eating_id]]->foods[count($eatingdata[$eatings[$value->eating_id]]->foods)] = $arr;
            }
            $day->data = $eatingdata;
            $days[count($days)] = $day;
        $obj->days = $days;
        $obj->norm = $norm;
        $totalallday = (object)["fats" => 0, "hidrocarbonats" => 0, "proteins" => 0, "colories" => 0];
        foreach($obj->days[0]->data as $key => $value) {
                $total = (object)["fats" => 0, "hidrocarbonats" => 0, "proteins" => 0, "colories" => 0];
                foreach($value->foods as $key2 => $value2) {
                    $total->fats += $value2["real"]->food->fats;
                    $total->hidrocarbonats += $value2["real"]->food->hidrocarbonats;
                    $total->proteins += $value2["real"]->food->proteins;
                    $total->colories += $value2["real"]->food->colories;
                    $totalallday->fats += $value2["real"]->food->fats;
                    $totalallday->hidrocarbonats += $value2["real"]->food->hidrocarbonats;
                    $totalallday->proteins += $value2["real"]->food->proteins;
                    $totalallday->colories += $value2["real"]->food->colories;
                }
                $value->total = $total;
            }
        $obj->total = $totalallday;
        $obj->foods = $foods;
        return $obj;
        
    }

    public function getEatingdiary() {
        //$this->autoRender = false;
        //$uid = $this->Auth->user('id');
        $this->autoRender = false;
    	$res = [];
		if ($this->request->is('post')) {
			$request = file_get_contents('php://input');
			$reqdata = json_decode($request);
			if ($reqdata == null || !property_exists($reqdata, "apiKey") || !property_exists($reqdata, "date")) 
			{
				$res['status'] = "error";
            	$res['mess'] = 'Неверные параметры запроса';
            	$this->response->statusCode(400);
			} else {
				$apiKey = $reqdata->apiKey;
				$users = TableRegistry::get("Users")->find('all', ['conditions' => ['apiKey' => $apiKey]]);
				if ($users->count() == 0)
				{
					$res['status'] = "error";
            		$res['mess'] = 'Нет доступа';
            		$this->response->statusCode(203);
				} else {
        			$uid = $users->first()->id;
        			$date = $reqdata->date;
        			$profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $uid, 'active' => true ] ]);
        			$profile = $profiles->first();
        			$this->loadComponent('Diet');

        			$activityCoef = $this->Diet->getActivityCoef($profile);
        			$age = $this->Diet->getAge($profile);
        			$NB = $this->Diet->Harris_Benedict($profile->weight, $profile->growth, $age, $profile->sex, $activityCoef);
        			$NB += $this->Diet->getAimOffset($profile);
        			$this->Diet->setAveKkal($NB);
        			$bgunorm = $this->Diet->PFC_for_day($profile->somatotype, $profile->weight);
        			$bgunorm["Kkal"] = round($NB, 2);
        			$bgunorm["aveCaCf"] = round($bgunorm["aveCaCf"], 2);
        			$bgunorm["aveFtCf"] = round($bgunorm["aveFtCf"], 2);
        			$bgunorm["avePrCf"] = round($bgunorm["avePrCf"], 2);
        			$existingEating = TableRegistry::get("Eatingdiary")->find('all', ["contain" => ["Eatingprogram", "Eating", "Food"], "conditions" => ["Eatingdiary.date" => $date]]);
        			if ($existingEating->count() > 0) {
        			    //$this->set("data", $existingEating->toArray());
        			    echo "{\"status\" : \"success\", \"data\" : " . json_encode($this->formatresults($existingEating->toArray(), $bgunorm)) . "}";
        			}
        			else {
        			    $routine = TableRegistry::get("Routine")->find('all', ["conditions" => 
        			        ["active" => true, "userId" => $uid]])->first();
        			    if ($routine == null) {
        			        echo "{\"status\" : \"error\", \"message\" : \"Не выбрано ни одного активного распорядка дня. Выберите активный распорядок дня для просмотра программы питания.\"}";    
            			} else {
            			    $eatingprograms = TableRegistry::get("Eatingprogram")->find('all', ['conditions' => [ 'users_id' => $uid, 'routine_id' => $routine->id, 'active' => true] ]);
                				if ($eatingprograms->count() > 0) {
                				    $eatingprogram = $eatingprograms->first();
                				    $date1 = new \DateTime($eatingprogram->date);
                				    $date2 = new \DateTime($date);
                				    $diffbetween = $date2->diff($date1)->format("%a");
                				    //echo($date1->format("Y-m-d"));
                				    //echo($date2->format("Y-m-d"));
                				    //echo($diffbetween);
                				    $daycnt = TableRegistry::get("Routineeatingmenu")->find('all', ['fields' => array('day_number' => 'MAX(Routineeatingmenu.day_number)'), 'conditions' => [ 'eatingprogram_id' => $eatingprogram->id]])->first();
                    				$daycnt->day_number+=1;
                    				$curdate = ($diffbetween + 2) % $daycnt->day_number;
                    				//echo ($diffbetween + 2) . " / " . $daycnt->day_number . " = " .  $curdate;
                    				$eatingprogram = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ['conditions' => ['day_number' => $curdate], "Food", "Eating"]], 'conditions' => [ 'users_id' => $uid, 'Eatingprogram.id' => $eatingprogram->id] ]);
                    				echo "{\"status\" : \"success\", \"data\" : " . json_encode($this->formateatingprogram($eatingprogram->first(), $bgunorm)) . "}";
                					} else {
                    					echo "{\"status\" : \"error\", \"message\" : \"Не выбрано ни одной активной программы питания.\"}";    
                					}
            			}
        			}
        		}
        	}
        }
    }
}

