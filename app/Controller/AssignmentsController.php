<?php
App::uses('AppController', 'Controller');
/**
 * Assignments Controller
 *
 * @property Assignment $Assignment
 */
class AssignmentsController extends AppController {

	public function index(){
		$this->Assignment->recursive = -1;
		$assignments = $this->Assignment->find('all');
		$results = array();
		foreach($assignments as $assignment){
			$results[] = $assignment['Assignment'];
		}
		$this->autoRender = false;
		echo json_encode($results);
	}
}
