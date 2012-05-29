<?php
/**
	CakePHP NaiveBayesClassifier Plugin

	Copyright (C) 2012-3827 dr. Hannibal Lecter / lecterror
	<http://lecterror.com/>

	Multi-licenced under:
		MPL <http://www.mozilla.org/MPL/MPL-1.1.html>
		LGPL <http://www.gnu.org/licenses/lgpl.html>
		GPL <http://www.gnu.org/licenses/gpl.html>
*/

App::uses('NaiveBayesClassifierAppModel', 'NaiveBayesClassifier.Model');
App::uses('BayesTokenCounter', 'NaiveBayesClassifier.Model');

/**
 * @property BayesTokenCounter $BayesTokenCounter
 */
class BayesClass extends NaiveBayesClassifierAppModel
{
	public $hasMany = array
		(
			'BayesTokenCounter' => array('className' => 'NaiveBayesClassifier.BayesTokenCounter'),
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
