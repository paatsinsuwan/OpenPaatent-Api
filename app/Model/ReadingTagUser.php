<?php
/**
* 
*/
class ReadingTagUser extends AppModel
{
  public $belongsTo = array(
		'ReadingTag' => array(
			'className' => 'ReadingTag',
			'foreignKey' => 'reading_tag_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}

?>