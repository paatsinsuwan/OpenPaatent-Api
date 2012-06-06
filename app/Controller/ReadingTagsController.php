<?php
App::uses('AppController', 'Controller');
/**
 * ReadingTags Controller
 *
 * @property ReadingTag $ReadingTag
 */
class ReadingTagsController extends AppController {

	public function index(){
		$this->ReadingTag->recursive = -1;
		$readingTags = $this->ReadingTag->find('all');
		$results = array();
		foreach($readingTags as $readingTag){
			$results[] = $readingTag['ReadingTag'];
		}
		$this->autoRender = false;
		echo json_encode($results);
	}
}
