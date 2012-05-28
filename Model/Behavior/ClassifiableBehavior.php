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

App::uses('ModelBehavior', 'Model');
App::uses('BayesToken', 'NaiveBayesClassifier.Model');

/**
 * Automatic classification of a document.
 *
 * Can classify a document on save and on validate. Can store the class in DB,
 * or prevent saving the row if classified in an undesirable class.
 *
 * Use:
 *
 * 	public $actsAs = array
 * 		(
 * 			'NaiveBayesClassifier.Classifiable' => array
 * 			(
 * 				'on' => 'save',
 * 				'classify' => array('name', 'email', 'comment'),
 * 				'destination' => 'type',
 * 				'valid_class' => 'ham', // only for validation
 * 				'map' => array // only for saving
 * 				(
 * 					'spam' => -1,
 * 					'ham' => 1,
 * 				),
 * 				'options' => array // @see BayesToken::classify()
 * 				(
 * 					'laplace_smoothing' => 2,
 * 					'threshold' => 1.5,
 * 				),
 * 			)
 * 		);
 *
 *  on: when to classify data, possible values: 'save', 'validate', 'off'
 *  classify: model field or fields to use for classification
 *  destination: model field to use for storing the class
 *  valid_class: used only for validation, can be an array or a single string value
 *  map (optional): what to store in database for every class detected
 *  options (optional): classification options, @see BayesToken::classify()
 *
 * @property BayesToken $BayesToken
 */
class ClassifiableBehavior extends ModelBehavior
{
	public $settings = array();

	public function __construct()
	{
		parent::__construct();

		$this->BayesToken = ClassRegistry::init('NaiveBayesClassifier.BayesToken');
	}

	public function setup(Model $Model, $settings)
	{
		$this->settings[$Model->alias] = $settings;
	}

	public function cleanup(Model $Model)
	{
		parent::cleanup($Model);
	}

	public function beforeValidate(Model $Model)
	{
		if (empty($this->settings[$Model->alias]))
		{
			return true;
		}

		$settings = $this->settings[$Model->alias];

		if (!isset($settings['on']) || $settings['on'] != 'validate')
		{
			return true;
		}

		$class = $this->_classify($Model, $settings);

		if (!is_array($settings['valid_class']))
		{
			$settings['valid_class'] = (array)$settings['valid_class'];
		}

		if (!in_array($class, $settings['valid_class']))
		{
			$Model->invalidate($settings['destination'], 'classification');
			return false;
		}

		return true;
	}

/**
 * Classify field(s) before saving the row.
 *
 * @param Model $model Model using this behavior
 * @return mixed False if the operation should abort. Any other result will continue.
 */
	public function beforeSave(Model $Model)
	{
		if (empty($this->settings[$Model->alias]))
		{
			return true;
		}

		$settings = $this->settings[$Model->alias];
		$destination = $settings['destination'];

		if (!$Model->hasField($destination))
		{
			throw new CakeException(__('Missing classification field "%s"', $destination));
		}

		if (!isset($settings['on']) || $settings['on'] != 'save')
		{
			return true;
		}

		$class = $this->_classify($Model, $settings);

		$Model->data[$Model->alias][$destination] = $class;
		return true;
	}


	/**
	 * This method is here only to expose BayesToken::train() to the model.
	 *
	 * @see BayesToken::train()
	 */
	public function train(Model $Model, $document, $class_label, $options = array())
	{
		return $this->BayesToken->train($document, $class_label, $options);
	}


	/**
	 * This method is here only to expose BayesToken::untrain() to the model.
	 *
	 * @see BayesToken::untrain()
	 */
	public function untrain(Model $Model, $document, $class_label, $options = array())
	{
		return $this->BayesToken->untrain($document, $class_label, $options);
	}


	/**
	 * This method is here only to expose BayesToken::classify() to the model.
	 *
	 * @see BayesToken::classify()
	 */
	public function classify(Model $Model, $document, array $options = array())
	{
		return $this->BayesToken->classify($document, $options);
	}


	private function _classify(Model $Model, $settings)
	{
		$fields = $settings['classify'];

		$document = '';

		foreach ($fields as $field)
		{
			if ($Model->hasField($field) && isset($Model->data[$Model->alias][$field]))
			{
				$document .= ' '.$Model->data[$Model->alias][$field];
			}
		}

		$options = array();

		if (isset($settings['options']))
		{
			$options = $settings['options'];
		}

		$class = $this->BayesToken->classify($document, $options);

		if ($class === false)
		{
			$class = null;
		}

		if (!empty($class) &&
			isset($settings['map']) &&
			isset($settings['map'][$class])
			)
		{
			$class = $settings['map'][$class];
		}

		return $class;
	}
}
