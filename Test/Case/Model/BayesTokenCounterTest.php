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

App::uses('BayesTokenCounter', 'NaiveBayesClassifier.Model');
App::uses('NaiveBayesClassifier', 'NaiveBayesClassifier.Lib');

/**
 * BayesToken Test Case
 *
 */
class BayesTokenCounterTest extends CakeTestCase
{
	/**
	 *
	 * @var BayesTokenCounter
	 */
	public $BayesTokenCounter = null;

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
		$this->BayesTokenCounter = ClassRegistry::init('NaiveBayesClassifier.BayesTokenCounter');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BayesTokenCounter);

		NaiveBayesClassifier::reset();
		parent::tearDown();
	}

	/**
	 * @todo: move to BayesTokenCounterTest
	 */
	public function testGetTokenCountSingle()
	{
		$expected = array('BayesTokenCounter' => array('count_sum' => 114));
		$result = $this->BayesTokenCounter->getCount();
		$this->assertEqual($result, $expected);

		$expected = array('BayesTokenCounter' => array('count_sum' => 58));
		$result = $this->BayesTokenCounter->getCount(array('class' => 1));
		$this->assertEqual($result, $expected);

		$expected = array('BayesTokenCounter' => array('count_sum' => 13));
		$result = $this->BayesTokenCounter->getCount(array('token' => 21));
		$this->assertEqual($result, $expected);

		$expected = array('BayesTokenCounter' => array('count_sum' => 5));
		$result = $this->BayesTokenCounter->getCount(array('class' => 1, 'token' => 21));
		$this->assertEqual($result, $expected);
	}


	/**
	 * @todo: move to BayesTokenCounterTest
	 */
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

		$result = $this->BayesTokenCounter->getCount(array('class' => 1, 'multiple' => true));
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

		$result = $this->BayesTokenCounter->getCount(array('token' => 21, 'multiple' => true));
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

		$result = $this->BayesTokenCounter->getCount(array('multiple' => 'class'));
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

		$result = $this->BayesTokenCounter->getCount(array('token' => 21, 'multiple' => 'class'));
		$this->assertEqual($result, $expected);
	}
}
