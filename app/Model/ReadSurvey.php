<?php
App::uses('AppModel', 'Model');
/**
 * ReadSurvey Model
 *
 * @property Assignment $Assignment
 */
class ReadSurvey extends AppModel {
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'assignment_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Assignment' => array(
			'className' => 'Assignment',
			'foreignKey' => 'assignment_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
