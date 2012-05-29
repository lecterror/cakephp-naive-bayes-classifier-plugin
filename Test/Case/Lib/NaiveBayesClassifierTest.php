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

App::uses('NaiveBayesClassifier', 'NaiveBayesClassifier.Lib');

/**
 * BayesToken Test Case
 *
 */
class NaiveBayesClassifierTest extends CakeTestCase
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
 * testTrain method
 *
 * @return void
 */
	public function testTrain()
	{
		$this->assertEqual(NaiveBayesClassifier::train('Enlarge your rolex!', 'spam'), true);

		$expected = array
			(
				'BayesClass' => array
				(
					'id' => 1,
					'label' => 'spam',
					'vector_count' => 11
				),
			);

		$result = $this->BayesClass->find('first', array('conditions' => array('label' => 'spam'), 'contain' => false));
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

	public function testUntrain()
	{
		$this->assertEqual(NaiveBayesClassifier::untrain('Enlarge your rolex!', 'spam'), true);

		$expected = array
			(
				'BayesClass' => array
				(
					'id' => 1,
					'label' => 'spam',
					'vector_count' => 9
				),
			);

		$result = $this->BayesClass->find('first', array('conditions' => array('label' => 'spam'), 'contain' => false));
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
							'count' => 6
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
							'count' => 3
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
		$result = NaiveBayesClassifier::classify('This is a perfectly normal sentence about Steam games');
		$this->assertEqual($result, 'ham');

		$result = NaiveBayesClassifier::classify('Buy cheap replica watches for shits and giggles!');
		$this->assertEqual($result, 'spam');

		$result = NaiveBayesClassifier::classify('ambiguous sentence of replica code');
		$this->assertEqual($result, false);

		$result = NaiveBayesClassifier::classify
			(
				'a bit less ambiguous sentence of cheap replica code, also, yello',
				array
				(
					'threshold' => 2,
				)
			);
		$this->assertEqual($result, false);
	}
}
