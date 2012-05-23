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
