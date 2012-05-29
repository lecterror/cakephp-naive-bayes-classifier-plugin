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
App::uses('BayesClass', 'NaiveBayesClassifier.Model');
App::uses('BayesToken', 'NaiveBayesClassifier.Model');

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
				'className' => 'NaiveBayesClassifier.BayesClass',
				'foreignKey' => 'bayes_class_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'BayesToken' => array
			(
				'className' => 'NaiveBayesClassifier.BayesToken',
				'foreignKey' => 'bayes_token_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);


	/**
	 * Returns the number of tokens in training vectors.
	 *
	 * Can be used to retreive counters for all tokens, for a single class,
	 * for a single token, or for a single token *and* class. Also allows
	 * fetching multiple sums at the same time, see $options.
	 *
	 * $options params accepts the following:
	 *
	 *  - class: Class ID for query filtering
	 *  - token: Token ID for query filtering
	 *  - multiple: If boolean true, will do a group by for both class and token,
	 *    also accepts 'class' and 'token' to group by those only
	 *
	 * @param type $options Options to filter counter data
	 * @return array Counter results
	 */
	public function getCount($options = array())
	{
		$defaultOptions = array('class' => null, 'token' => null, 'multiple' => false);
		$options = array_merge($defaultOptions, $options);

		$this->virtualFields['count_sum'] = sprintf('SUM(%s.count)', $this->alias);
		$fields = array('BayesTokenCounter.count_sum');
		$method = 'first';
		$conditions = array();
		$group = array();

		if (!empty($options['class']))
		{
			$conditions += array('BayesTokenCounter.bayes_class_id' => $options['class']);
		}

		if (!empty($options['token']))
		{
			$conditions += array('BayesTokenCounter.bayes_token_id' => $options['token']);
		}

		if (!empty($options['multiple']))
		{
			if ($options['multiple'] === true)
			{
				$group = array('BayesTokenCounter.bayes_class_id', 'BayesTokenCounter.bayes_token_id');
				$fields = array_merge($fields, $group);
			}
			else
			{
				if ($options['multiple'] == 'class')
				{
					$group[] = 'BayesTokenCounter.bayes_class_id';
					$fields[] = 'BayesTokenCounter.bayes_class_id';
				}
				else if ($options['multiple'] == 'token')
				{
					$group[] = 'BayesTokenCounter.bayes_token_id';
					$fields[] = 'BayesTokenCounter.bayes_token_id';
				}
			}

			$method = 'all';
		}

		$result = $this->find
			(
				$method,
				array
				(
					'conditions' => $conditions,
					'fields' => $fields,
					'group' => $group,
				)
			);

		unset($this->virtualFields['count_sum']);
		return $result;
	}
}
