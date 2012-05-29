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

App::uses('BayesToken', 'NaiveBayesClassifier.Model');
App::uses('NaiveBayesClassifier', 'NaiveBayesClassifier.Lib');

/**
 * BayesToken Test Case
 *
 */
class BayesTokenTest extends CakeTestCase
{
	/**
	 *
	 * @var BayesToken
	 */
	public $BayesToken = null;

	/**
	 *
	 * @var BayesClass
	 */
	public $BayesClass = null;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array
		(
			'plugin.naive_bayes_classifier.bayes_class',
			'plugin.naive_bayes_classifier.bayes_token',
			'plugin.naive_bayes_classifier.bayes_token_counter',
		);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BayesClass = ClassRegistry::init('NaiveBayesClassifier.BayesClass');
		$this->BayesToken = ClassRegistry::init('NaiveBayesClassifier.BayesToken');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BayesClass);
		unset($this->BayesToken);
		NaiveBayesClassifier::reset();

		parent::tearDown();
	}

/**
 * testGetTokenCounters method
 *
 * @return void
 */
	public function testGetTokenCounters()
	{
		$input_tokens = array('pharmacy');
		$expected = array
			(
				array
				(
					'BayesToken' => array
					(
						'id' => 10,
						'value' => 'pharmacy',
					),
					'BayesTokenCounter' => array
					(
						array
						(
							'id' => 10,
							'bayes_class_id' => 1,
							'bayes_token_id' => 10,
							'count' => 7
						)
					)
				)
			);

		$result = $this->BayesToken->getTokenCounters($input_tokens);
		$this->assertEqual($result, $expected);

		$input_tokens = array('pharmacy', 'rubberbandman');
		$expected = array
			(
				array
				(
					'BayesToken' => array
					(
						'id' => 10,
						'value' => 'pharmacy',
					),
					'BayesTokenCounter' => array
					(
						array
						(
							'id' => 10,
							'bayes_class_id' => 1,
							'bayes_token_id' => 10,
							'count' => 7
						)
					)
				),
				array
				(
					'BayesToken' => array
					(
						'id' => 20,
						'value' => 'rubberbandman',
					),
					'BayesTokenCounter' => array
					(
						array
						(
							'id' => 20,
							'bayes_class_id' => 2,
							'bayes_token_id' => 20,
							'count' => 10
						)
					)
				),
			);

		$result = $this->BayesToken->getTokenCounters($input_tokens);
		$this->assertEqual($result, $expected);

		$input_tokens = array('pharmacy', 'rubberbandman');
		$expected = array
			(
				array
				(
					'BayesToken' => array
					(
						'id' => 10,
						'value' => 'pharmacy',
					),
					'BayesTokenCounter' => array
					(
						array
						(
							'id' => 10,
							'bayes_class_id' => 1,
							'bayes_token_id' => 10,
							'count' => 7
						)
					)
				),
				array
				(
					'BayesToken' => array
					(
						'id' => 20,
						'value' => 'rubberbandman',
					),
					'BayesTokenCounter' => array
					(
					)
				),
			);

		// only class 1 counters
		$result = $this->BayesToken->getTokenCounters($input_tokens, array('class' => 1));
		$this->assertEqual($result, $expected);

		$input_tokens = array('pharmacy', 'rubberbandman');
		$expected = array
			(
				array
				(
					'BayesToken' => array
					(
						'id' => 10,
						'value' => 'pharmacy',
					),
					'BayesTokenCounter' => array
					(
						array
						(
							'id' => 10,
							'bayes_class_id' => 1,
							'bayes_token_id' => 10,
							'count' => 7
						)
					)
				),
			);

		// exclusively, tokens which have class 1 counters
		$result = $this->BayesToken->getTokenCounters($input_tokens, array('class' => 1, 'exclusive' => true));
		$this->assertEqual($result, $expected);

		$input_tokens = array('purchase');
		$expected = array
			(
				array
				(
					'BayesToken' => array
					(
						'id' => 21,
						'value' => 'purchase',
					),
					'BayesTokenCounter' => array
					(
						array
						(
							'id' => 22,
							'bayes_class_id' => 2,
							'bayes_token_id' => 21,
							'count' => 8
						),
					),
				),
			);

		$result = $this->BayesToken->getTokenCounters($input_tokens, array('class' => 2));
		$this->assertEqual($result, $expected);
	}
/**
 * testTokenize method
 *
 * @return void
 */
	public function testTokenize()
	{
		$input = 'Welcome to the test of a basic sentence!';
		$expected = array
			(
				'welcome' => 1,
				'the' => 1,
				'test' => 1,
				'basic' => 1,
				'sentence' => 1,
			);

		$result = $this->BayesToken->tokenize($input);
		$this->assertEqual($result, $expected);

		$input = 'This may indeed be the_second_test! Even#though-I%may&try=to pass 900 vi@gra*words/here,\\it shouldn\'t work <a href="www.viagra.com">GET VIAGRA!!!</a>';
		$expected = array
			(
				'this' => 1,
				'may' => 2,
				'indeed' => 1,
				'the' => 1,
				'second' => 1,
				'test' => 1,
				'even' => 1,
				'though' => 1,
				'try' => 1,
				'pass' => 1,
				'gra' => 1,
				'words' => 1,
				'here' => 1,
				'shouldn' => 1,
				'work' => 1,
				'href' => 1,
				'www' => 1,
				'viagra' => 2,
				'com' => 1,
				'get' => 1,
			);

		$result = $this->BayesToken->tokenize($input);
		$this->assertEqual($result, $expected);

		$input = 'te st to o sh or t wo rd s andalsopleasedotrytotestwordswhicharetoolongtobetrueanyway hel hell hello welcome';

		$result = $this->BayesToken->tokenize($input);
		$this->assertEqual($result, array('hel' => 1, 'hell' => 1, 'hello' => 1, 'welcome' => 1));

		$result = $this->BayesToken->tokenize($input, array('min_length' => 5, 'max_length' => 6));
		$this->assertEqual($result, array('hello' => 1));

		$input = 'čmar šeširdžija `arse žeđ ćumur 1°C';
		$expected = array
			(
				'cmar' => 1,
				'sesirdzija' => 1,
				'arse' => 1,
				'zed' => 1,
				'cumur' => 1,
			);

		$result = $this->BayesToken->tokenize($input);
		$this->assertEqual($result, $expected);
	}
}
