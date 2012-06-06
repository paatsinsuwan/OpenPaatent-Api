<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

	public function index(){
		$this->User->recursive = -1;
		$users = $this->User->find('all');
		$results = array();
		foreach($users as $user){
			$results[] = $user['User'];
		}
		$this->autoRender = false;
		header('Content-type: application/json');
		echo json_encode($results);
	}
}
