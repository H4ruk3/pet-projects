<?php

namespace App\Controller;

use User;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Controller\Component\DietComponent;
use Cake\Event\Event;
use Cake\Validation\Validator;

class NutritionprogramController extends AppController
{
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if ($this->Auth->user("role") == "corp" || $this->Auth->user("role") == "trainer")
            $this->viewBuilder()->layout('corpuser');
        else    //if ($this->Auth->user("role") == "admin") 
			if ($this->Auth->user("role") == "admin") 
				$this->viewBuilder()->layout('admin');
			else
			if ($this->request->admin)
                if ($this->Auth->user("role") == "admin") 
                    $this->viewBuilder()->layout('admin');
                else 
                    throw new UnauthorizedException();
            else    

            $this->viewBuilder()->layout('redesignmain');
        $this->set("section", "eating");
    }

    private function formateatingprogram($program) {
        $obj = new \stdClass;
            $obj->id = $program->id;
            $obj->name = $program->name;
            $obj->date_active = $program->date_active;
            $obj->lastmodified = $program->lastmodified;
            $obj->active = $program->active;
            $obj->routine_id = $program->routine_id;
            $obj->routine_name = $program->routine->name;
            $key2 = [];
            $key2[$program->routine->routineday[0]->id] = 0;
            $key2[$program->routine->routineday[1]->id] = 1;
            $key3 = [];
            for ($ii=0; $ii < 2; $ii++)
                foreach ($program->routine->routineday[$ii]->eating as $key => $value) 
                    $key3[$value->id] = $program->routine->routineday[$ii]->id;        
                

            $days=[];
            foreach ($program->routineeatingmenu as $key => $menu) {
                if (!array_key_exists($menu->day_number, $days)) {
                    //for ($ii=0; $ii < 2; $ii++)
                    $ii = $key2[$key3[$menu->eating_id]];
                    foreach ($program->routine->routineday[$ii]->eating as $key => $value) {
                        //$days[$menu->day_number][(int)$value->id] = (object)['foods' => [], 'eating' => $value, "number" => $key];
                        $days[$menu->day_number][$key] = (object)['foods' => [], 'eating' => $value, "number" => $key, "day_type" => $ii];
                        $keys[$value->id] = $key;
                    }
                }
                //$days[$menu->day_number][(int)$menu->eating_id]->foods[count($days[$menu->day_number][(int)$menu->eating_id]->foods)] = $menu;
                $menu->food->colories = round($menu->cnt/100 * $menu->food->colories, 2);
                $menu->food->hidrocarbonats = round($menu->cnt/100 * $menu->food->hidrocarbonats, 2);
                $menu->food->fats = round($menu->cnt/100 * $menu->food->fats, 2);
                $menu->food->proteins = round($menu->cnt/100 * $menu->food->proteins, 2);
                $days[$menu->day_number][$keys[$menu->eating_id]]->foods[count($days[$menu->day_number][$keys[$menu->eating_id]]->foods)] = $menu;
            }
            $obj->days = $days;
            //$eatings[$program->id] = $obj;
            return $obj;
    }
    

    private function checkvCorrespondingRoutine($uid, $rid) {
        $routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $uid ]] );

        $routine = TableRegistry::get("Routine")->get($rid, ['contain' => ['Routineday'=>['Eating']]] );
        foreach ($routines as $key => $value) {
            if (count($value->routineday) == count($routine->routineday)) {
                $correct = true;
                for($i = 0; $i < count($value->routineday); $i++) {
                    if ($value->routineday[$i]->eatCount != $routine->routineday[$i]->eatCount)
                        $correct = false;
                }
                if ($correct)
                    return $value->id;
            }
        }
        return -1;
    }

    public function index() {
    	$id = $this->Auth->user('id');
    	$eatingprograms = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'users_id' => $id] ]);
    	$eatings = [];
    	$keys = [];

    	foreach ($eatingprograms as $key => $program) {
    		$eatings[$program->id] = $this->formateatingprogram($program);
		}
		
		$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
		if ($id != $admin->first()->id) {
			$templates = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'users_id' => $admin->first()->id] ]);
			$templateinfo = [];
			foreach ($templates as $key => $program) {
				
                $templateinfo[$program->id] = $this->formateatingprogram($program);
                //$routine = TableRegistry::get("Routine")->get($program->routine_id, ['contain' => ['Routineday'=>['Eating']]] );
            //$this->set("routine", $routine->routineday);
                if ($this->checkvCorrespondingRoutine($id, $program->routine_id) < 0) {
                    $templateinfo[$program->id]->can_use = false;
                } else 
                $templateinfo[$program->id]->can_use = true;
			}
			$this->set("template", $templateinfo);
		}
        $activeroutine = TableRegistry::get("Routine")->find("all", ["conditions" => ["userId" => $id, "active" => true]]);
        $this->set("activeroutineid", $activeroutine->first()->id);
		$this->set("eatingprograms", $eatings);
    	$this->set("title", "Программа питания");
    }

    public function active($routine_id, $prog_id) {

		/*$this->autoRender = false;

		if ($this->request->is('post')) {*/
			$id = $this->Auth->user('id');
			//$r = $this->request->input();
			//$data = json_decode($this->request->input());
			//var_dump($data);
			$programs = TableRegistry::get("Eatingprogram");
			//var_dump($this->request->input());
			$program = $programs->get($prog_id);
			TableRegistry::get("Eatingprogram")->updateAll(['active' => 0], ['routine_id =' => $routine_id, "users_id =" => $id]);
			$program->active = true;
            $program->date_active = (new \DateTime())->format("Y-m-d H:i:s");
			$programs->save($program);
			return $this->redirect(['action' => 'index']);	
			//echo $this->ok();
		//}
	}

    public function create() {
    	$id = $this->Auth->user('id');
    	$profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $id, 'active' => true ] ]);
    	$profile = $profiles->first();
        if ($profile != null) {
    	   $this->loadComponent('Diet');

    	   $activityCoef = $this->Diet->getActivityCoef($profile);
    	   $age = $this->Diet->getAge($profile);
    	   $NB = $this->Diet->Harris_Benedict($profile->weight, $profile->growth, $age, $profile->sex, $activityCoef);
    	   $NB += $this->Diet->getAimOffset($profile);
    	   $this->Diet->setAveKkal($NB);

    	   $bgunorm = $this->Diet->PFC_for_day($profile->somatotype, $profile->weight);
            $bgunorm["Kkal"] = $NB;
            $this->set("bgunorm", $bgunorm);
        }
    	//$foods = TableRegistry::get("Food")->find('all', [ ]);
    	//$this->set("foods", $foods);
        $admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
        $ids = [$id, $admin->first()->id];
        $foodcategories = TableRegistry::get("Foodcategory")->find('all', ["contain" => ["foods" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
        $this->set("foodcategories", $foodcategories);
    	$id = $this->Auth->user('id');
    	//$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Eating'], 'conditions' => [ 'userId' => $id ] ]);
    	$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $id ]] );
        if ($routines->count() == 0) {
            $this->Flash->error("У вас не создано ни одного распорядка дня. Без этого невозможно создание программ питания. Добавьте новый распорядок дня.");
            return $this->redirect(['action' => 'index']);
        }
    	$this->set("routines", $routines);
    	
    	$this->set("test", "test");


    	if ($_POST) {

			$id = $this->Auth->user('id');
			$programs = TableRegistry::get("Eatingprogram");
			$program = $programs->newEntity();
			$program->name = trim($_POST['name']);
			$program->routine_id = $_POST['routine'];
            $program->users_id = $id;
            $program->lastmodified = (new \DateTime())->format("Y-m-d H:i:s"); 
			$validator = $programs->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			
			$validate = $this->validateInput();
			$eatings = TableRegistry::get("routineeatingmenu");

			if (empty($errors) && $validate && $programs->save($program)) {
					foreach($_POST['foods'] AS $daynum => $v){
						//print_r($v);
						foreach($v AS $eatingid => $foods) {
							//echo $eatingid;
							//print_r ($foods);
							foreach ($foods as $key => $value) {
								$eating = $eatings->newEntity();
								$eating->eatingprogram_id = $program->id;
								$eating->eating_id = $eatingid;
								//echo($eating->eating_id);
								$eating->food_id = $value[0];
								$eating->day_number = $daynum;
								$eating->cnt = $value[1];
								$eatings->save($eating);
							}
						}
					}
			}
			return $this->redirect(['action' => 'index']);
		}


    }

    public function createtmp($tmp) {
        $id = $this->Auth->user('id');
        $profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $id, 'active' => true ] ]);
        $profile = $profiles->first();
        $this->loadComponent('Diet');

        $activityCoef = $this->Diet->getActivityCoef($profile);
        $age = $this->Diet->getAge($profile);
        $NB = $this->Diet->Harris_Benedict($profile->weight, $profile->growth, $age, $profile->sex, $activityCoef);
        $NB += $this->Diet->getAimOffset($profile);
        $this->Diet->setAveKkal($NB);

        $bgunorm = $this->Diet->PFC_for_day($profile->somatotype, $profile->weight);
        $bgunorm["Kkal"] = $NB;
        $admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
        $ids = [$id, $admin->first()->id];
        $foodcategories = TableRegistry::get("Foodcategory")->find('all', ["contain" => ["foods" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
        $this->set("foodcategories", $foodcategories);
        $id = $this->Auth->user('id');
        //$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Eating'], 'conditions' => [ 'userId' => $id ] ]);
        $routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $id ]] );

        $program = TableRegistry::get("Eatingprogram")->get($tmp, ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ["Food", "Eating"]]]);

        if ($program->users_id != $id) {
            $equalroutines = $this->checkroutine($program->routine_id, $id);
            if (count($equalroutines) == 0) {
                $this->Flash->error("У пользователя для которого создаётся программа питания нет ни одного распорядка дня соответствующего распорядку дня в шаблоне.");
                //return $this->redirect('/admin/nutritionprogram');
                return $this->redirect(['action' => 'index']);
            }  else 
                $program->routine_id = $equalroutines[0]->id;  
        }
        
        /*$this->set("equal", $equalroutines);
        $program->routine_id = $equalroutines[0]->id;*/

        $obj = $this->formateatingprogram($program);

        $this->set("routines", $routines);
        $this->set("bgunorm", /*"{'NB': $NB, 'CMA': $CMA}"*/$bgunorm);
        $this->set("program", $obj);
        $this->set("test", "test");
        


        if ($_POST) {

            $id = $this->Auth->user('id');
            $programs = TableRegistry::get("Eatingprogram");
            $program = $programs->newEntity();
            $program->name = trim($_POST['name']);
            $program->routine_id = $_POST['routine'];
            $program->users_id = $id;
            $program->lastmodified = (new \DateTime())->format("Y-m-d H:i:s"); 
            $validator = $programs->validationDefault(new Validator());
            $errors = $validator->errors($this->request->data());
            
            $validate = $this->validateInput();
            $eatings = TableRegistry::get("routineeatingmenu");

            if (empty($errors) && $validate && $programs->save($program)) {
                    foreach($_POST['foods'] AS $daynum => $v){
                        //print_r($v);
                        foreach($v AS $eatingid => $foods) {
                            //echo $eatingid;
                            //print_r ($foods);
                            foreach ($foods as $key => $value) {
                                $eating = $eatings->newEntity();
                                $eating->eatingprogram_id = $program->id;
                                $eating->eating_id = $eatingid;
                                //echo($eating->eating_id);
                                $eating->food_id = $value[0];
                                $eating->day_number = $daynum;
                                $eating->cnt = $value[1];
                                $eatings->save($eating);
                            }
                        }
                    }
            }
            return $this->redirect(['action' => 'index']);
        }

        $this->render('create');
    }
    private function validateInput() {
    	/*for ($i = 0; $i < count($_POST['exercise']); $i++) 
    		if (count($_POST['exercise'][$i+1]) == 0)
    			return false;*/
    	return true;
    }

    public function indexold() {

    }

    public function view($id) {
    	$uid = $this->Auth->user('id');
    	$eatingprogram = TableRegistry::get("Eatingprogram")->get($id, ['contain' => ['Routine', "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'users_id' => $uid] ]);
    	$this->set("eatings", $eatingprogram);
    }

    public function delete($id) {
    	$this->autoRender = false;

		$programs = TableRegistry::get("Eatingprogram");
		$eatingmenus = TableRegistry::get("Routineeatingmenu");
		$program = $programs->get($id);
		$eatingmenus->deleteAll(['eatingprogram_id' => $program->id]);
		$programs->delete($program);
		return $this->redirect(['action' => 'index']);
    }

    public function edit($id) {
    	$uid = $this->Auth->user('id');
    	$program = TableRegistry::get("Eatingprogram")->get($id, ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'users_id' => $uid] ]);
    	//$eatings = [];
    	$keys = [];
    		/*$obj = new \stdClass;
    		$obj->id = $program->id;
    		$obj->name = $program->name;
    		$obj->active = $program->active;
    		$obj->routine_id = $program->routine_id;
    		$days=[];
    		foreach ($program->routineeatingmenu as $key => $menu) {
    			if (!array_key_exists($menu->day_number, $days)) {
                    for ($ii=0; $ii < 2; $ii++)
	    			foreach ($program->routine->routineday[$ii]->eating as $key => $value) {
	    				//$days[$menu->day_number][(int)$value->id] = (object)['foods' => [], 'eating' => $value, "number" => $key];
	    				$days[$menu->day_number][$key] = (object)['foods' => [], 'eating' => $value, "number" => $key];
	    				$keys[$value->id] = $key;
	    			}
	    		}
    			//$days[$menu->day_number][(int)$menu->eating_id]->foods[count($days[$menu->day_number][(int)$menu->eating_id]->foods)] = $menu;
    			$menu->food->colories = $menu->cnt/100 * $menu->food->colories;
    			$menu->food->hidrocarbonats = $menu->cnt/100 * $menu->food->hidrocarbonats;
    			$menu->food->fats = $menu->cnt/100 * $menu->food->fats;
    			$menu->food->proteins = $menu->cnt/100 * $menu->food->proteins;
    			$days[$menu->day_number][$keys[$menu->eating_id]]->foods[count($days[$menu->day_number][$keys[$menu->eating_id]]->foods)] = $menu;
    		}
    		$obj->days = $days;
    		//$eatings[$program->id] = $obj;
            */
        $obj = $this->formateatingprogram($program);

    	$uid = $this->Auth->user('id');
    	$profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $uid, 'active' => true ] ]);
    	$profile = $profiles->first();
    	$this->loadComponent('Diet');

    	$activityCoef = $this->Diet->getActivityCoef($profile);
    	$age = $this->Diet->getAge($profile);
    	$NB = $this->Diet->Harris_Benedict($profile->weight, $profile->growth, $age, $profile->sex, $activityCoef);
    	$NB += $this->Diet->getAimOffset($profile);
    	$this->Diet->setAveKkal($NB);

    	$bgunorm = $this->Diet->PFC_for_day($profile->somatotype, $profile->weight);
        $bgunorm["Kkal"] = $NB;
    	$admin = TableRegistry::get("Users")->find('all', ['conditions' => ['role'=> 'admin']]);
        $ids = [$id, $admin->first()->id];
        $foodcategories = TableRegistry::get("Foodcategory")->find('all', ["contain" => ["foods" => ["conditions" => ["deleted" => 0, "owner IN" => $ids]]], "conditions" => ["deleted" => 0, "owner IN" => $ids]]);
        $this->set("foodcategories", $foodcategories);
    	//$id = $this->Auth->user('id');
    	//$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Eating'], 'conditions' => [ 'userId' => $id ] ]);
    	$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], 'conditions' => [ 'userId' => $uid ]] );
        if ($_POST) {

            $uid = $this->Auth->user('id');
            $programs = TableRegistry::get("Eatingprogram");
            $eatingmenus = TableRegistry::get("Routineeatingmenu");
            

            $eatingmenus->deleteAll(['eatingprogram_id' => $program->id]);
            /*if ($eatingprogram->routine_id != $_POST['routine']) {
                $programs.delete($eatingprogram);
            }*/

            $program->name = trim($_POST['name']);
            
            $program->routine_id = $_POST['routine'];
            $program->lastmodified = (new \DateTime())->format("Y-m-d H:i:s"); 
            $validator = $programs->validationDefault(new Validator());
            $errors = $validator->errors($this->request->data());
            
            $validate = $this->validateInput();
            $eatings = TableRegistry::get("routineeatingmenu");

            if (empty($errors) && $validate && $programs->save($program)) {
                    foreach($_POST['foods'] AS $daynum => $v){
                        foreach($v AS $eatingid => $foods) {
                            //echo $eatingid;
                            //print_r ($foods);
                            foreach ($foods as $key => $value) {
                                $eating = $eatings->newEntity();
                                $eating->eatingprogram_id = $program->id;
                                $eating->eating_id = $eatingid;
                                //echo($eating->eating_id);
                                $eating->food_id = $value[0];
                                $eating->day_number = $daynum;
                                $eating->cnt = $value[1];
                                $eatings->save($eating);
                            }
                        }
                    }
            }
    	   return $this->redirect(['action' => 'index']);
        }
    	$this->set("routines", $routines);
    	$this->set("bgunorm", /*"{'NB': $NB, 'CMA': $CMA}"*/$bgunorm);
		$this->set("program", $obj);
		$this->render('create');
    }

    public function edit2($id) {
    	$uid = $this->Auth->user('id');
    	$eatingprogram = TableRegistry::get("Eatingprogram")->get($id, ['contain' => ['Routine', "routineeatingmenu" => ["Food", "Eating"]], 'conditions' => [ 'userId' => $uid] ]);
		$eatingprograminfo = (object)[];
		$eatingprograminfo->id = $id;
		$eatingprograminfo->name = $eatingprogram->name;
		$eatingprograminfo->routine_id = $eatingprogram->routine_id;
		$days=[];
		foreach($eatingprogram->routineeatingmenu as $menu) {
          //echo ($days[$menu->day_number]); 
          //$days[$menu->day_number][$menu->eating_id] = 2;
          if ((array_key_exists($menu->day_number, $days)) && (array_key_exists($menu->eating_id, $days[$menu->day_number]))) {
          	$ii = count($days[$menu->day_number][$menu->eating_id]);
            $days[$menu->day_number][$menu->eating_id][$ii] = $menu->food;
            $days[$menu->day_number][$menu->eating_id][$ii]->cnt = $menu->cnt;
        }
          else { 
            		$days[$menu->day_number][$menu->eating_id][0] = $menu->food;
            		$days[$menu->day_number][$menu->eating_id][0]->cnt = $menu->cnt;
        	}
        }
        $eatingprograminfo->daycnt = count($days);
        $eatingprograminfo->eatingcnt = count($days[0]);
    	$this->set("eatings", $days);

$profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $uid, 'active' => true ] ]);
    	$profile = $profiles->first();
    	$this->loadComponent('Diet');

    	$activityCoef = $this->Diet->getActivityCoef($profile);
    	$age = $this->Diet->getAge($profile);
    	$NB = $this->Diet->Harris_Benedict($profile->weight, $profile->growth, $age, $profile->sex, $activityCoef);
    	$NB += $this->Diet->getAimOffset($profile);
    	$this->Diet->setAveKkal($NB);

    	$bgunorm = $this->Diet->PFC_for_day($profile->somatotype, $profile->weight);
        $bgunorm["Kkal"] = $NB;

    	$foods = TableRegistry::get("Food")->find('all', [ ]);
    	$this->set("foods", $foods);
    	$routines = TableRegistry::get("Routine")->find('all', ['contain' => ['Eating'], 'conditions' => [ 'userId' => $uid ] ]);
    	foreach($routines as $key => $rout) {
    		if ($rout->id == $eatingprograminfo->routine_id)
    			$eatingprograminfo->routine_number = $key;
    	}
    	$this->set("routines", $routines);
    	$this->set("eatingprograminfo", $eatingprograminfo);
    	$this->set("backurl", "/nutritionprogram/edit/"+$id);
    	$this->set("bgunorm", /*"{'NB': $NB, 'CMA': $CMA}"*/$bgunorm);
    	




/*    	foreach($eatings->routineeatingmenu as $menu) {
          //echo ($days[$menu->day_number]); 
          //$days[$menu->day_number][$menu->eating_id] = 2;
          if ((array_key_exists($menu->day_number, $days)) && (array_key_exists($menu->eating_id, $days[$menu->day_number])))
            $days[$menu->day_number][$menu->eating_id][count($days[$menu->day_number][$menu->eating_id])] = $menu;
          else 
            $days[$menu->day_number][$menu->eating_id][0] = $menu;
        }



    	$routineeatings = TableRegistry::get("Routine")
			->get($id, ['contain' => ['Eating'=>[
            'Foods']]]);
		$this->set("eatings", $routineeatings);
		$food = TableRegistry::get("Food")->find('all', []);
		$this->set("foods", $food);
*/
		if ($_POST) {

			$uid = $this->Auth->user('id');
			$programs = TableRegistry::get("Eatingprogram");
			$eatingmenus = TableRegistry::get("Routineeatingmenu");
			

			$eatingmenus->deleteAll(['eatingprogram_id' => $eatingprogram->id]);
			if ($eatingprogram->routine_id != $_POST['routine']) {
				$programs.delete($eatingprogram);
			}

			$eatingprogram->name = trim($_POST['name']);
			
			$eatingprogram->routine_id = $_POST['routine'];
			$validator = $programs->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			
			$validate = $this->validateInput();

			if (empty($errors) && $validate && $programs->save($eatingprogram)) {
					foreach($_POST['foods'] AS $daynum => $v){
						foreach($v AS $eatingid => $foods) {
							foreach ($foods as $key => $value) {
								$eating = $eatingmenus->newEntity();
								$eating->eatingprogram_id = $eatingprogram->id;
								$eating->eating_id = $eatingid;
								$eating->food_id = $value[0];
								$eating->day_number = $daynum;
								$eating->cnt = $value[1];
								$eatingmenus->save($eating);
							}
						}
					}
			}
			









			/*$connection = \Cake\Datasource\ConnectionManager::get("default");
			$connection->logQueries(true);
			$routineeatings = TableRegistry::get("Routineeatingmenu");
			foreach($_POST["food"] as $day => $dayexs) {
				$routineeatings->deleteAll([ 'eating_id' => $day]);	
				//var_dump($dayexs);
				foreach($dayexs as $exid => $exdata) {
					//$routineeatings = TableRegistry::get("Routineeatingmenu");
					$routineeating = $routineeatings->newEntity();
					$routineeating->cnt = $exdata["cnt"];
					$routineeating->eating_id = $day;
					$routineeating->food_id = $exid;
					$routineeatings->save($routineeating);
				}

			}	*/

			//$trainingprogram->name = $_POST['name'];
			//$trainingprogram->userId = $id;
			
			//$trainingdays = TableRegistry::get("Trainingprogramday");
			//$trainingprogramdaysexercises = TableRegistry::get("Trainingprogramday_Exercise");

			//$validator = $programs->validationDefault(new Validator());
			//$errors = $validator->errors($this->request->data());
			//$validate = $this->validateInput();

			/*if ($validate) {
			if ($programs->save($trainingprogram)) {
	
				$trainingday = $trainingdays->find('all', ['conditions'=>['trainingprogram_id' => $trainingprogram->id]]);
				foreach ($trainingday as $id => $day) {
					$trainingprogramdaysexercises->deleteAll([ 'trainingprogramday_id' => $day->id ]);		
				}
			}*/
			return $this->redirect(['action' => 'index']);
		}
    }
}