<?php
App::import('Model', 'Document');
App::import('Model', 'Assignment');
App::import('Model', 'User');
App::import('Model', 'ReadingTag');
App::import('Model', 'TagSurvey');
App::import('Model', 'Profile');

$sphinxapi = APP."Vendor/sphinxapi.php";

require "$sphinxapi";
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Pages';

/**
 * Default helper
 *
 * @var array
 */
	public $helpers = array('Html', 'Session');

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

/**
 * Displays a view
 *
 * @param mixed What page to display
 * @return void
 */
	public function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}
	public function visualize($query = null)
	{
	  $document_id = 6;
	  $excluding_list = array('cbkm.wong@gmail.com','danieladhunter@gmail.com','pat_nean@hotmail.com', '');
	  $keywords = array("black box");
	  $search_results = array(
      "name" => "document$document_id", 
      "imports" => array(
        array(
          array("name" => "document$document_id.Keywords", "field_name" => "all_tags")
        ),
        array(
          array("name" => "document$document_id.PersonalKnowledge", "field_name" => "personal_tags")
        ), 
        array(
          array("name" => "document$document_id.OutsideKnowledge", "field_name" => "outside_tags")
        ), 
        array(
          array("name" => "document$document_id.Metadata", "field_name" => "extra_info_tags")
        )
      )
    );
    $this->ReadingTag = new ReadingTag();
    $this->ReadingTag->bindModel(array(
      'hasAndBelongsToMany' => array(
        'User'
      )
    ));
    $this->Profile = new Profile();
    $this->User = new User();
    if(!empty($query)){
      
    }
    else{
      if($this->RequestHandler->isAjax()){
        $prof_role_list = array();
        $duration_list = array();
        $reading_tags = $this->ReadingTag->find('all', array('conditions' => array('ReadingTag.document_id' => $document_id)));
        foreach($reading_tags as $rt){
          $prof_array = array();
          foreach($rt['User'] as $user){
            $user = $this->User->find('all', array(
              'conditions' => array(
                'User.id' => $user['id'], 
                'NOT' => array(
                  'User.email' => $excluding_list
                )
              )
            ));
            if(!empty($user[0]['Profile']['professional_role'])){
              $pr = "role.".Inflector::camelize(Inflector::slug($user[0]['Profile']['professional_role']." ".$user[0]['User']['username']));
              $prof_array[] = $pr;
              $prof_role_list[] = $pr;
            }
          }
          $results[] = array("name" => "all_tags.".Inflector::camelize(Inflector::slug($rt['ReadingTag']['title'])), "imports" => $prof_array);
        }
        foreach($prof_role_list as $pr){
          $current_role_duration = array();
          $current_role_username = explode(" ", Inflector::humanize(Inflector::underscore($pr)));
          $current_user = $this->User->findByUsername($current_role_username[count($current_role_username) - 1]);
          if(preg_match("/\\x{2D}/", $current_user['Profile']['work_duration'])){
            $duration_string = preg_replace("/\\x{2D}/", "to", $current_user['Profile']['work_duration']);
          }
          else{
            $duration_string = $current_user['Profile']['work_duration'];
          }
          if(!empty($duration_string)){
            $crd = "duration.".Inflector::camelize(Inflector::slug($duration_string));
          }
          if(!empty($crd)){
            $current_role_duration[] = $crd;
            $duration_list[] = $crd;
          }
          $results[] = array("name" => $pr, "imports" => $current_role_duration);
        }
        foreach($duration_list as $duration){
          $results[] = array("name" => $duration, "imports" => array());
        }
        Configure::write("debug", 0);
        //debug($results);
        //die;
        $this->set(compact('results'));
      }
      else{
        // $prof_role_list = array();
        //         $duration_list = array();
        //         $reading_tags = $this->ReadingTag->find('all', array('conditions' => array('ReadingTag.document_id' => $document_id)));
        //         foreach($reading_tags as $rt){
        //           $prof_array = array();
        //           foreach($rt['User'] as $user){
        //             $user = $this->User->find('all', array(
        //               'conditions' => array(
        //                 'User.id' => $user['id'], 
        //                 'NOT' => array(
        //                   'User.email' => $excluding_list
        //                 )
        //               )
        //             ));
        //             if(!empty($user[0]['Profile']['professional_role'])){
        //               $pr = "role.".Inflector::camelize(Inflector::slug($user[0]['Profile']['professional_role']." ".$user[0]['User']['username']));
        //               $prof_array[] = $pr;
        //               $prof_role_list[] = $pr;
        //             }
        //           }
        //           $results[] = array("name" => "all_tags.".Inflector::camelize(Inflector::slug($rt['ReadingTag']['title'])), "imports" => $prof_array);
        //         }
        //         foreach($prof_role_list as $pr){
        //           $current_role_duration = array();
        //           $current_role_username = explode(" ", Inflector::humanize(Inflector::underscore($pr)));
        //           $current_user = $this->User->findByUsername($current_role_username[count($current_role_username) - 1]);
        //           if(preg_match("/\\x{2D}/", $current_user['Profile']['work_duration'])){
        //             $duration_string = preg_replace("/\\x{2D}/", "to", $current_user['Profile']['work_duration']);
        //           }
        //           else{
        //             $duration_string = $current_user['Profile']['work_duration'];
        //           }
        //           if(!empty($duration_string)){
        //             $crd = "duration.".Inflector::camelize(Inflector::slug($duration_string));
        //           }
        //           if(!empty($crd)){
        //             $current_role_duration[] = $crd;
        //             $duration_list[] = $crd;
        //           }
        //           $results[] = array("name" => $pr, "imports" => $current_role_duration);
        //         }
        //         foreach($duration_list as $duration){
        //           $results[] = array("name" => $duration, "imports" => array());
        //         }
        //         Configure::write("debug", 0);
        //         debug($results);
        //         die;
        //         $this->set(compact('results'));
      }
    }
	}
	
	private function getUserIdsListByTag($tag = null){
    $result = array();
    if(!empty($tag)){
      $this->ReadingTag->bindModel(
        array('hasAndBelongsToMany' => array(
          'User' => array(
            'classname' => 'User'
          )
        ))
      );
      $data = $this->ReadingTag->find("first", array("conditions" => array("ReadingTag.title" => $tag)));
      
      //debug($tmp); die;
	    $scl = new SphinxClient();
	    $query_results = $scl->Query($tag, "all_tags");
     
      if(!empty($query_results['matches'])){
        foreach($query_results['matches'] as $a_tag_id_as_key => $val){
          $tagsurvey = $this->TagSurvey->findById($a_tag_id_as_key);
          $data['User']['id'] = $tagsurvey['Assignment']['user_id'];
          $rt_id = $data['ReadingTag']['id'];
          $u_id = $data['User']['id'];
          $this->ReadingTag->query('INSERT INTO `reading_tags_users` SET `reading_tag_id`=' . $rt_id . ', `user_id`=' . $u_id);
        }
      }
      //debug($result);die;
	  }
	  //return $result;
	}
	
	private function findMatch($options = null)
	{
	  if(!empty($options)){
	    $results = $options['Model']->find("all", array('conditions' => $options['conditions']));
	  }
	  debug($results);
	}
}
