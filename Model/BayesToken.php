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
App::uses('BayesTokenCounter', 'NaiveBayesClassifier.Model');
App::uses('Inflector', 'Utility');
App::uses('Hash', 'Utility');

/**
 * @property BayesTokenCounter $BayesTokenCounter
 */
class BayesToken extends NaiveBayesClassifierAppModel
{
	public $hasMany = array
		(
			'BayesTokenCounter' => array('className' => 'NaiveBayesClassifier.BayesTokenCounter'),
		);

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

