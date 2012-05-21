<?php
App::uses('NaiveBayesClassifierAppModel', 'NaiveBayesClassifier.Model');
App::uses('BayesClass', 'NaiveBayesClassifier.Model');
App::uses('Inflector', 'Utility');
App::uses('Hash', 'Utility');

/**
 * @property BayesTokenCounter $BayesTokenCounter
 */
class BayesToken extends NaiveBayesClassifierAppModel
{
	public $hasMany = array
		(
			'BayesTokenCounter',
		);


	public function __construct($id = false, $table = null, $ds = null)
	{
		parent::__construct($id, $table, $ds);

		$this->BayesClass = ClassRegistry::init('NaiveBayesClassifier.BayesClass');
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
		$new_tokens = $this->tokenize($document, $options);

		$class = $this->BayesClass->findOrCreate($class_label);

		// update the number of documents for this category
		$class['BayesClass']['vector_count'] += 1;
		$this->BayesClass->save($class);

		// find out which tokens do we already have
		$tokens = $this->getTokenCounters
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

		return $this->saveMany($tokens, array('deep' => true));
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

		$new_tokens = $this->tokenize($document);
		$total_token_count = 0;

		$classes = $this->BayesClass->find('all', array('contain' => false));
		$total_documents = array_sum(Hash::extract($classes, '{n}.BayesClass.vector_count'));
		$total_classes = count($classes);
		$total_token_count = $this->getTokenCount();
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

			$total_class_token_count = $this->getTokenCounters
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

			foreach ($new_tokens as $new_token => $new_count)
			{
				$token_count = 0;
				$total_class_token_count = 0;

				$token = $this->getTokenCounters
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
	public function getTokenCount($options = array())
	{
		$defaultOptions = array('class' => null, 'token' => null, 'multiple' => false);
		$options = array_merge($defaultOptions, $options);
		
		$this->BayesTokenCounter->virtualFields['count_sum'] = 'SUM(BayesTokenCounter.count)';
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
		
		$result = $this->BayesTokenCounter->find
			(
				$method,
				array
				(
					'conditions' => $conditions,
					'fields' => $fields,
					'group' => $group,
				)
			);

		unset($this->BayesTokenCounter->virtualFields['count_sum']);
		return $result;
	}

	/**
	 * Find tokens with one or more conditions:
	 * 
	 *  - token value(s)
	 *  - class + exclusive: only tokens with counters for specified class
	 *  - class - exclusive: all tokens, but counters only for specified class
	 * 
	 * $options param takes the following values:
	 * 
	 *  - class: Class ID to use for counter filtering
	 *  - exlusive: If true, only those tokens which have counters for specified class will be returned
	 *
	 * @param mixed $tokens A single token value, or an array of tokens
	 * @param array $options Filtering options
	 * @return array Tokens found for the specified search params
	 */
	public function getTokenCounters($tokens = array(), $options = array())
	{
		$defaultOptions = array('class' => null, 'exclusive' => false);
		$options = array_merge($defaultOptions, $options);
		
		$conditions = array();

		if (!empty($tokens))
		{
			$conditions = array('BayesToken.value' => $tokens);
		}

		$counters_conditions = array();

		if (!empty($options['class']))
		{
			$counters_conditions = array('BayesTokenCounter.bayes_class_id' => $options['class']);
		}

		$tokens = $this->find
			(
				'all',
				array
				(
					'conditions' => array
					(
						$conditions,
					),
					'contain' => array
					(
						'BayesTokenCounter' => array
						(
							'conditions' => array
							(
								$counters_conditions,
							),
						)
					),
				)
			);

		if (!$options['exclusive'])
		{
			return $tokens;
		}

		$output = array();
		$class_path = sprintf('BayesTokenCounter.{n}[bayes_class_id=%d]', $options['class']);

		foreach ($tokens as $key => $item)
		{
			if (Hash::check($item, $class_path))
			{
				$output[] = $item;
			}
		}

		return $output;
	}

	/**
	 * Tokenize a document.
	 * 
	 * $options param takes the following values:
	 * 
	 *  - min_length: minimum token length, default 3
	 *  - max_length: maximum token length, default 20
	 *
	 * @param type $document Document to tokenize
	 * @param type $options Options for tokenization
	 * @return array Token array
	 * @todo Add additional options, such as a custom regex, as well as entirely 3rd party tokenization
	 */
	public function tokenize($document, $options = array())
	{
		$defaultOptions = array('min_length' => 3, 'max_length' => 20);
		$options = array_merge($defaultOptions, $options);

		$tokens = $this->_tokenize($document);
		$output = array();

		foreach ($tokens as $token)
		{
			$token_length = strlen($token);

			if ($token_length < $options['min_length'] ||
				$token_length > $options['max_length'])
			{
				continue;
			}

			if (!isset($output[$token]))
			{
				$output[$token] = 0;
			}

			$output[$token]++;
		}

		return $output;
	}

	private function _tokenize($document)
	{
		$document = Inflector::slug($document, ' ');
		$document = strtolower($document);
		$tokens = preg_split('#((\W+?)|[_!0-9])#', $document, -1, PREG_SPLIT_NO_EMPTY);
		#$tokens = preg_split('#[\s,\.\/"\:;\|<>\-_\[\]{}\+=\)\(\*\&\^%]+#', $document, -1, PREG_SPLIT_NO_EMPTY);

		return $tokens;
	}
}

