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
              $pr = $user[0]['Profile']['professional_role'];
              $prof_array[] = $pr;
              $prof_role_list[] = $pr;
            }
          }
          $results[] = array("name" => $rt['ReadingTag']['title'], "imports" => $prof_array);
        }
        foreach($prof_role_list as $pr){
          $results[] = array("name" => $pr, "imports" => array());
        }
        Configure::write("debug", 0);
        $this->set(compact('results'));
      }
      else{
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
              $prof_array[] = $user[0]['Profile']['professional_role'];
            }
          }
          $results[] = array("name" => $rt['ReadingTag']['title'], "imports" => $prof_array);
        }
        //debug($results);
        //die;
        // foreach($reading_tags as $rt){
        //           $res = $scl->Query("$rt", "all_tags");
        //           $tmp = array("name" => $rt);
        //           $matches_array = array();
        //           if(!empty($res['matches'])){
        //             foreach($res['matches'] as $a_tag => $val){
        //               // $a_survey = $this->TagSurvey->findById($a_tag);
        //               //             $id = $a_survey['Assignment']['user_id'];
        //               //             $user_ids_list["$id"] = $id;
        //             }
        //             // $users = $this->User->find("all", array(
        //             //               'conditions' => array(
        //             //                 'User.id' => $user_ids_list,
        //             //                 'NOT' => array(
        //             //                   'User.email' => $excluding_list
        //             //                 )
        //             //               )
        //             //             ));
        //             //             //unset($user_ids_list);
        //             //             foreach($users as $user){
        //             //               if(!empty($user['Profile']['professional_role'])){
        //             //                 $matches_array[] = $user['Profile']['professional_role']. " " .$user['User']['username'];
        //             //                 $match_list[] = array("name" => $user['Profile']['professional_role']. " " .$user['User']['username']);
        //             //                 $duration = $user['Profile']['work_duration'];
        //             //                 $year_of_experiences[$duration] = $duration;
        //             //               }
        //             //             }
        //             //           }
        //           //$tmp['imports'] = $matches_array;
        //           //$results[] = $tmp;
        //         }
        // foreach($match_list as &$item){
        //           $current_group = $this->User->find("all", array(
        //             'conditions' => array(
        //               'User.id' => $user_ids_list,
        //               'Profile.professional_role' => $item
        //             )
        //           ));
        //           $profs = array();
        //           foreach($current_group as $a_member){
        //             $profs[] = $a_member['Profile']['professional_role']. " " .$a_member  ['User']['username'];
        //           }
        //           $item['imports'] = $profs;
        //           //debug($current_group);die;
        //           $results[] = $item;
        //         }
        
        // foreach($year_of_experiences as $exp){
        //           $results[] = array("name" => $exp, "imports" => array());
        //         }
        //debug($results); die;
        $this->set(compact($results));
      }
      
      
      //debug($results);
      //die;
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
