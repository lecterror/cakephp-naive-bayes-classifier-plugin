<?php

App::uses('NaiveBayesClassifierAppModel', 'NaiveBayesClassifier.Model');

/**
 * BayesTokenCounter Model
 *
 * @property BayesClass $BayesClass
 * @property BayesToken $BayesToken
 */
class BayesTokenCounter extends NaiveBayesClassifierAppModel
{
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'bayes_class_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'bayes_token_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'count' => array(
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
	public $belongsTo = array
		(
			'BayesClass' => array
			(
				'className' => 'BayesClass',
				'foreignKey' => 'bayes_class_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'BayesToken' => array
			(
				'className' => 'BayesToken',
				'foreignKey' => 'bayes_token_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
}
