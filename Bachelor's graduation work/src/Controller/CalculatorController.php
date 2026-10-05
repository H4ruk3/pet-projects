<? 
namespace App\Controller;


use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

use User;
use Cake\ORM\TableRegistry;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Validation\Validator;

class CalculatorController extends AppController
{
	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if ($this->Auth->user("role") == "corp")
        	$this->viewBuilder()->layout('corpuser');
        else	
        	$this->viewBuilder()->layout('redesignmain');
        $this->set("section", "calc");
    }

    public function Somatype() {

    }

    private function getFullYears($birthdayDate) {
        $tz = new \DateTimeZone('Europe/Moscow');
        $interval = $birthdayDate->diff(new \DateTime('now', $tz));
        return $interval->format("%Y");
    }

    public function Kalories() {
    $id = $this->Auth->user("id");
    $profiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $id, 'active' => true] ]);
    if ($profiles->count() > 0) {
        $p = $profiles->first();
        // age уже хранится как число, birthday не используется
        $this->set("user", $p);
    }
}

}