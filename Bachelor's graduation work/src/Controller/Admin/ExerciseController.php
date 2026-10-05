<? 
namespace App\Controller\Admin;


use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

use User;
use Cake\ORM\TableRegistry;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Validation\Validator;

class ExerciseController extends AppController
{


	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->layout('admin');
        $this->set("section", 'help');
    }

    public function isAuthorized($user) {
        
        return true;
    }

    public function index() {

    	//$users = TableRegistry::get("Users")->find('all', []);
    	$uid = $this->Auth->user('id');
    	$musculgroups = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises" => ["conditions" => ["deleted" => 0, 'owner' => $uid]]], "conditions" => ["deleted" => 0, 'owner' => $uid]]);
		$this->set("musculgroups", $musculgroups);
		
		//$this->set("users", $users);

    }

    public function create() {
    	$uid = $this->Auth->user('id');
    	$musculgroups = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises" => ["conditions" => ["deleted" => 0, 'owner' => $uid]]], "conditions" => ["deleted" => 0, 'owner' => $uid]]);
		$this->set("musculgroups", $musculgroups);
    	if ($this->request->is("post")) {
    		//echo "tut";
			$exercises = TableRegistry::get("Exercise");
			$exercise = $exercises->newEntity();
			//$exercise = $exercises->patchEntity($exercise, $this->request->data);

			$exercise->name = $this->request->data['name'];
			$exercise->owner = $this->Auth->user('id');
			$exercise->description = $this->request->data['description'];
            $target_path = WWW_ROOT . 'img/excersices/';
            $exercise->img = $this->request->data['img']['name'];
            $exercise->video = ($this->request->data['video']['name'] != '')?$this->request->data['video']['name']:null;

           	/*if ($musculgroups->save($musculgroup)) {

							$this->Flash->Success('Упражнение успешно добавлена');
							return $this->redirect(['action' => 'index']);	
						}*/
            $file_name = $this->request->data['img']['name'];
            //$this->set("files", $this->request->data);/*
            $parts = explode(".", $file_name);
            $fname = $parts[0];
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_file_name = $fname .  "." . $ext;
            //$path = $new_file_name;
            $to_path = $target_path . $fname . "." . $ext; //set the target path with a new name of image
            //echo $path;
            //echo $file_name;

            if ($file_name != '') {

                //echo "tut 2";
                if (move_uploaded_file($_FILES['img']['tmp_name'], $to_path)) {
                    
                	//Грузим видео
                	$target_path = WWW_ROOT . 'video/exercises/';
                    $file_name = $this->request->data['video']['name'];
            //$this->set("files", $this->request->data);/*
            		$parts = explode(".", $file_name);
            		$ext = pathinfo($file_name, PATHINFO_EXTENSION);
            		$fname = $parts[0];
            		$new_file_name = $fname .  "." . $ext;
            //$path = $new_file_name;
            		$to_path = $target_path . $fname . "." . $ext; //set the target path with a new name of image
            //echo $path;

            		if ($file_name != '') {

                		if (!move_uploaded_file($_FILES['video']['tmp_name'], $to_path)) {
            

                				$this->Flash->error("Не удалось загрузить файл.");

		                    
						} 
                	}
                	$validator = $exercises->validationDefault(new Validator());
							$errors = $validator->errors($this->request->data());
							if (!empty($errors)) {
								foreach ($errors as $field => $fieldErrors) {
									foreach ($fieldErrors as $message) {
										$this->Flash->error($message);
									}
								}
							}
								//echo($errors);
							else {

								if ($exercises->save($exercise)) {

									$exercisesmusculgroups = TableRegistry::get("ExerciseMusculgroup");
									$exercisesmusculgroup = $exercisesmusculgroups->newEntity();
									$exercisesmusculgroup->musculgroup_id = $this->request->data['musculgroup'];
									$exercisesmusculgroup->exercise_id = $exercise->id;
									$exercisesmusculgroups->save($exercisesmusculgroup);
									$this->Flash->Success('Упражнение успешно добавлено');
									return $this->redirect(['action' => 'index']);	
								}
							}
                } else {
                    $this->Flash->error("Не удалось загрузить файл.");
                }
            } else {
            	$validator = $exercises->validationDefault(new Validator());
							$errors = $validator->errors($this->request->data());
							if (!empty($errors)) {
								foreach ($errors as $field => $fieldErrors) {
									foreach ($fieldErrors as $message) {
										$this->Flash->error($message);
									}
								}
							}
								//echo($errors);
							else {

								if ($exercises->save($exercise)) {

									$exercisesmusculgroups = TableRegistry::get("ExerciseMusculgroup");
									$exercisesmusculgroup = $exercisesmusculgroups->newEntity();
									$exercisesmusculgroup->musculgroup_id = $this->request->data['musculgroup'];
									$exercisesmusculgroup->exercise_id = $exercise->id;
									$exercisesmusculgroups->save($exercisesmusculgroup);
									$this->Flash->Success('Упражнение успешно добавлено');
									return $this->redirect(['action' => 'index']);	
								}
							}
            }

			
		}
	}
	
	
/***********************************
 * Изменение упражнения
 *************************************/
	public function edit($id = null) {
		$exercise = null;
		if ($id != null)
			//$exercise = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises" => array("conditions" => array("Exercises.id" => $id))]])->all();
			$exercise = TableRegistry::get("Exercise")->find('all',  [
				"contain" => ["Musculgroups"], "conditions" => ["id" => $id]])->first();
			
		if ($this->request->is("post")) {
			$exercises = TableRegistry::get("Exercise");
			$exercise = $exercises->get($this->request->data["id"]);
			$exercise->name = $this->request->data['name'];
			$exercise->description = $this->request->data['description'];
			if ($this->request->data['img']['name'] != null && $this->request->data['img']['name'] != "") {
				//Delete old image
				$target_path = WWW_ROOT . 'img/excersices/';
				if ($exercise->img != null && $exercise->img != "")
					unlink($target_path.$exercise->img);
				$exercise->img = $this->request->data['img']['name'];
				$file_name = $this->request->data['img']['name'];
				//$this->set("files", $this->request->data);/*
				$parts = explode(".", $file_name);
				$fname = $parts[0];
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				$new_file_name = $fname .  "." . $ext;
				//$path = $new_file_name;
				$to_path = $target_path . $fname . "." . $ext; //set the target path with a new name of image
				//echo $path;

				if ($file_name != '') {

					if (move_uploaded_file($_FILES['img']['tmp_name'], $to_path)) {
					}
				}
			}
			if ($this->request->data['video']['name'] != null && $this->request->data['img']['name'] != "") {
				//Delete old image
				$target_path = WWW_ROOT . 'video/excersices/';
				if ($exercise->video != null && $exercise->video != "")
					unlink($target_path.$exercise->video);
				$exercise->video = $this->request->data['video']['name'];
				$file_name = $this->request->data['video']['name'];
				//$this->set("files", $this->request->data);/*
				$parts = explode(".", $file_name);
				$fname = $parts[0];
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				$new_file_name = $fname .  "." . $ext;
				//$path = $new_file_name;
				$to_path = $target_path . $fname . "." . $ext; //set the target path with a new name of image
				//echo $path;

				if ($file_name != '') {

					if (move_uploaded_file($_FILES['video']['tmp_name'], $to_path)) {
					}
				}
			}
			$validator = $exercises->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			if (!empty($errors)) {
				$this->set("exercise", $exercise);
				$this->set("error", $errors);
				$this->Flash->error($errors);
			}
			else {
				if ($exercises->save($exercise)) {
					//echo($exercise->id);
					$exercisesmusculgroups = TableRegistry::get("ExerciseMusculgroup");
					$exercisesmusculgroups->deleteAll([ 'exercise_id' => $exercise->id ]);
					$exercisesmusculgroup = $exercisesmusculgroups->newEntity();
					$exercisesmusculgroup->musculgroup_id = intval($this->request->data['musculgroup']);
					$exercisesmusculgroup->exercise_id = $exercise->id;
					$exercisesmusculgroups->save($exercisesmusculgroup);
					$this->Flash->Success('Упражнение успешно изменено');
					return $this->redirect(['action' => 'index']);	
				}
			}
		}
		$uid = $this->Auth->user('id');
    	$musculgroups = TableRegistry::get("Musculgroup")->find('all', ["contain" => ["Exercises" => ["conditions" => ["deleted" => 0, 'owner' => $uid]]], "conditions" => ["deleted" => 0, 'owner' => $uid]]);
		$this->set("musculgroups", $musculgroups);
		$this->set("edit", true);
		$this->set("exercise", $exercise);
		$this->render('create');
	}

    public function createmusculgroup() {
    	if ($this->request->is("post")) {

			$musculgroups = TableRegistry::get("Musculgroup");
			$musculgroup = $musculgroups->newEntity();
			$musculgroup = $musculgroups->patchEntity($musculgroup, $this->request->data);
			$musculgroup->owner = $this->Auth->user('id');
			
			$validator = $musculgroups->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			if (!empty($errors)) {
				$this->set("musculgroup", $musculgroup);
				$this->Flash->error($errors);
			}
				//echo($errors);
			else {

				if ($musculgroups->save($musculgroup)) {

					$this->Flash->Success('Группа мышц успешно добавлена');
					return $this->redirect(['action' => 'index']);	
				}
			}
		}
	}
	
	public function deletefile() {
		$this->autoRender = false;
		if ($this->request->is("post")) {
			$exercises = TableRegistry::get("Exercise");
			$exercise = $exercises->get($this->request->data["id"]);
			if ($this->request->data["type"] = "ïmg" && $exercise->img != null) {
				$target_path = WWW_ROOT . 'img/excersices/';
				unlink($target_path.$exercise->img);
				$exercise->img = null;
				$exercises->save($exercise);
				echo "{\"status\": \"success\", \"message\" : \"Файл успешно удалён\"}";
			} else if ($this->request->data["type"] = "video" && $exercise->video != null) {
				$target_path = WWW_ROOT . 'video/excersices/';
				unlink($target_path.$exercise->video);
				$exercise->video = null;
				$exercises->save($exercise);
				echo "{\"status\": \"success\", \"message\" : \"Файл успешно удалён\"}";
			} else {
				echo "{\"status\": \"error\", \"message\" : \"Неверный тип ресурса\"}";	
			}
		} else {
			echo "{\"status\": \"error\", \"message\" : \"Неверный запрос\"}";
		}
	}

    public function delete($id) {

		$this->autoRender = false;

		$exersices = TableRegistry::get("Exercise");
		$exersice = $exersices->get($id);
		//$exersices->delete($exersice);
		$exersice->deleted = 1;
		$exersices->save($exersice);
		return $this->redirect(['action' => 'index']);
	}

}