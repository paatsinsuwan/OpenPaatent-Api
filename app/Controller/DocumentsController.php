<?php
App::uses('AppController', 'Controller');
/**
 * Documents Controller
 *
 * @property Document $Document
 */
class DocumentsController extends AppController {
	
	public function index(){
		if($this->RequestHandler->accepts('json')){
			$documents = $this->Document->find('all');
			$results = array();
			foreach($documents as $document){
				$results[] = $document['Document'];
			}
			$this->set(compact('results'));
		}
		else{
			parent::nonApiAccess();
		}
	}
}
