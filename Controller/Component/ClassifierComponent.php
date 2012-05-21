<?php

App::uses('Component', 'Controller');
App::uses('Hash', 'Utility');
App::uses('BayesToken', 'NaiveBayesClassifier.Model');

class ClassifierComponent extends Component
{
	/**
	 *
	 * @var BayesToken
	 */
	public $BayesToken = null;


	public function __construct(ComponentCollection $collection, $settings = array())
	{
		parent::__construct($collection, $settings);

		$this->Controller = $collection->getController();

		$this->BayesToken = ClassRegistry::init('NaiveBayesClassifier.BayesToken');
	}


	/**
	 * 
	 * Classifies given $document using naive Bayesian classification, with the options provided in $options.
	 * 
	 * Options array takes the following values:
	 * 
	 *  - laplace_smoothing: Laplace smoothing param, default k = 1
	 *  - threshold: Threshold difference necessary to ensure "reliable" classification, default 1.5
	 *  - debug: outputs internal vars using pr() and debug(), useful for debugging purposes
	 * 
	 * A threshold value of 1.5 means that the ratio between two highest classes must be at least 60% : 40%
	 * 
	 * When $document is successfully classified, the return value is the class label. When
	 * classification does not succeed (class difference < threshold), the return value is boolean false.
	 *
	 * @param string $document Document to be classified
	 * @param array $options Options affecting the way $text is classified (laplace_smoothing, threshold)
	 * @return mixed Boolean false when $document could not be classified, otherwise a class label
	 * @see BayesToken::classify()
	 */
	public function classify($document, array $options = array())
	{
		return $this->BayesToken->classify($document, $options);
	}
	
	/**
	 * Adds tokens from a document to the train set. Returns true on success.
	 * 
	 * $options param takes the following values:
	 * 
	 *  - min_length: minimum token length, default 3
	 *  - max_length: maximum token length, default 20
	 *
	 * @param string $document Document to train with
	 * @param string $class_label Class label to assign the document to
	 * @param array $options Tokenizer options array
	 * @see BayesToken::train()
	 */
	public function train($document, $class_label, $options = array())
	{
		return $this->BayesToken->train($document, $class_label, $options);
	}
}
