<?php

App::uses('Component', 'Controller');
App::uses('Hash', 'Utility');
App::uses('BayesToken', 'NaiveBayesClassifier.Model');
App::uses('BayesClass', 'NaiveBayesClassifier.Model');

class ClassifierComponent extends Component
{
	/**
	 *
	 * @var BayesClass
	 */
	public $BayesClass = null;

	/**
	 *
	 * @var BayesToken
	 */
	public $BayesToken = null;


	public function __construct(ComponentCollection $collection, $settings = array())
	{
		parent::__construct($collection, $settings);

		$this->Controller = $collection->getController();

		$this->BayesClass = ClassRegistry::init('NaiveBayesClassifier.BayesClass');
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
	 * When $document is successfully classified, the return value is an array of class id and label. When
	 * classification does not succeed (class difference < threshold), the return value is boolean false.
	 *
	 * @param string $document Document to be classified
	 * @param array $options Options affecting the way $text is classified (laplace_smoothing, threshold)
	 * @return mixed Boolean false when $text could not be classified, otherwise, an array of class id and label
	 */
	public function classify($document, array $options = array())
	{
		$defaultOptions = array
			(
				'laplace_smoothing'	=> 1,
				'threshold'			=> 1.5,
				'debug'				=> false
			);
		
		$result = array();
		$options = array_merge($defaultOptions, $options);
		$K = (float)$options['laplace_smoothing'];
		$debug = (bool)$options['debug'];

		$new_tokens = $this->BayesToken->tokenize($document);
		$total_token_count = 0;

		$classes = $this->BayesClass->find('all', array('contain' => false));
		$total_documents = array_sum(Hash::extract($classes, '{n}.BayesClass.vector_count'));
		$total_classes = count($classes);
		$total_token_count = $this->BayesToken->getTokenCount();
		$total_token_count = intval($total_token_count['BayesTokenCounter']['count_sum']);


		foreach ($classes as $class)
		{
			$class_name = $class['BayesClass']['label'];
			$class_count = $class['BayesClass']['vector_count'];

			// $P_class = class probability
			$P_class = ($class_count + $K) / ($total_documents + $K * $total_classes);
			// $P_final = final probability
			$P_final = $P_class;

			if ($debug) { pr(sprintf('P(%s) = %f', $class_name, $P_class)); }

			foreach ($new_tokens as $new_token => $new_count)
			{
				$token_count = 0;
				$total_class_token_count = 0;

				$token = $this->BayesToken->getTokenCounters
					(
						$new_token,
						array
						(
							'class' => $class['BayesClass']['id'],
							'exclusive' => true,
						)
					);
				
				if (!empty($token))
				{
					$token_count = $token[0]['BayesTokenCounter'][0]['count'];
				}
				
				$total_class_token_count = $this->BayesToken->getTokenCounters
					(
						array(),
						array
						(
							'class' => $class['BayesClass']['id'],
							'exclusive' => true,
						)
					);

				if (!empty($total_class_token_count))
				{
					$total_class_token_count = count($total_class_token_count);
				}

				// $P_token = individual token probability
				$P_token = ($token_count + $K) / ($total_class_token_count + $K * $total_token_count);

				if ($debug) { pr(sprintf('P(%s|%s) = %f', $new_token, $class_name, $P_token)); }

				$P_final *= $P_token;
			}

			$result[$class_name] = $P_final;
		}

		$normalizer = array_sum($result);

		foreach ($result as $class => $probability)
		{
			$result[$class] = $probability / $normalizer;
		}

		if ($debug) { debug($result); }

		arsort($result, SORT_DESC);
		$labels = array_keys($result);
		$difference = $result[$labels[0]] / $result[$labels[1]];

		if ($difference < $options['threshold'])
		{
			return false;
		}

		return $labels[0];
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
	 */
	public function train($document, $class_label, $options = array())
	{
		$new_tokens = $this->BayesToken->tokenize($document, $options);

		$class = $this->BayesClass->findOrCreate($class_label);

		// update the number of documents for this category
		$class['BayesClass']['vector_count'] += 1;
		$this->BayesClass->save($class);

		// find out which tokens do we already have
		$tokens = $this->BayesToken->getTokenCounters
				(
					array_keys($new_tokens),
					array
					(
						'class' => $class['BayesClass']['id']
					)
				);

		foreach ($new_tokens as $token => $count)
		{
			$token_path = sprintf('{n}.BayesToken[value=%s]', $token);

			// if token doesn't exist in database, add it
			if (!Hash::check($tokens, $token_path))
			{
				$tokens[] = array
					(
						'BayesToken' => array('value' => $token),
						'BayesTokenCounter' => array
						(
							array
							(
								'bayes_class_id' => $class['BayesClass']['id'],
								'count' => 1
							)
						)
					);

				continue;
			}

			// @TODO: optimise somehow..
			foreach ($tokens as &$data)
			{
				if ($data['BayesToken']['value'] == $token)
				{
					if (empty($data['BayesTokenCounter']))
					{
						$data['BayesTokenCounter'][0] = array
							(
								'bayes_class_id' => $class['BayesClass']['id'],
								'bayes_token_id' => $data['BayesToken']['id'],
								'count' => 0
							);
					}

					$data['BayesTokenCounter'][0]['count'] += 1; // @TODO: should $count be used instead?
					break;
				}
			}
		}

		return $this->BayesToken->saveMany($tokens, array('deep' => true));
	}
}
