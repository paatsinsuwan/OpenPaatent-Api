<?php
App::uses('AppController', 'Controller');
// App::uses('JsonResponse', 'Json.Network');
/**
 * Documents Controller
 *
 * @property Document $Document
 */
class DocumentsController extends AppController {
	
	public function index(){
		$this->Document->recursive = -1;
		$documents = $this->Document->find('all');
		$results = array();
		foreach($documents as $document){
			$results[] = $document['Document'];
		}
		$this->autoRender = false;
		header('Content-type: application/json');
		echo json_encode($results);
	}
}
