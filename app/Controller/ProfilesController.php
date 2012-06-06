<?php
App::uses('AppController', 'Controller');
/**
 * Profiles Controller
 *
 * @property Profile $Profile
 */
class ProfilesController extends AppController {

	public function index(){
		$this->Profile->recursive = -1;
		$profiles = $this->Profile->find('all');
		$results = array();
		foreach($profiles as $profile){
			$results[] = $profile['Profile'];
		}
		$this->autoRender = false;
		echo json_encode($results);
	}
}
