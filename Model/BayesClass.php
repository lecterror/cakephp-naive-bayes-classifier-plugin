<?php
App::uses('NaiveBayesClassifierAppModel', 'NaiveBayesClassifier.Model');

/**
 * @property BayesTokenCounter $BayesTokenCounter
 */
class BayesClass extends NaiveBayesClassifierAppModel
{
	public $hasMany = array
		(
			'BayesTokenCounter'
		);
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'label' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'vector_count' => array(
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

	public function findOrCreate($class_label)
	{
		$class = $this->find
				(
					'first',
					array
					(
						'contain' => false,
						'conditions' => array
						(
							'BayesClass.label' => $class_label
						)
					)
				);

		if (empty($class))
		{
			$this->create();
			$class = $this->save(array('label' => $class_label));
		}

		return $class;
	}
}
