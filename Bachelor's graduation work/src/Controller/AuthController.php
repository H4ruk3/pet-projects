<?php

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use App\Controller\AppController;
use Cake\Event\Event;
use User;
use Cake\ORM\TableRegistry;
use Cake\Log\Log;
use Cake\Controller\Component\CookieComponent;
use Cake\Routing\Router;
use Cake\Mailer\Email;


require_once(ROOT.'/src/Controller/Component/recaptchalib.php');
  

class AuthController extends AppController
{

	var $components = array('Cookie');

	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    	$this->Auth->allow(['reg', 'registration', 'register']);
    }

    public function index() {
    	if ($this->Auth->user()) {
    		return $this->redirect(['controller' => 'Profile', 'action' => 'index']);
    	} else  if ($this->Cookie->check("rememberMe")) {
    		$cookie = $this->Cookie->read('rememberMe');
            if (!is_null($cookie)) {
                $users = TableRegistry::get("Users")->find('all', ['conditions' => ['apiKey' => $cookie['apikey']]]);
				if ($users->count() > 0)
				{
					$user = $users->first();
					$this->Auth->setUser($user);
					$this->redirect(['controller' => 'Profile', 'action' => 'index']);
				}
			} 
    	}
    	else
    	{
    		$this->viewBuilder()->layout('login');
    	}
    }

    /************************************************************
    Метод для входа на сайт.
    ************************************************************/
	public function login() {
		$this->autoRender = false;
		if ($this->request->is('post')) {
            $user = $this->Auth->identify();

            if ($user) {

                $this->Auth->setUser($user);
                echo "{\"status\":\"success\"}";
            }
            else
            	echo "{\"status\":\"error\", \"message\":\"Неверный логин или пароль\"}";
        }
	}

	/************************************************************
    Определяем куда перенаправить пользователя после логина.
    ************************************************************/
    public function postlogin($rem=null) {
		$user = $this->Auth->user();
		if ($user) {
			if ($rem != null) {
				if (!$user["apiKey"])	{

						$user["apiKey"] = sha1($user["username"].$user["password"]);
						$this->Users->save($user);            	
	            	}
        		    $cookie = array();
            		$cookie['apikey'] = $user["apiKey"];
            		$this->Cookie->write('rememberMe', $cookie, true, "1 week");
			}


			if ($user['role'] == 'admin')
				return $this->redirect(['controller' => 'Users', 'action' => 'index', 'prefix' => 'admin']);
			else {
				$id = $user['id'];
                $cntprofiles = 0;
                if ($user["role"] == "trainer") {
                    $cntprofiles = TableRegistry::get("Trainerprofile")->find('all', [ 'conditions' => [ 'users_id' => $id, 'active' => true ] ])->count();    
                } else {
                    $cntprofiles = TableRegistry::get("Profiles")->find('all', [ 'conditions' => [ 'userId' => $id, 'active' => true ] ])->count();
                }
				if ($cntprofiles == 0) 
					$this->redirect(['controller' => 'profile', 'action' => 'create']);
				else {
					$this->redirect(['controller' => 'profile', 'action' => 'index']);
				}
			}
		} else {
			return $this->redirect(['controller' => 'Auth', 'action' => 'index']);
		}
	}

    /************************************************************
    Выход пользователя из профиля.
    ************************************************************/
	public function logout() {
		$this->Cookie->delete('rememberMe');
		$this->Cookie->delete('name');
		return $this->redirect($this->Auth->logout());
	}

    /************************************************************
     Вывод формы регистрации
    *************************************************************/
    public function registration() {
        $this->viewBuilder()->layout('login');
    }

    /************************************************************
     Вывод формы регистрации
    *************************************************************/
    public function register($type) {
        $this->viewBuilder()->layout('login');
        $this->viewBuilder()->template('index');
        if ($type == null || !in_array($type, array("user", "trainer", "corp")))
            $this->redirect(['controller' => 'Auth', 'action' => 'registration']);
        $this->set("isreg", true);
        $this->set("role", $type);
    }

	/************************************************************
    Метод регистрации нового пользователя
    ************************************************************/
    public function reg() {

		$this->autoRender = false;
		$user = $this->Users->newEntity();

		if ($this->request->is('post'))  {

			//Проверяем правильность капчи
			//Раскоментить в релиз версии
			/*$privatekey = "6Ldj9jcUAAAAAMP2DxPvd1zO_-gjhqJIyWrhF1q9";
			$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
			Log::write('debug', $resp);
			$response = $_POST["g-recaptcha-response"];
			$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array(
		'secret' => '6Ldj9jcUAAAAAMP2DxPvd1zO_-gjhqJIyWrhF1q9',
		'response' => $_POST["g-recaptcha-response"]
	);
	$options = array(
		'http' => array (
			'method' => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success=json_decode($verify);



*/
            if (false) {
			//if (!$resp->is_valid/*true*//*) {
			//if ($captcha_success->success==false) {
			    // What happens when the CAPTCHA was entered incorrectly
			    echo "{\"status\":\"error\", \"message\":\"Вы не прошли проверку на робота. Заполните капчу снова.\"}";
			} else {

				//Проверяем уникальность регистрируемого пользователя
				$usercount = TableRegistry::get("Users")->find('all', [ 'conditions' => [ 'username' => $_POST["username"] ] ])->count();

				if ($usercount > 0)
					echo "{\"status\":\"error\", \"message\":\"Пользователь с таким логином уже существует.\"}";
				else {

					$user = $this->Users->PatchEntity($user, $this->request->data);
					//$this->set("user1", $this->request->data);
					
					if (count($user->errors()) > 0)
						//echo $user->errors();
                        var_dump($user->errors());
                    $user->created = date("Y-m-d h:m:s");
					if ($this->Users->save($user)) {

						//$this->Flash->success(__('The user has been saved.'));
		                $this->Auth->setUser($user);
		                //return $this->redirect(['controller' => 'Profile', 'action' => 'create']);
		                echo "{\"status\":\"success\"}";
					} else 
					echo "{\"status\":\"error\"}";
				}
			}
		}
	}


	/*****************************************
		Восстановление пароля
	******************************************/
	public function password()
    {
        $this->autoRender = false;
        //echo "ok";
        if ($this->request->is('post')) {
        	//echo "ok";
            //$query = $this->Users->findByEmail($this->request->data['username']);
                        //Проверяем правильность капчи
            //Раскоментить в релиз версии
            $privatekey = "6Ldj9jcUAAAAAMP2DxPvd1zO_-gjhqJIyWrhF1q9";
            $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
            Log::write('debug', $resp);
            $response = $_POST["g-recaptcha-response"];
            $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => '6Ldj9jcUAAAAAMP2DxPvd1zO_-gjhqJIyWrhF1q9',
        'response' => $_POST["g-recaptcha-response"]
    );
    $options = array(
        'http' => array (
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captcha_success=json_decode($verify);


        if ($captcha_success->success==false) {
                // What happens when the CAPTCHA was entered incorrectly
                echo "{\"status\":\"error\", \"message\":\"Вы не прошли проверку на робота. Заполните капчу снова.\"}";
            } else {
            $user = $this->Users->find('all', [
		            		'conditions' => [
		            			'username' => $this->request->data['username'],
		            		] 
		            	])
		            	->first();
            //$user = $query->first();
            if (is_null($user)) {
                //$this->Flash->error('Email address does not exist. Please try again');
                echo "{\"status\" : \"error\", \"message\" : \"Пользователь с указанным email не существует\"}";
            } else {
                $passkey = uniqid();
                $url = Router::Url(['controller' => 'auth', 'action' => 'reset'], true) . '/' . $passkey;
                $timeout = time() + DAY;
                if ($this->Users->updateAll(['passkey' => $passkey, 'timeout' => $timeout], ['id' => $user->id])){
                    $this->sendResetEmail($url, $user);
                    //$this->redirect(['action' => 'login']);
                    //$this->Flash->success('Email address does not exist. Please try again');
                    echo "{\"status\":\"success\"}";
                } else {
                    //$this->Flash->error('Error saving reset passkey/timeout');
                    echo "{\"status\" : \"error\", \"message\" : \"Ошибка восстановления пароля.\"}";
                }
            }
        }
        } else echo "{\"status\" : \"error\", \"message\" : \"Неверный запрос.\"}";
    }

    private function sendResetEmail($url, $user) {
        $email = new Email();
        $email->template('resetpw');
        $email->emailFormat('both');
        $email->from('no-reply@coachme.ru');
        $email->to($user->username, $user->username);
        $email->subject('Reset your password');
        $email->viewVars(['url' => $url, 'username' => $user->username]);
        //var_dump($user);
        if ($email->send()) {
            $this->Flash->success(__('На указанный адрес электронной почты была отправлена ссылка для восстановления пароля. Пожалуйста пройдите по этой ссылке.'));
        } else {
            $this->Flash->error(__('Не удалось отправить сообщение: ') . $email->smtpError);
        }
    }

    public function reset($passkey = null) {
        if ($passkey) {
            $query = $this->Users->find('all', ['conditions' => ['passkey' => $passkey, 'timeout >' => time()]]);
            $user = $query->first();
            if ($user) {
                if (!empty($this->request->data)) {
                    // Clear passkey and timeout
                    $this->request->data['passkey'] = null;
                    $this->request->data['timeout'] = null;
                    $user = $this->Users->patchEntity($user, $this->request->data);
                    if ($this->Users->save($user)) {
                        $this->Flash->success(__('Ваш пароль успешно изменён.'));
                        return $this->redirect(array('action' => 'index'));
                    } else {
                        $this->Flash->error(__('Пароль не может быть изменён. Пожалуйста повторите попытку снова.'));
                    }
                } else {
                    $this->viewBuilder()->layout('login');
                }
            } else {
                $this->Flash->error('Неверный код. Пожалуйста проверьте свой email или попробуйте снова');
                $this->redirect(['action' => 'password']);
            }
            unset($user->password);
            $this->set(compact('user'));
        } else {
            $this->redirect('/');
        }
    }

}