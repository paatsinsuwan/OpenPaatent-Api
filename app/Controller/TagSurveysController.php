<?php
App::uses('AppController', 'Controller');
/**
 * TagSurveys Controller
 *
 * @property TagSurvey $TagSurvey
 */
class TagSurveysController extends AppController {

	public function index(){
		$this->TagSurvey->recursive = -1;
		$tagsurveys = $this->TagSurvey->find('all');
		$results = array();
		foreach($tagsurveys as $tagsurvey){
			$results[] = $tagsurvey['TagSurvey'];
		}
		$this->autoRender = false;
		echo json_encode($results);
	}
}
