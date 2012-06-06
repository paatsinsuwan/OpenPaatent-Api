<?php
App::uses('AppController', 'Controller');
/**
 * Degrees Controller
 *
 * @property Degree $Degree
 */
class DegreesController extends AppController {

	public function index(){
		$this->Degree->recursive = -1;
		$degrees = $this->Degree->find('all');
		$results = array();
		foreach($degrees as $degree){
			$results[] = $degree['Degree'];
		}
		$this->autoRender = false;
		echo json_encode($results);
	}
}
