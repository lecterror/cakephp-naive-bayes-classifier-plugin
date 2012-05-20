<?php

App::uses('BayesClass', 'NaiveBayesClassifier.Model');

/**
 * BayesClass Test Case
 *
 */
class BayesClassTest extends CakeTestCase
{
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
			'plugin.naive_bayes_classifier.bayes_token_counter'
		);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BayesClass = ClassRegistry::init('NaiveBayesClassifier.BayesClass');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BayesClass);

		parent::tearDown();
	}

/**
 * testFindOrCreate method
 *
 * @return void
 */
	public function testFindOrCreate()
	{
		$expected = array
			(
				'BayesClass' => array
				(
					'id' => 1,
					'label' => 'spam',
					'vector_count' => 10,
				),
			);

		$result = $this->BayesClass->findOrCreate('spam');
		$this->assertEqual($result, $expected);

		$expected = array
			(
				'BayesClass' => array
				(
					'id' => 3,
					'label' => 'cabbage',
					'vector_count' => 0,
				),
			);

		$result = $this->BayesClass->findOrCreate('cabbage');
		$this->assertEqual($result, $expected);
	}
}
