<?php
App::import('Model', 'Document');
App::import('Model', 'Assignment');
App::import('Model', 'User');
App::import('Model', 'ReadingTag');
App::import('Model', 'TagSurvey');
App::import('Model', 'Profile');
App::import('Model', 'ReadingTagUser');

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
	public function flare_visualize($query = null, $doc_id = null, $keyword_type = null)
	{
	  $document_id = $doc_id;
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
    $this->Profile = new Profile();
    $this->User = new User();
    $this->ReadingTagUser = new ReadingTagUser();
    $this->TagSurvey = new TagSurvey();
    
    if(!empty($query)){
      // if($query == "add"){
      //         for($i = 1; $i < 7; $i++){
      //           $reading_tags = $this->ReadingTag->find('all', array('conditions' => array('ReadingTag.document_id' => $i)));
      //           foreach($reading_tags as $rt){
      //             $this->addToReadingTagUserByTag($rt['ReadingTag']['title']);
      //           }
      //         }
      //       }
      if($query == "layout1"){
        if($this->RequestHandler->isAjax()){
          $prof_role_list = array();
          $duration_list = array();
          $reading_tags = $this->ReadingTag->find('all', array(
            'conditions' => array(
              'ReadingTag.document_id' => $document_id,
             )
          ));
          foreach($reading_tags as $rt){
            $prof_array = array();
            foreach($rt['ReadingTagUser'] as $item){
              if($item['tag_section'] == $keyword_type){
                $user = $this->User->find('all', array(
                  'conditions' => array(
                    'User.id' => $item['user_id'], 
                    'NOT' => array(
                      'User.email' => $excluding_list
                    )
                  )
                ));
              }
              if(!empty($user[0]['Profile']['professional_role'])){
                $pr = "role.".Inflector::camelize(Inflector::slug($user[0]['Profile']['professional_role']." ".$user[0]['User']['username']));
                $prof_array[] = $pr;
                $prof_role_list[] = $pr;
              }
            }
            $results[] = array("name" => $keyword_type.".".Inflector::camelize(Inflector::slug($rt['ReadingTag']['title'])), "imports" => $prof_array);
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
          $this->set(compact('results'));
        }
        else{
          $array_keyword_type_map = array(
            "all_tags" => "General Keywords",
            "personal_tags" => "Personal Keywords",
            "outside_tags" => "Outside Keywords",
            "extra_info_tags" => "Metadata"
          );
          $tag_section_title = $array_keyword_type_map["$keyword_type"];
          $this->Document = new Document();
          $document = $this->Document->findById($doc_id);
          $results = array(
              "controller" => "pages", 
              "action" => "flare_visualize",
              $query,
              $doc_id,
              $keyword_type
          );
          $this->set(compact("results", "document", "tag_section_title"));
        }
      }
      else if($query == "layout2"){
        if($this->RequestHandler->isAjax()){
          $prof_role_list = array();
          $duration_list = array();
          $reading_tags = $this->ReadingTag->find('all', array(
            'conditions' => array(
              'ReadingTag.document_id' => $document_id,
             )
          ));
          foreach($reading_tags as $rt){
            $prof_array = array();
            foreach($rt['ReadingTagUser'] as $item){
              if($item['tag_section'] == $keyword_type){
                $user = $this->User->find('all', array(
                  'conditions' => array(
                    'User.id' => $item['user_id'], 
                    'NOT' => array(
                      'User.email' => $excluding_list
                    )
                  )
                ));
              }
              if(!empty($user[0]['Profile']['experience_level'])){
                $pr = "role.".Inflector::camelize(Inflector::slug($user[0]['Profile']['experience_level']." of  ".$user[0]['User']['username']));
                $prof_array[] = $pr;
                $prof_role_list[] = $pr;
              }
            }
            $results[] = array("name" => $keyword_type.".".Inflector::camelize(Inflector::slug($rt['ReadingTag']['title'])), "imports" => $prof_array);
          }
          foreach($prof_role_list as $pr){
            $current_role_duration = array();
            $current_role_username = explode(" ", Inflector::humanize(Inflector::underscore($pr)));
            $current_user = $this->User->findByUsername($current_role_username[count($current_role_username) - 1]);
            $duration_string = $current_user['Profile']['comfort_level'];
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
          $this->set(compact('results'));
        }
        else{
          $array_keyword_type_map = array(
            "all_tags" => "General Keywords",
            "personal_tags" => "Personal Keywords",
            "outside_tags" => "Outside Keywords",
            "extra_info_tags" => "Metadata"
          );
          $tag_section_title = $array_keyword_type_map["$keyword_type"];
          $this->Document = new Document();
          $document = $this->Document->findById($doc_id);
          $results = array(
              "controller" => "pages", 
              "action" => "flare_visualize",
              $query,
              $doc_id,
              $keyword_type
          );
          $this->set(compact("results", "document", "tag_section_title"));
        }
      }
      else if($query == "layout3"){
        if($this->RequestHandler->isAjax()){
          $prof_role_list = array();
          $duration_list = array();
          $reading_tags = $this->ReadingTag->find('all', array(
            'conditions' => array(
              'ReadingTag.document_id' => $document_id,
             )
          ));
          foreach($reading_tags as $rt){
            $prof_array = array();
            foreach($rt['ReadingTagUser'] as $item){
              if($item['tag_section'] == $keyword_type){
                $user = $this->User->find('all', array(
                  'conditions' => array(
                    'User.id' => $item['user_id'], 
                    'NOT' => array(
                      'User.email' => $excluding_list
                    )
                  )
                ));
              }
              if(!empty($user)){
                foreach($user[0]['Assignment'] as $user_assignment){
                  if($user_assignment['type'] == "tag"){
                    $tag_survey = $this->TagSurvey->find('first', array('conditions' => array('TagSurvey.assignment_id' => $user_assignment['id'])));
                    if(!empty($tag_survey['TagSurvey']['duration'])){
                      $pr = "DurationOfCompletion.".Inflector::camelize(Inflector::slug("complete in ". $tag_survey['TagSurvey']['duration']." by  ".$user[0]['User']['username']));
                      $results[] = array("name" => $pr, "imports" => array());
                      $prof_array[] = $pr;
                    }
                  }
                }
              }
            }
            $results[] = array("name" => $keyword_type.".".Inflector::camelize(Inflector::slug($rt['ReadingTag']['title'])), "imports" => $prof_array);
          }
          Configure::write("debug", 0);
          $this->set(compact('results'));
        }
        else{
          $array_keyword_type_map = array(
            "all_tags" => "General Keywords",
            "personal_tags" => "Personal Keywords",
            "outside_tags" => "Outside Keywords",
            "extra_info_tags" => "Metadata"
          );
          $tag_section_title = $array_keyword_type_map["$keyword_type"];
          $this->Document = new Document();
          $document = $this->Document->findById($doc_id);
          $results = array(
              "controller" => "pages", 
              "action" => "flare_visualize",
              $query,
              $doc_id,
              $keyword_type
          );
          $this->set(compact("results", "document", "tag_section_title"));
        }
      }
      else if($query == "layout4"){
        if($this->RequestHandler->isAjax()){
          $prof_role_list = array();
          $duration_list = array();
          $reading_tags = $this->ReadingTag->find('all', array(
            'conditions' => array(
              'ReadingTag.document_id' => $document_id,
             )
          ));
          foreach($reading_tags as $rt){
            $prof_array = array();
            foreach($rt['ReadingTagUser'] as $item){
              if($item['tag_section'] == $keyword_type){
                $user = $this->User->find('all', array(
                  'conditions' => array(
                    'User.id' => $item['user_id'], 
                    'NOT' => array(
                      'User.email' => $excluding_list
                    )
                  )
                ));
              }
              if(!empty($user)){
                foreach($user[0]['Assignment'] as $user_assignment){
                  if($user_assignment['type'] == "tag"){
                    $tag_survey = $this->TagSurvey->find('first', array('conditions' => array('TagSurvey.assignment_id' => $user_assignment['id'])));
                    if(!empty($tag_survey['TagSurvey']['expert_on_particular'])){
                      $pr = "level.".Inflector::camelize(Inflector::slug($user[0]['User']['username'] .$tag_survey['TagSurvey']['expert_on_particular']. " on the subject"));
                      $results[] = array("name" => $pr, "imports" => array());
                      $prof_array[] = $pr;
                    }
                  }
                }
              }
            }
            $results[] = array("name" => $keyword_type.".".Inflector::camelize(Inflector::slug($rt['ReadingTag']['title'])), "imports" => $prof_array);
          }
          Configure::write("debug", 0);
          $this->set(compact('results'));
        }
        else{
          $array_keyword_type_map = array(
            "all_tags" => "General Keywords",
            "personal_tags" => "Personal Keywords",
            "outside_tags" => "Outside Keywords",
            "extra_info_tags" => "Metadata"
          );
          $tag_section_title = $array_keyword_type_map["$keyword_type"];
          $this->Document = new Document();
          $document = $this->Document->findById($doc_id);
          $results = array(
              "controller" => "pages", 
              "action" => "flare_visualize",
              $query,
              $doc_id,
              $keyword_type
          );
          $this->set(compact("results", "document", "tag_section_title"));
        }
      }
      else{
        
      }
    }
    else{
      $this->Document = new Document();
      $documents = $this->Document->find("all", array("recursive" => -1));
      $this->set(compact("documents"));
    }
	}
	
	private function addToReadingTagUserByTag($tag = null){
    if(!empty($tag)){
      $data = $this->ReadingTag->find("first", array("conditions" => array("ReadingTag.title" => $tag)));
      $keyword_type = "extra_info_tags";
      $scl = new SphinxClient();
	    $query_results = $scl->Query($tag, $keyword_type);
     
      if(!empty($query_results['matches'])){
        foreach($query_results['matches'] as $a_tag_id_as_key => $val){
          $tagsurvey = $this->TagSurvey->findById($a_tag_id_as_key);
          $data['User']['id'] = $tagsurvey['Assignment']['user_id'];
          $this->ReadingTagUser->create();
          $rt_id = $data['ReadingTag']['id'];
          $u_id = $data['User']['id'];
          $this->ReadingTagUser->set(array("user_id" => $u_id, "reading_tag_id" => $rt_id, "tag_section" => $keyword_type));
          $this->ReadingTagUser->save();
        }
      }
	  }
	}
	
	public function co_visualize($query = null, $doc_id = null)
	{
	  if(!empty($query)){
	    if($this->RequestHandler->isAjax()){
	      $this->ReadingTagUser = new ReadingTagUser();
        $string = "SELECT user_id, users.username, GROUP_CONCAT( DISTINCT (reading_tag_id)
        ORDER BY reading_tag_id ) AS tag_list
        FROM reading_tag_users
        LEFT JOIN reading_tags ON reading_tags.id = reading_tag_users.reading_tag_id
        LEFT JOIN users ON users.id = user_id
        WHERE reading_tags.document_id =1
        AND users.email NOT 
        IN ('cbkm.wong@gmail.com',  'danieladhunter@gmail.com',  'pat_nean@hotmail.com',  '')
        GROUP BY user_id";
        $user_id_to_tag_id = $this->ReadingTagUser->query($string);
        $i = 0;
	      foreach($user_id_to_tag_id as $item){
	        //debug($item);
	        $nodes[$item['reading_tag_users']['user_id']] = array("name" => Inflector::camelize(Inflector::slug($item['users']['username'])), "group" => 1);
	        
	        //$nodes[] = array("name" => Inflector::camelize(Inflector::slug($item['users']['username'])), "group" => 1);
	        //$links[] = array("source" => $i, "target" => 0, "value" => 1);//count(explode(",", $item[0]['user_list'])));
	        //$links[] = array("source" => $i, "target" => $i-1, "value" => 1);
	        $links[] = array("source" => $i, "target" => 0, "value" => 1);
	        $i++;
	      }
	      $array_keys = array_keys($nodes);
        foreach($array_keys as $key){
          $tmp_node_list[] = $nodes[$key];
        }
	      $results = array("nodes" => $tmp_node_list, "links" => $links);
	      Configure::write("debug", 0);
	      $this->set(compact("results"));
	    }
	    else{
	      // test
	              // 
	             // $this->ReadingTag = new ReadingTag();
	             //         $tags = $this->ReadingTag->find("all", array(
	             //           'conditions' => array(
	             //             'ReadingTag.document_id' => $doc_id
	             //           )
	             //         ));
	             //         $i = 1;
        $this->ReadingTagUser = new ReadingTagUser();
        $string = "SELECT user_id, GROUP_CONCAT( DISTINCT (reading_tag_id)
        ORDER BY reading_tag_id ) AS tag_list
        FROM reading_tag_users
        LEFT JOIN reading_tags ON reading_tags.id = reading_tag_users.reading_tag_id
        LEFT JOIN users ON users.id = user_id
        WHERE reading_tags.document_id =1
        AND users.email NOT 
        IN ('cbkm.wong@gmail.com',  'danieladhunter@gmail.com',  'pat_nean@hotmail.com',  '')
        GROUP BY user_id";
        $user_id_to_tag_id = $this->ReadingTagUser->query($string);
        foreach($user_id_to_tag_id as $item){
          //debug($item);
          $user_list[] = trim($item['reading_tag_users']['user_id']);
        }
        //debug($user_list);
        $this->User = new User();
        $this->User->unbindModel(array('hasMany' => array('ReadingTagUser', 'Assignment')));
        $users = $this->User->find("all", array('conditions' => array('User.id' => $user_list)));
        debug($users);
        //debug($user_id_to_tag_id);
        // foreach($tags as $tag){
        //           
        //           //$current = $this->ReadingTagUser->find("all", array("conditions" => array("ReadingTagUser.reading_tag_id" => $tag['ReadingTag']['id'])));
        //           
        //           $current = $this->ReadingTagUser->query();
        //           debug($current);
        //           $nodes[] = array("name" => Inflector::camelize(Inflector::slug($tag['ReadingTag']['title'])), "group" => 1);
        //           $links[] = array("source" => $i, "target" => $i-1, "value" => 1);
        //         }
  	    // test
	    }
	  }
	  else{
	    $this->Document = new Document();
	    $documents = $this->Document->find("all");
	    $this->set(compact("documents"));
	  }
	}
}
