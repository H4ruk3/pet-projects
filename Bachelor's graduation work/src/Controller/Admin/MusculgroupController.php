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

class MusculgroupController extends AppController
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
    	$musculgroups = TableRegistry::get("Musculgroup")->find('all', ['conditions' => ['deleted'=>0, 'owner' => $uid]]);
		$this->set("musculgroups", $musculgroups);
		
		//$this->set("users", $users);

    }

	public function create() {
    	if ($this->request->is("post")) {

			$musculgroups = TableRegistry::get("Musculgroup");
			$musculgroup = $musculgroups->newEntity();
			$musculgroup = $musculgroups->patchEntity($musculgroup, $this->request->data);
			
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

    public function edit() {

		$musculgroups = TableRegistry::get("Musculgroup");

		if ($this->request->is("post")) {
			$musculgroup = $musculgroups->get($_POST['id']);
			$musculgroup->name = $_POST['name'];
			$validator = $musculgroups->validationDefault(new Validator());
			$errors = $validator->errors($this->request->data());
			if (!empty($errors)) {
				//$this->set("musculgroup", $musculgroup);
				$this->Flash->error($errors);
			}
				//echo($errors);
			else {

				if ($musculgroups->save($musculgroup)) {

					$this->Flash->Success('Группа мышц успешно изменена');
					return $this->redirect(['action' => 'index']);	
				}
			}
		}
		//return $this->redirect(['action' => 'index']);
	}

    public function delete($id) {

		$this->autoRender = false;

		$musculgroups = TableRegistry::get("Musculgroup");
		$musculgroup = $musculgroups->get($id);
		//$musculgroups->delete($musculgroup);
		$musculgroup->deleted = 1;
		$musculgroups->save($musculgroup);
		return $this->redirect(['action' => 'index']);
	}

}