<?php

namespace App\Controller;

use User;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

use Cake\ORM\TableRegistry;
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Validation\Validator;
use Cake\I18n\Date;


class DiaryController extends AppController
{

	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if ($this->Auth->user("role") == "corp" || $this->Auth->user("role") == "trainer")
            $this->viewBuilder()->layout('corpuser');
        else    //if ($this->Auth->user("role") == "admin") 
            if ($this->request->admin)
                if ($this->Auth->user("role") == "admin") 
                    $this->viewBuilder()->layout('admin');
                else 
                    throw new UnauthorizedException();
            else    

            $this->viewBuilder()->layout('redesignmain');
        $this->set("section", "diary");
    }

	public function isAuthorized($user) {
               
		return true;  
    }

	public function index() {
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
        $bgunorm["Kkal"] = round($NB, 2);
        $bgunorm["aveCaCf"] = round($bgunorm["aveCaCf"], 2);
        $bgunorm["aveFtCf"] = round($bgunorm["aveFtCf"], 2);
        $bgunorm["avePrCf"] = round($bgunorm["avePrCf"], 2);
        $trainingprogramdays = [];
        $diarydays = TableRegistry::get("Diary")->find('all', [ 'conditions' => [ 'users_id' => $id ] ]);
        foreach ($diarydays as $key => $day) {
            $trainigday = TableRegistry::get("Trainingprogramday")->get($day->trainingprogramday_id);
            $day->trainingprogramday = $trainigday;
            if (array_key_exists($trainigday->trainingprogram_id, $trainingprogramdays)) {
                $day->alternativedays = $trainingprogramdays[$trainigday->trainingprogram_id];
            } else {
                $trainingprogramdays[$trainigday->trainingprogram_id] = [];
                $trainigdays = TableRegistry::get("Trainingprogramday")->find('all', ["conditions" => ["trainingprogram_id" => $trainigday->trainingprogram_id]]);
                foreach($trainigdays as $key => $day2) {
                    $planexercises = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["trainingprogramday_id" => $day2->id]]);
                    $exes1 = [];
                    //var_dump($planexercises->toArray());
                    foreach($planexercises as $key => $planex) {
                        $ex = new \stdClass();
                        $exercise = TableRegistry::get("Exercise")->get($planex->exercise_id, ['contain' => ['Musculgroups']]); 
                        $ex->exercise = $exercise;  
                        $ex->plan = $planex;
                        $sets1 = [];
                        for ($i=0; $i<$planex->podhod; $i++) {
                            $set = new \stdClass();
                            $set->planrepeats = $planex->repeats;
                            $set->planweight = $planex->minweight . " - " . $planex->maxweight;
                            $sets1[count($sets1)] = $set;   
                        }
                        $ex->sets = $sets1;
                        $exes1[count($exes1)] = $ex;
                    }
                    //var_dump($exes1);
                            //$day->dayexersices = $exes1;   
                    $trainingprogramdays[$trainigday->trainingprogram_id][$day2->id] = $exes1;
                }
                $day->alternativedays = $trainingprogramdays[$trainigday->trainingprogram_id];
            }
            $doneex = TableRegistry::get("Doneexercise")->find('all', ["conditions" => ["diary_id" => $day->id]]);
            $day->dayexersices = $doneex->all();
            if ($doneex->count() == 0) {
                $planexercises = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["trainingprogramday_id" => $day->trainingprogramday_id]]);
                $exes1 = [];
                foreach($planexercises as $key => $planex) {
                    $ex = new \stdClass();
                    $exercise = TableRegistry::get("Exercise")->get($planex->exercise_id, ['contain' => ['Musculgroups']]); 
                    $ex->exercise = $exercise;  
                    $ex->plan = $planex;
                    $sets1 = [];
                    for ($i=0; $i<$planex->podhod; $i++) {
                        $set = new \stdClass();
                        $set->planrepeats = $planex->repeats;
                        $set->planweight = $planex->minweight . " - " . $planex->maxweight;
                        $sets1[count($sets1)] = $set;   
                    }
                    $ex->sets = $sets1;
                    $exes1[count($exes1)] = $ex;
                }
                $day->dayexersices = $exes1;
            } else {
                foreach ($doneex as $key => $ex) {  
                    $exercise = TableRegistry::get("Exercise")->get($ex->exercise_id, ['contain' => ['Musculgroups']]); 
                    $ex->exercise = $exercise;  
                    $plan = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["id" => $ex->trainingdayexercise_id]]);
                    //debug($plan);
                    //var_dump($plan->first());
                    $plan1 = $plan->first();
                    $ex->plan = $plan1;
                    $sets = TableRegistry::get("Doneexerciseset")->find('all', ["conditions" => ["doneexercise_id" => $ex->id]]);
                    $sets1 = $sets->toArray();
                    for ($i=0; $i<count($sets1); $i++) {
                        if ($plan1 != null && $i<$plan1->podhod) {
                            $sets1[$i]->planrepeats = $plan1->repeats;
                            $sets1[$i]->planweight = $plan1->minweight . " - " . $plan1->maxweight;
                            //$sets1[$i]->planrepeats = $plan1->repeats;
                        }
                    }
                    if ($plan1 != null && count($sets1) < $plan1->podhod)
                        for ($i=count($sets1); $i<$plan1->podhod; $i++) {
                            $set = new \stdClass();
                            $set->planrepeats = $plan1->repeats;
                            $set->planweight = $plan1->minweight . " - " . $plan1->maxweight;
                            $sets1[count($sets1)] = $set;   
                        }
                    $ex->sets = $sets1;
                            //$day->dayexersices->sets = $sets->all();
                }
            }
                    //$day->dayexersices = $doneex->all();
        }        

        $this->set("diarydays", $diarydays);

        $exercises = TableRegistry::get("Doneexercise")->find('all');        

        $this->set("exes", $exercises->all());        

        $this->loadModel("Doneexerciseset");
        $this->loadModel("Doneexercise");
        $requestArticlesDetaile = $this->Doneexerciseset->find('all', [
            'join'=>['Doneexercise' => [
                'table' => 'doneexercise',
                'type' => 'LEFT',
                'conditions' => 'Doneexerciseset.doneexercise_id = Doneexercise.id'
                ]
            ]
        ]);
        $this->set("exesede", $requestArticlesDetaile->all()); 
        $musculgroups = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises"]]);
        $this->set("musculgroups", $musculgroups);



        /*Нужно учесть какоой день - Тренировочный или нет. Для этого количество дней разбить на две группы - кол-во тренировочных, кол-во нетренировочных*/
        $freeeatings = []; $trainingeatings = [];
        $routine = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], "conditions" => 
                ["active" => true, "userId" => $id]])->first();
            if ($routine != null) {
                    $eatingprogram = TableRegistry::get("Eatingprogram")->find('all', ['conditions' => [ 'users_id' => $id, 'routine_id' => $routine->id, 'active' => true] ]);
                    if ($eatingprogram->count() > 0) {
                        $eatingprogram=$eatingprogram->first();
                    $key2 = [];
                    $key2[$routine->routineday[0]->id] = 0;
                    $key2[$routine->routineday[1]->id] = 1;
                    $key3 = [];
                    for ($ii=0; $ii < 2; $ii++)
                        foreach ($routine->routineday[$ii]->eating as $key => $value)
                            $key3[$value->id] = $routine->routineday[$ii]->id;        
                    $freedays = [];
                    $trainingdays = [];

                    $menu = TableRegistry::get("Routineeatingmenu")->find('all', ['conditions' => [ 'eatingprogram_id' => $eatingprogram->id]]);
                    foreach($menu as $value){
                        $daytype = $key2[$key3[$value->eating_id]];
                        if ($daytype == 0 && !in_array($value->day_number, $trainingdays))
                            $trainingdays[] = $value->day_number;
                        else if ($daytype == 1 && !in_array($value->day_number, $freedays)) {
                            $freedays[] = $value->day_number;
                        }
                    }
                    /*$diarydays = TableRegistry::get("Diary")->find('all', [ 'conditions' => [ 'users_id' => $uid, 'date' => $date ] ]);
                    //print_r($trainingdays);
                    //print_r($freedays);
                    $curdatenum = 0;
                    if ($diarydays->count()>0) {
                        $daycnt->day_number = count($trainingdays)>0?count($trainingdays):count($freedays);
                    }
                    else {
                        $daycnt->day_number = count($freedays)>0?count($freedays):count($trainingdays);
                    }*/
                    
                    foreach($trainingdays as $day) {
                        $eatingprogram = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ['conditions' => ['day_number' => $day], "Food", "Eating"]], 'conditions' => [ 'users_id' => $id, 'Eatingprogram.active' => true, 'routine_id' => $routine->id] ]);
                        $trainingeatings[] = $this->formateatingprogram($eatingprogram->toArray()[0], $bgunorm);
                    }
                    
                    foreach($freedays as $day) {
                        $eatingprogram = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ['conditions' => ['day_number' => $day], "Food", "Eating"]], 'conditions' => [ 'users_id' => $id, 'Eatingprogram.active' => true, 'routine_id' => $routine->id] ]);
                        $freeeatings[] = $this->formateatingprogram($eatingprogram->toArray()[0], $bgunorm);
                    }
                }
            }
        $this->set("freeeatings", $freeeatings);
        $this->set("trainingeatings", $trainingeatings);


        $foodcategories = TableRegistry::get("Foodcategory")->find('all', ["contain" => ["foods"]]);
        $this->set("foodcategories", $foodcategories);
    }

    public function addDay() {
        $date = $this->request->data["date"];
        //echo $date;
        $uid = $this->Auth->user('id');
        $this->autoRender = false;
        $trainingprograms = TableRegistry::get("Trainingprogram")->find('all', ['contain' => ['trainingprogramday'], 'conditions' => [ 'users_id' => $uid, 'active' => true ] ]);
        if ($trainingprograms->count() > 0) {
            $trainingprogram = $trainingprograms->first();
            $day = $this->Diary->newEntity();
            $diaryday = new \stdClass();
            $lasttraining = TableRegistry::get("Diary")->find('all', ['conditions' => ['date <' => $date, 'users_id' => $uid], 'order' => ['date' => 'DESC']])->first();
            if ($lasttraining) {
                $trainingdaysmapbyid = [];
                for ($i = 0; $i < count($trainingprogram->trainingprogramday); $i++) {
                    $trainingdaysmapbyid[$trainingprogram->trainingprogramday[$i]->id] = $i;
                }
                if (array_key_exists($lasttraining->trainingprogramday_id, $trainingdaysmapbyid)) {
                    $i = $trainingdaysmapbyid[$lasttraining->trainingprogramday_id];
                    $day->trainingprogramday_id = ($i == (count($trainingprogram->trainingprogramday)-1))?$trainingprogram->trainingprogramday[0]->id:$trainingprogram->trainingprogramday[$i+1]->id;
                    $diaryday->trainingprogramday = ($i == (count($trainingprogram->trainingprogramday)-1))?$trainingprogram->trainingprogramday[0]:$trainingprogram->trainingprogramday[$i+1];
                } else {
                    //echo "Предыдущая тренировка выполнялась по другой программе.";
                    //echo $lasttraining->trainingprogramday_id;
                    //print_r($trainingdaysmapbyid);
                    $day->trainingprogramday_id = $trainingprogram->trainingprogramday[0]->id;              
                    $diaryday->trainingprogramday = $trainingprogram->trainingprogramday[0];    
                }
            } else {
                $day->trainingprogramday_id = $trainingprogram->trainingprogramday[0]->id;              
                $diaryday->trainingprogramday = $trainingprogram->trainingprogramday[0];
            }
            $day->users_id = $uid;
            $day->date = $date;
            $this->Diary->save($day);
            $diarydays = [];
            $diaryday->date = $day->date;
            $diaryday->id = $day->id;
            $diaryday->mark = 0;
            $diaryday->trainingprogramday_id = $day->trainingprogramday_id;
            


            $planexercises = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["trainingprogramday_id" => $day->trainingprogramday_id]]);
            $exes1 = [];
            foreach($planexercises as $key => $planex) {
                $ex = new \stdClass();
                $exercise = TableRegistry::get("Exercise")->get($planex->exercise_id, ['contain' => ['Musculgroups']]); 
                $ex->exercise = $exercise;  
                $ex->plan = $planex;
                $sets1 = [];
                for ($i=0; $i<$planex->podhod; $i++) {
                    $set = new \stdClass();
                    $set->planrepeats = $planex->repeats;
                    $set->planweight = $planex->minweight . " - " . $planex->maxweight;
                    $sets1[count($sets1)] = $set;   
                }
                $ex->sets = $sets1;
                $exes1[count($exes1)] = $ex;
            }
            $diaryday->dayexersices = $exes1;
            $diaryday->users_id = $day->users_id;
            /**Получаем список альтернативных дней*/
            //$trainingprogramdays[$trainigday->trainingprogram_id] = [];
            $trainingprogramdays = [];
                        $trainigdays = TableRegistry::get("Trainingprogramday")->find('all', ["conditions" => ["trainingprogram_id" => $diaryday->trainingprogramday->trainingprogram_id]]);
                        foreach($trainigdays as $key => $day) {
                            $planexercises = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["trainingprogramday_id" => $day->id]]);
                        $exes1 = [];
                        foreach($planexercises as $key => $planex) {
                            $ex = new \stdClass();
                            $exercise = TableRegistry::get("Exercise")->get($planex->exercise_id, ['contain' => ['Musculgroups']]); 
                            $ex->exercise = $exercise;  
                            $ex->plan = $planex;
                            $sets1 = [];
                            for ($i=0; $i<$planex->podhod; $i++) {
                                $set = new \stdClass();
                                $set->planrepeats = $planex->repeats;
                                $set->planweight = $planex->minweight . " - " . $planex->maxweight;
                                $sets1[count($sets1)] = $set;   
                            }
                            $ex->sets = $sets1;
                            $exes1[count($exes1)] = $ex;
                        }
                            //$day->dayexersices = $exes1;   
                            $trainingprogramdays[$day->id] = $exes1;
                        }
                         $diaryday->alternativedays = $trainingprogramdays;



            $diarydays[$diaryday->id] = $diaryday;
            $addedId = $diaryday->id;
            //Пытаемся обновить запланированные тренировки для последующих дней.
             //Проверяем есть ли тренировки уже запланированные после текущей.
            $curdate = new \DateTime();
            $addedDate = new \DateTime($date);
            $isreplace = true;
            if ($curdate > $addedDate) {
                //echo "bolshe";
                //Проверяем есть ли тренировки после добавленной и до текущей.
                //$futuretrainings = TableRegistry::get("Diary")->find('all', ['conditions' => ['date >' => $date, 'date <=' => $curdate->format("Y-m-d"), "users_id" => $uid], 'order' => ['date' => 'DESC']]);
                /*if ($futuretrainings->count() > 0)
                    $isreplace = false;*/
                $trainingsbetween =  TableRegistry::get("Diary")->find('all', ['contain'=>["Doneexercise"], 'conditions' => ['date >' => $date, 'date <=' => $curdate->format("Y-m-d"), "users_id" => $uid], 'order' => ['date' => 'DESC']]);
                foreach($trainingsbetween as $training) {
                    if (count($training->Doneexercise)>0){
                        //echo "tut";
                        $isreplace = false;    
                    }
                }
            }

            if ($curdate <= $addedDate || $isreplace) {
                $futuretrainings = TableRegistry::get("Diary")->find('all', ['conditions' => ['date >' => $date, "users_id" => $uid], 'order' => ['date' => 'DESC']]);
                foreach($futuretrainings as $key => $futuretraining) {
                    $i = $trainingdaysmapbyid[$futuretraining->trainingprogramday_id];
                    $futuretraining->trainingprogramday_id = ($i == (count($trainingprogram->trainingprogramday)-1))?$trainingprogram->trainingprogramday[0]->id:$trainingprogram->trainingprogramday[$i+1]->id;
                    //($i == (count($trainingprogram->trainingprogramday)-1))?$i = 0:$i++;
                    $this->Diary->save($futuretraining);

                    $diaryday = new \stdClass();
                    $diaryday->date = $futuretraining->date;
                    $diaryday->id = $futuretraining->id;
                    $diaryday->mark = 0;
                    $diaryday->trainingprogramday_id = $futuretraining->trainingprogramday_id;
                    $diaryday->trainingprogramday = ($i == (count($trainingprogram->trainingprogramday)-1))?$trainingprogram->trainingprogramday[0]:$trainingprogram->trainingprogramday[$i+1];
                    $planexercises = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["trainingprogramday_id" => $futuretraining->trainingprogramday_id]]);
                    $exes1 = [];
                    foreach($planexercises as $key => $planex) {
                        $ex = new \stdClass();
                        $exercise = TableRegistry::get("Exercise")->get($planex->exercise_id, ['contain' => ['Musculgroups']]); 
                        $ex->exercise = $exercise;  
                        $ex->plan = $planex;
                        $sets1 = [];
                        for ($i=0; $i<$planex->podhod; $i++) {
                            $set = new \stdClass();
                            $set->planrepeats = $planex->repeats;
                            $set->planweight = $planex->minweight . " - " . $planex->maxweight;
                            $sets1[count($sets1)] = $set;   
                        }
                        $ex->sets = $sets1;
                        $exes1[count($exes1)] = $ex;
                    }
                    $diaryday->dayexersices = $exes1;
                    $diaryday->users_id = $futuretraining->users_id;
                    /**Получаем список альтернативных дней*/
            //$trainingprogramdays[$trainigday->trainingprogram_id] = [];
                    
                         $diaryday->alternativedays = $this->getAlternativedays($diaryday->trainingprogramday->trainingprogram_id);
                    $diarydays[$futuretraining->id] = $diaryday;



                }
            }
            echo "{\"status\" : \"success\", \"date\" : \"".$date."\", \"id\" : ".$addedId.", \"diaryday\" : ".json_encode($diarydays)."}";
        }
        else {
            echo "{\"status\" : \"error\"}";   
        }
        
    }

    private function getAlternativedays($trainingprogram_id) {
                /**Получаем список альтернативных дней*/
            //$trainingprogramdays[$trainigday->trainingprogram_id] = [];
            $trainingprogramdays = [];
                        $trainigdays = TableRegistry::get("Trainingprogramday")->find('all', ["conditions" => ["trainingprogram_id" => $trainingprogram_id]]);
                        foreach($trainigdays as $key => $day) {
                            $planexercises = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["trainingprogramday_id" => $day->id]]);
                        $exes1 = [];
                        foreach($planexercises as $key => $planex) {
                            $ex = new \stdClass();
                            $exercise = TableRegistry::get("Exercise")->get($planex->exercise_id, ['contain' => ['Musculgroups']]); 
                            $ex->exercise = $exercise;  
                            $ex->plan = $planex;
                            $sets1 = [];
                            for ($i=0; $i<$planex->podhod; $i++) {
                                $set = new \stdClass();
                                $set->planrepeats = $planex->repeats;
                                $set->planweight = $planex->minweight . " - " . $planex->maxweight;
                                $sets1[count($sets1)] = $set;   
                            }
                            $ex->sets = $sets1;
                            $exes1[count($exes1)] = $ex;
                        }
                            //$day->dayexersices = $exes1;   
                            $trainingprogramdays[$day->id] = $exes1;
                        }
                        return $trainingprogramdays;
    }

    private function updateplantrainings($uid, $date, $startdate) {
        $diarydays = [];
        $trainingprograms = TableRegistry::get("Trainingprogram")->find('all', ['contain' => ['trainingprogramday'],  'conditions' => [ 'users_id' => $uid, 'active' => true ] ]);
        //echo($uid);
        //echo($trainingprograms->count());
        if ($trainingprograms->count() > 0) {
            $trainingprogram = $trainingprograms->first();
            $trainingdaysmapbyid = [];
            for ($i = 0; $i < count($trainingprogram->trainingprogramday); $i++) {
                $trainingdaysmapbyid[$trainingprogram->trainingprogramday[$i]->id] = $i;
            }
            $futuretrainings = TableRegistry::get("Diary")->find('all', ['conditions' => ['date >' => $date, "users_id" => $uid], 'order' => ['date' => 'DESC']]);
            $i = array_key_exists($startdate, $trainingdaysmapbyid)?$trainingdaysmapbyid[$startdate]:count($trainingprogram->trainingprogramday)-1;
            //echo($i);
            foreach($futuretrainings as $key => $futuretraining) {
                //echo("<br>update futuretraining");
                //$i = $trainingdaysmapbyid[$futuretraining->trainingprogramday_id];
                //$i = 

                $i = ($i == count($trainingprogram->trainingprogramday)-1)?0:$i+1;
                //echo($i);
                $futuretraining->trainingprogramday_id = $trainingprogram->trainingprogramday[$i]->id;


                //$futuretraining->trainingprogramday_id = ($i == 0)?$trainingprogram->trainingprogramday[count($trainingprogram->trainingprogramday)-1]->id:$trainingprogram->trainingprogramday[$i-1]->id;
                  
                    //($i == (count($trainingprogram->trainingprogramday)-1))?$i = 0:$i++;
                $this->Diary->save($futuretraining);

                $diaryday = new \stdClass();
                $diaryday->date = $futuretraining->date->format("Y-m-d");//$futuretraining->date;
                $diaryday->id = $futuretraining->id;
                $diaryday->mark = 0;
                $diaryday->trainingprogramday_id = $futuretraining->trainingprogramday_id;
                //$diaryday->trainingprogramday = ($i == 0)?$trainingprogram->trainingprogramday[count($trainingprogram->trainingprogramday)-1]:$trainingprogram->trainingprogramday[$i-1];
                $diaryday->trainingprogramday = $trainingprogram->trainingprogramday[$i];
                $planexercises = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["trainingprogramday_id" => $futuretraining->trainingprogramday_id]]);
                $exes1 = [];
                foreach($planexercises as $key => $planex) {
                    $ex = new \stdClass();
                    $exercise = TableRegistry::get("Exercise")->get($planex->exercise_id, ['contain' => ['Musculgroups']]); 
                    $ex->exercise = $exercise;  
                    $ex->plan = $planex;
                    $sets1 = [];
                    for ($j=0; $j<$planex->podhod; $j++) {
                        $set = new \stdClass();
                        $set->planrepeats = $planex->repeats;
                        $set->planweight = $planex->minweight . " - " . $planex->maxweight;
                        $sets1[count($sets1)] = $set;   
                    }
                    $ex->sets = $sets1;
                    $exes1[count($exes1)] = $ex;
                }
                $diaryday->dayexersices = $exes1;
                $diaryday->users_id = $futuretraining->users_id;
                $diarydays[$futuretraining->id] = $diaryday;
            }
        }
        return $diarydays;
    }

    private function updateplantrainingsback($uid, $date, $startdate) {
        $diarydays = [];
        $trainingprograms = TableRegistry::get("Trainingprogram")->find('all', ['contain' => ['trainingprogramday'],  'conditions' => [ 'users_id' => $uid, 'active' => true ] ]);
        //echo($uid);
        //echo($trainingprograms->count());
        if ($trainingprograms->count() > 0) {
            $trainingprogram = $trainingprograms->first();
            $trainingdaysmapbyid = [];
            for ($i = 0; $i < count($trainingprogram->trainingprogramday); $i++) {
                $trainingdaysmapbyid[$trainingprogram->trainingprogramday[$i]->id] = $i;
            }
            $futuretrainings = TableRegistry::get("Diary")->find('all', ['conditions' => ['date >' => $date, "users_id" => $uid], 'order' => ['date' => 'DESC']]);
            $i = array_key_exists($startdate, $trainingdaysmapbyid)?$trainingdaysmapbyid[$startdate]:count($trainingprogram->trainingprogramday)-1;
            //echo("$i = ".$i);
            foreach($futuretrainings as $key => $futuretraining) {
                //echo("<br>update futuretraining");
                //$i = $trainingdaysmapbyid[$futuretraining->trainingprogramday_id];
                //$i = 

                $i = ($i == 0)?count($trainingprogram->trainingprogramday)-1:$i-1;
                //echo($i);
                $futuretraining->trainingprogramday_id = $trainingprogram->trainingprogramday[$i]->id;


                //$futuretraining->trainingprogramday_id = ($i == 0)?$trainingprogram->trainingprogramday[count($trainingprogram->trainingprogramday)-1]->id:$trainingprogram->trainingprogramday[$i-1]->id;
                  
                    //($i == (count($trainingprogram->trainingprogramday)-1))?$i = 0:$i++;
                $this->Diary->save($futuretraining);

                $diaryday = new \stdClass();
                $diaryday->date = $futuretraining->date->format("Y-m-d");//$futuretraining->date;
                $diaryday->id = $futuretraining->id;
                $diaryday->mark = 0;
                $diaryday->trainingprogramday_id = $futuretraining->trainingprogramday_id;
                //$diaryday->trainingprogramday = ($i == 0)?$trainingprogram->trainingprogramday[count($trainingprogram->trainingprogramday)-1]:$trainingprogram->trainingprogramday[$i-1];
                $diaryday->trainingprogramday = $trainingprogram->trainingprogramday[$i];
                $planexercises = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["trainingprogramday_id" => $futuretraining->trainingprogramday_id]]);
                $exes1 = [];
                foreach($planexercises as $key => $planex) {
                    $ex = new \stdClass();
                    $exercise = TableRegistry::get("Exercise")->get($planex->exercise_id, ['contain' => ['Musculgroups']]); 
                    $ex->exercise = $exercise;  
                    $ex->plan = $planex;
                    $sets1 = [];
                    for ($j=0; $j<$planex->podhod; $j++) {
                        $set = new \stdClass();
                        $set->planrepeats = $planex->repeats;
                        $set->planweight = $planex->minweight . " - " . $planex->maxweight;
                        $sets1[count($sets1)] = $set;   
                    }
                    $ex->sets = $sets1;
                    $exes1[count($exes1)] = $ex;
                }
                $diaryday->dayexersices = $exes1;
                $diaryday->users_id = $futuretraining->users_id;
                $diarydays[$futuretraining->id] = $diaryday;
            }
        }
        return $diarydays;
    }

    public function deleteday($id) {
        $this->autoRender = false;
        $uid = $this->Auth->user('id');
        if ($this->request->is('post')) {
            $diary = TableRegistry::get("Diary");
            $exercises = TableRegistry::get("Doneexercise");
            $sets = TableRegistry::get("Doneexerciseset");
            $deldiary = $diary->get($id);
            $date = $deldiary->date;

            $exercise = $exercises->find('all', ['conditions'=>['diary_id' => $id]]);
                    foreach ($exercise as $id => $ex) {
                        $sets->deleteAll([ 'doneexercise_id' => $ex->id ]);      
                    }
            $exercises->deleteAll([ 'diary_id' => $id ]);
            $diary->delete($deldiary);
            //Обновляем запланированные тренировки на следующиее дни
            /*$diarydays = [];
            $trainingprograms = TableRegistry::get("Trainingprogram")->find('all', ['contain' => ['trainingprogramday']], [ 'conditions' => [ 'users_id' => $uid, 'active' => true ] ]);
            if ($trainingprograms->count() > 0) {
                $trainingprogram = $trainingprograms->first();
                $trainingdaysmapbyid = [];
                for ($i = 0; $i < count($trainingprogram->trainingprogramday); $i++) {
                    $trainingdaysmapbyid[$trainingprogram->trainingprogramday[$i]->id] = $i;
                }
                $futuretrainings = TableRegistry::get("Diary")->find('all', ['conditions' => ['date >' => $date, "users_id" => $uid], 'order' => ['date' => 'DESC']]);
                foreach($futuretrainings as $key => $futuretraining) {
                    $i = $trainingdaysmapbyid[$futuretraining->trainingprogramday_id];
                    $futuretraining->trainingprogramday_id = ($i == 0)?$trainingprogram->trainingprogramday[count($trainingprogram->trainingprogramday)-1]->id:$trainingprogram->trainingprogramday[$i-1]->id;
                    //($i == (count($trainingprogram->trainingprogramday)-1))?$i = 0:$i++;
                    $this->Diary->save($futuretraining);

                    $diaryday = new \stdClass();
                    $diaryday->date = $futuretraining->date;
                    $diaryday->id = $futuretraining->id;
                    $diaryday->mark = 0;
                    $diaryday->trainingprogramday_id = $futuretraining->trainingprogramday_id;
                    $diaryday->trainingprogramday = ($i == 0)?$trainingprogram->trainingprogramday[count($trainingprogram->trainingprogramday)-1]:$trainingprogram->trainingprogramday[$i-1];
                    $planexercises = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["trainingprogramday_id" => $futuretraining->trainingprogramday_id]]);
                    $exes1 = [];
                    foreach($planexercises as $key => $planex) {
                        $ex = new \stdClass();
                        $exercise = TableRegistry::get("Exercise")->get($planex->exercise_id, ['contain' => ['Musculgroups']]); 
                        $ex->exercise = $exercise;  
                        $ex->plan = $planex;
                        $sets1 = [];
                        for ($i=0; $i<$planex->podhod; $i++) {
                            $set = new \stdClass();
                            $set->planrepeats = $planex->repeats;
                            $set->planweight = $planex->minweight . " - " . $planex->maxweight;
                            $sets1[count($sets1)] = $set;   
                        }
                        $ex->sets = $sets1;
                        $exes1[count($exes1)] = $ex;
                    }
                    $diaryday->dayexersices = $exes1;
                    $diaryday->users_id = $futuretraining->users_id;
                    $diarydays[$futuretraining->id] = $diaryday;
                }
            }*/
            
            $curdate = new \DateTime(date("Y-m-d 0:0:0"));
            $diarydays = [];
            $isreplace = true;
            $trainingsbetween =  TableRegistry::get("Diary")->find('all', ['contain'=>["Doneexercise"], 'conditions' => ['date >' => $date, 'date <=' => $curdate->format("Y-m-d"), "users_id" => $uid], 'order' => ['date' => 'DESC']]);
                foreach($trainingsbetween as $training) {
                    if (count($training->Doneexercise)>0){
                        //echo "tut";
                        $isreplace = false;    
                    }
                }
                /*if ($trainingsbetween->count() > 0) {
                    echo "tut";
                    $isreplace = false;
                }*/
            $futuretrainings = TableRegistry::get("Diary")->find('all', ['conditions' => ['date >' => $date, "users_id" => $uid], 'order' => ['date' => 'DESC']]);
            //if ($futuretrainings->first()->date >= $curdate) {
                /*$lasttrainings = TableRegistry::get("Diary")->find('all', ['conditions' => ['date <' => $date, "users_id" => $uid], 'order' => ['date' => 'DESC']]);
                if ($lasttrainings->count() > 0)*/
                if ($isreplace) {
                    //echo "replace";
                    $diarydays = $this->updateplantrainingsback($uid, $date, $futuretrainings->first()->trainingprogramday_id);
                }
            //}

            echo "{\"status\" : \"success\", \"diaryday\" : ".json_encode($diarydays)."}";
        } else {
             echo "{\"status\" : \"error\"}"; 
        }
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
        $totalallday->fats = round($totalallday->fats, 2);
        $totalallday->hidrocarbonats = round($totalallday->hidrocarbonats, 2);
        $totalallday->proteins = round($totalallday->proteins, 2);
        $totalallday->colories = round($totalallday->colories, 2);
        $obj->total = $totalallday;
        $obj->foods = $foods;
        return $obj;
        
    }

    public function getEatingdiary($date, $user = null) {
        $this->autoRender = false;
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
        $bgunorm["Kkal"] = round($NB, 2);
        $bgunorm["aveCaCf"] = round($bgunorm["aveCaCf"], 2);
        $bgunorm["aveFtCf"] = round($bgunorm["aveFtCf"], 2);
        $bgunorm["avePrCf"] = round($bgunorm["avePrCf"], 2);


        // Проверяем, есть ли данные о питании в дневнике на этот день.
        $existingEating = TableRegistry::get("Eatingdiary")->find('all', ["contain" => ["Eatingprogram", "Eating", "Food"], "conditions" => ["Eatingdiary.date" => $date]]);
        if ($existingEating->count() > 0) {
            $this->set("data", $existingEating->toArray());
            echo "{\"status\" : \"success\", \"data\" : " . json_encode($this->formatresults($existingEating->toArray(), $bgunorm)) . "}";
        }
        else {
            // Ищем активную программу питания для пользователя.
            $routine = TableRegistry::get("Routine")->find('all', ['contain' => ['Routineday'=>['Eating']], "conditions" => 
                ["active" => true, "userId" => $uid]])->first();
            if ($routine == null) {
                echo "{\"status\" : \"error\", \"message\" : \"Не выбрано ни одного активного распорядка дня. Выберите активный распорядок дня для просмотра программы питания.\"}";    
            } else {
                $eatingprograms = TableRegistry::get("Eatingprogram")->find('all', ['conditions' => [ 'users_id' => $uid, 'routine_id' => $routine->id, 'active' => true] ]);
                if ($eatingprograms->count() > 0) {
                    $eatingprogram = $eatingprograms->first();
                    //echo "data" . $eatingprogram->date_active;
                    $diarydays = TableRegistry::get("Diary")->find('all', [ 'conditions' => [ 'users_id' => $uid, 'date' => $date ] ]);
                    $istraining = $diarydays->count() > 0?true:false;
                    if ($eatingprogram->date_active==null || $eatingprogram->date_active=="")
                        $eatingprogram->date_active=/*date("Y-m-d 0:0:0");*/date("d.m.y, H:i");
                    //echo $eatingprogram->date_active;
                    //$date1 = new \DateTime($eatingprogram->date_active);
                    $date1 = /*new */\DateTime::createFromFormat("d.m.Y, H:i", $eatingprogram->date_active);
                    if ($date1 == false)
                        $date1 = /*new */\DateTime::createFromFormat("d.m.y, H:i", $eatingprogram->date_active);
                    //$date1 = $eatingprogram->date_active;
                    //var_dump($date1);
                    $date2 = new \DateTime($date);
                    $startdatenum = 0;
                    $startdate = 0;
                    $existingEatings = TableRegistry::get("Eatingdiary")->find('all', ["contain" => ["Eatingprogram", "Eating" => ["Routineday"], "Food"], "conditions" => ["Eatingdiary.date < " => $date, "Routineday.type" => $istraining?1:2], 'order' => ['Eatingdiary.date' => 'DESC']]);
                    if ($existingEatings->count() > 0) {
                        //echo $existingEatings->first();
                        $exisitingeating = $existingEatings->first();
                        if ($exisitingeating->eatingprogram_id != $eatingprogram->id) {
                            //если последняя запись была по другой программе питания
                            //есть ли другая программа питания?
                            $concurenteatingprogram = TableRegistry::get("Eatingprogram")->get($exisitingeating->eatingprogram_id);
                            if ($concurenteatingprogram == null) {
                                $date1 = $existingEatings->first()->date;
                            } else {
                                $date2->setTime(0,0);
                                $date1->setTime(0,0);
                                if ($date2 < $date1){
                                    //echo $date2 . "  " . $date1;
                                    //echo date_format($date2, 'Y-m-d H:m:i');
                                    //echo date_format($date1, 'Y-m-d H:m:i');
                                    $eatingprogram = $concurenteatingprogram;
                                    $date1 = $existingEatings->first()->date;
                                    $startdate = $existingEatings->first()->day_number;
                                } 
                            }
                        } else {
                            $date1 = $existingEatings->first()->date;
                            $startdate = $existingEatings->first()->day_number;
                        }
                        //echo $startdate . "<br>";
                    }
                    $diffbetween = 0;
                    $diffbetween = $date2->diff($date1)->format("%a");
                    //echo $eatingprogram->date."      ".$date;
                    /*if ($date2 >= $date1) {
                        echo "bolshe";
                        $diffbetween = $date2->diff($date1)->format("%a");
                    }
                    else {
                        $diffbetween = $date1->diff($date2)->format("%a");
                        echo "menshe";
                    }*/
                        //echo "noteq";
                    //echo $date1->format("Y-m-d") ."   ". $date2->format("Y-m-d");
                    /*if ($date1->format("Y-m-d") != $date2->format("Y-m-d"))
                        $diffbetween+=1;*/
                    //echo $diffbetween;
                    //echo($date1->format("Y-m-d"));
                    //echo($date2->format("Y-m-d"));
                    //echo($diffbetween);
                    $daycnt = TableRegistry::get("Routineeatingmenu")->find('all', ['fields' => array('day_number' => 'MAX(Routineeatingmenu.day_number)'), 'conditions' => [ 'eatingprogram_id' => $eatingprogram->id]])->first();
                    $daycnt->day_number+=1;

                    /*Нужно учесть какоой день - Тренировочный или нет. Для этого количество дней разбить на две группы - кол-во тренировочных, кол-во нетренировочных*/
                    $key2 = [];
                    $key2[$routine->routineday[0]->id] = 0;
                    $key2[$routine->routineday[1]->id] = 1;
                    $key3 = [];
                    for ($ii=0; $ii < 2; $ii++)
                        foreach ($routine->routineday[$ii]->eating as $key => $value)
                            $key3[$value->id] = $routine->routineday[$ii]->id;        
                    $freedays = [];
                    $trainingdays = [];
                    $menu = TableRegistry::get("Routineeatingmenu")->find('all', ['conditions' => [ 'eatingprogram_id' => $eatingprogram->id]]);
                    foreach($menu as $value){
                        $daytype = $key2[$key3[$value->eating_id]];
                        if ($daytype == 0 && !in_array($value->day_number, $trainingdays)) {
                            $trainingdays[] = $value->day_number;
                            if ($startdate != 0 && $startdate == $value->day_number) {
                                $startdatenum = count($trainingdays) - 1;
                                //print_r( $trainingdays);
                                //echo $startdatenum . "/n";
                            }
                        }

                        else if ($daytype == 1 && !in_array($value->day_number, $freedays)) {
                            $freedays[] = $value->day_number;
                            if ($startdate != 0 && $startdate == $value->day_number) {
                                $startdatenum = count($freedays) - 1;
                            }
                        }
                    }
                    
                    //print_r($trainingdays);
                    //print_r($freedays);
                    $trainingscount = TableRegistry::get("Diary")->find('all', [ 'conditions' => [ 'users_id' => $uid, 'date > ' => $date1, 'date <= ' => $date ] ]);
                    //echo $trainingscount->count();
                    $curdatenum = 0;
                    if ($diarydays->count()>0) {
                        $daycnt->day_number = count($trainingdays)>0?count($trainingdays):count($freedays);
                        //$curdate = ($diffbetween) % $daycnt->day_number;
                        //echo $startdatenum . "  " . $trainingscount->count();
                        $curdate = ($startdatenum + $trainingscount->count()) % $daycnt->day_number;
                        $curdatenum = count($trainingdays)>0?$trainingdays[$curdate]:$freedays[$curdate];
                    }
                    else {
                        $daycnt->day_number = count($freedays)>0?count($freedays):count($trainingdays);
                        $curdate = ($diffbetween) % $daycnt->day_number;
                        $curdatenum = count($freedays)>0?$freedays[$curdate]:$trainingdays[$curdate];
                    }




                    //$curdate = ($diffbetween + 2) % $daycnt->day_number;
                    //$curdate = ($diffbetween) % $daycnt->day_number;
                    //echo ($diffbetween + 2) . " / " . $daycnt->day_number . " = " .  $curdate;


                    $eatingprogram = TableRegistry::get("Eatingprogram")->find('all', ['contain' => ['Routine' => ['Routineday'=>['Eating']], "routineeatingmenu" => ['conditions' => ['day_number' => $curdatenum], "Food", "Eating"]], 'conditions' => [ 'users_id' => $uid, 'Eatingprogram.id' => $eatingprogram->id] ]);
                    echo "{\"status\" : \"success\", \"data\" : " . json_encode($this->formateatingprogram($eatingprogram->first(), $bgunorm)) . "}";
                } else {
                    echo "{\"status\" : \"error\", \"message\" : \"Не выбрано ни одной активной программы питания.\"}";    
                }
            }
            //else echo "{\"status\" : \"error\"}";
        }
    }

    public function savetraining() {
        $this->autoRender = false;
        $uid = $this->Auth->user('id');
        //echo($uid);
        //var_dump($_POST);
        //$doneexercises = TableRegistry::get("Doneexercise");
        $diarydays = [];
        $diary = TableRegistry::get("Diary");
        $exercises = TableRegistry::get("Doneexercise");
        $sets = TableRegistry::get("Doneexerciseset");
        $deldiary = $diary->get($_POST["id"]);
        if ($deldiary->trainingprogramday_id != $_POST["baseday"]) {
            //echo("tut");
            $futuretrainings = TableRegistry::get("Diary")->find('all', ['conditions' => ['date >' => $deldiary->date, "users_id" => $uid], 'order' => ['date' => 'DESC']]);
            if ($futuretrainings->first()->date > new \DateTime()) {
                //echo("update");
                $diarydays = $this->updateplantrainings($uid, $deldiary->date, $_POST["baseday"]);
            }
        }
        $deldiary->trainingprogramday_id = $_POST["baseday"];
        $deldiary->mark = $_POST["mark"];
        $diary->save($deldiary);
        $exercise = $exercises->find('all', ['conditions'=>['diary_id' => $_POST["id"]]]);
        foreach ($exercise as $id => $ex) {
            $sets->deleteAll([ 'doneexercise_id' => $ex->id ]);      
        }
        $exercises->deleteAll([ 'diary_id' => $_POST["id"] ]);
        foreach($_POST["excersice"] as $key => $value){
            $exercise = $exercises->newEntity();
            $exercise->diary_id = $_POST["id"];
            $exercise->exercise_id = $key;
            $exercise->trainingdayexercise_id = isset($value["trainingexercise"])?$value["trainingexercise"]:-1;
            $exercises->save($exercise);
            foreach($value["sets"] as $key1 => $value1){
                $set = $sets->newEntity();
                $set->doneexercise_id = $exercise->id;
                $set->approach = $key1;
                $set->weight = $value1["weight"];
                $set->repeat = $value1['repeats'];
                $set->plan_weight = $value1['plan_weight'];
                $set->plan_repeat = $value1['plan_repeat'];
                $sets->save($set);
            }
        }

        $trainigday = TableRegistry::get("Trainingprogramday")->get($deldiary->trainingprogramday_id);
        $deldiary->trainingprogramday = $trainigday;
        $doneex = TableRegistry::get("Doneexercise")->find('all', ["conditions" => ["diary_id" => $deldiary->id]]);
        $deldiary->dayexersices = $doneex->all();
        if ($doneex->count() == 0) {
            $planexercises = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["trainingprogramday_id" => $deldiary   ->trainingprogramday_id]]);
            $exes1 = [];
            foreach($planexercises as $key => $planex) {
                $ex = new \stdClass();
                $exercise = TableRegistry::get("Exercise")->get($planex->exercise_id, ['contain' => ['Musculgroups']]); 
                $ex->exercise = $exercise;  
                $ex->plan = $planex;
                $sets1 = [];
                for ($i=0; $i<$planex->podhod; $i++) {
                    $set = new \stdClass();
                    $set->planrepeats = $planex->repeats;
                    $set->planweight = $planex->minweight . " - " . $planex->maxweight;
                    $sets1[count($sets1)] = $set;   
                }
                $ex->sets = $sets1;
                $exes1[count($exes1)] = $ex;
            }
            $deldiary->dayexersices = $exes1;
        } else {
            foreach ($doneex as $key => $ex) {  
                $exercise = TableRegistry::get("Exercise")->get($ex->exercise_id, ['contain' => ['Musculgroups']]); 
                $ex->exercise = $exercise;  
                $plan = TableRegistry::get("TrainingprogramdayExercise")->find('all', ["conditions" => ["id" => $ex->trainingdayexercise_id]]);
                //debug($plan);
                //var_dump($plan->first());
                $plan1 = $plan->first();
                $ex->plan = $plan1;
                $sets = TableRegistry::get("Doneexerciseset")->find('all', ["conditions" => ["doneexercise_id" => $ex->id]]);
                $sets1 = $sets->toArray();
                for ($i=0; $i<count($sets1); $i++) {
                    if (($plan1 != null) && $i<$plan1->podhod) {
                        $sets1[$i]->planrepeats = $plan1->repeats;
                        $sets1[$i]->planweight = $plan1->minweight . " - " . $plan1->maxweight;
                    }
                }
                if (($plan1 != null) && count($sets1) < $plan1->podhod)
                    for ($i=count($sets1); $i<$plan1->podhod; $i++) {
                        $set = new \stdClass();
                        $set->planrepeats = $plan1->repeats;
                        $set->planweight = $plan1->minweight . " - " . $plan1->maxweight;
                        $sets1[count($sets1)] = $set;   
                    }
                $ex->sets = $sets1;
                //$day->dayexersices->sets = $sets->all();
            }
        }
        $deldiary->date = $deldiary->date->format("Y-m-d");        
        $diarydays[$deldiary->id] = $deldiary;    

        $alternativedays = $this->getAlternativedays($deldiary->trainingprogramday->trainingprogram_id);
        foreach($diarydays as $key => $value) {
            $value->alternativedays = $alternativedays;
        }
        echo "{\"status\" : \"success\", \"id\" : ". $_POST['id'] .", \"day\" : ". json_encode($diarydays) ."}";
    }

    function saveeating() {
        $this->autoRender = false;
        $uid = $this->Auth->user('id');
        //var_dump($_POST);
        //$doneexercises = TableRegistry::get("Doneexercise");
        $diary = TableRegistry::get("Eatingdiary");
        $diary->deleteAll(["date" => $_POST["date"]]);
        foreach($_POST["food"] as $eating_id => $value) {
            foreach($value as $food_id => $value1) {
                $oneeating = $diary->newEntity();
                $oneeating->date = $_POST["date"];
                $oneeating->eating_id = $eating_id;
                $oneeating->food_id = $food_id;
                $oneeating->eatingprogram_id = $_POST["eatingprogram_id"];
                $oneeating->day_number = $_POST["day_number"];
                $oneeating->cnt = $value1["cnt"];
                $oneeating->users_id = $uid;
                $diary->save($oneeating);
            }    
        }
        echo "{\"status\" : \"success\"}";
    }
}