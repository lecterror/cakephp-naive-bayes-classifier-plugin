<?php

App::uses('BayesToken', 'NaiveBayesClassifier.Model');

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
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array
		(
			'plugin.naive_bayes_classifier.bayes_class',
			'plugin.naive_bayes_classifier.bayes_token',
			'plugin.naive_bayes_classifier.bayes_token_counter'
		);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BayesToken = ClassRegistry::init('NaiveBayesClassifier.BayesToken');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BayesToken);

		parent::tearDown();
	}

	public function testGetTokenCountSingle()
	{
		$expected = array('BayesTokenCounter' => array('count_sum' => 114));
		$result = $this->BayesToken->getTokenCount();
		$this->assertEqual($result, $expected);

		$expected = array('BayesTokenCounter' => array('count_sum' => 58));
		$result = $this->BayesToken->getTokenCount(array('class' => 1));
		$this->assertEqual($result, $expected);

		$expected = array('BayesTokenCounter' => array('count_sum' => 13));
		$result = $this->BayesToken->getTokenCount(array('token' => 21));
		$this->assertEqual($result, $expected);

		$expected = array('BayesTokenCounter' => array('count_sum' => 5));
		$result = $this->BayesToken->getTokenCount(array('class' => 1, 'token' => 21));
		$this->assertEqual($result, $expected);
	}


	public function testGetTokenCountMultiple()
	{
		$expected = array
			(
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'bayes_token_id' => '1',
						'count_sum' => '8'
					)
				),
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'bayes_token_id' => '2',
						'count_sum' => '5'
					)
				),
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'bayes_token_id' => '3',
						'count_sum' => '7'
					)
				),
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'bayes_token_id' => '4',
						'count_sum' => '2'
					)
				),
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'bayes_token_id' => '5',
						'count_sum' => '4'
					)
				),
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'bayes_token_id' => '6',
						'count_sum' => '7'
					)
				),
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'bayes_token_id' => '7',
						'count_sum' => '7'
					)
				),
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'bayes_token_id' => '8',
						'count_sum' => '4'
					)
				),
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'bayes_token_id' => '9',
						'count_sum' => '2'
					)
				),
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'bayes_token_id' => '10',
						'count_sum' => '7'
					)
				),
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'bayes_token_id' => '21',
						'count_sum' => '5'
					)
				)
			);

		$result = $this->BayesToken->getTokenCount(array('class' => 1, 'multiple' => true));
		$this->assertEqual($result, $expected);

		$expected = array
			(
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'bayes_token_id' => '21',
						'count_sum' => '5'
					),
				),
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '2',
						'bayes_token_id' => '21',
						'count_sum' => '8'
					),
				),
			);

		$result = $this->BayesToken->getTokenCount(array('token' => 21, 'multiple' => true));
		$this->assertEqual($result, $expected);

		$expected = array
			(
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'count_sum' => '58'
					),
				),
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '2',
						'count_sum' => '56'
					),
				),
			);

		$result = $this->BayesToken->getTokenCount(array('multiple' => 'class'));
		$this->assertEqual($result, $expected);

		$expected = array
			(
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '1',
						'count_sum' => '5'
					),
				),
				array
				(
					'BayesTokenCounter' => array
					(
						'bayes_class_id' => '2',
						'count_sum' => '8'
					),
				),
			);

		$result = $this->BayesToken->getTokenCount(array('token' => 21, 'multiple' => 'class'));
		$this->assertEqual($result, $expected);
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

/**
 * testTrain method
 *
 * @return void
 */
	public function testTrain()
	{
		$this->assertEqual($this->BayesToken->train('Enlarge your rolex!', 'spam'), true);

		$expected = array
			(
				'BayesClass' => array
				(
					'id' => 1,
					'label' => 'spam',
					'vector_count' => 11
				),
			);

		$result = $this->BayesToken->BayesClass->find('first', array('conditions' => array('label' => 'spam'), 'contain' => false));
		$this->assertEqual($result, $expected);

		$expected = array
			(
				array
				(
					'BayesToken' => array
					(
						'id' => 6,
						'value' => 'enlarge'
					),
					'BayesTokenCounter' => array
					(
						array
						(
							'id' => 6,
							'bayes_class_id' => 1,
							'bayes_token_id' => 6,
							'count' => 8
						),
					),
				),
				array
				(
					'BayesToken' => array
					(
						'id' => 5,
						'value' => 'rolex'
					),
					'BayesTokenCounter' => array
					(
						array
						(
							'id' => 5,
							'bayes_class_id' => 1,
							'bayes_token_id' => 5,
							'count' => 5
						),
					),
				),
				array
				(
					'BayesToken' => array
					(
						'id' => 22,
						'value' => 'your'
					),
					'BayesTokenCounter' => array
					(
						array
						(
							'id' => 23,
							'bayes_class_id' => 1,
							'bayes_token_id' => 22,
							'count' => 1
						),
					),
				),
			);

		$result = $this->BayesToken->find
			(
				'all',
				array
				(
					'contain' => array('BayesTokenCounter'),
					'conditions' => array
					(
						'value' => array('enlarge', 'your', 'rolex')
					),
				)
			);

		$this->assertEqual($result, $expected);
	}


/**
 * testClassify method
 *
 * @return void
 */
	public function testClassify()
	{
		$result = $this->BayesToken->classify('This is a perfectly normal sentence about Steam games');
		$this->assertEqual($result, 'ham');

		$result = $this->BayesToken->classify('Buy cheap replica watches for shits and giggles!');
		$this->assertEqual($result, 'spam');

		$result = $this->BayesToken->classify('ambiguous sentence of replica code');
		$this->assertEqual($result, false);
	}
}
