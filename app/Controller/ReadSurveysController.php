<?php
App::uses('AppController', 'Controller');
/**
 * ReadSurveys Controller
 *
 * @property ReadSurvey $ReadSurvey
 */
class ReadSurveysController extends AppController {

	public function index(){
		$this->ReadSurvey->recursive = -1;
		$readsurveys = $this->ReadSurvey->find('all');
		$results = array();
		foreach($readsurveys as $readsurvey){
			$results[] = $readsurvey['ReadSurvey'];
		}
		$this->autoRender = false;
		header('Content-type: application/json');
		echo json_encode($results);
	}
}
