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

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');

App::uses('BayesClass', 'NaiveBayesClassifier.Model');
App::uses('BayesToken', 'NaiveBayesClassifier.Model');
App::uses('BayesTokenCounter', 'NaiveBayesClassifier.Model');
App::uses('ClassifierComponent', 'NaiveBayesClassifier.Controller/Component');


class ClassifierComponentTestController extends Controller
{
	public $uses = null;
}

/**
 * ClassifierComponent Test Case
 *
 */
class ClassifierComponentTest extends CakeTestCase
{

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
	 *
	 * @var Controller
	 */
	public $Controller = null;

	/**
	 *
	 * @var ClassifierComponent
	 */
	public $Classifier = null;

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

	/**
	 *
	 * @var BayesTokenCounter
	 */
	public $BayesTokenCounter = null;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp()
	{
		parent::setUp();

		$request = new CakeRequest('/');
		$response = new CakeResponse();
		$this->Controller = new ClassifierComponentTestController($request, $response);
		$this->Controller->constructClasses();
		$this->Classifier = new ClassifierComponent($this->Controller->Components);

		$this->BayesClass = ClassRegistry::init('NaiveBayesClassifier.BayesClass');
		$this->BayesToken = ClassRegistry::init('NaiveBayesClassifier.BayesToken');
		$this->BayesTokenCounter = ClassRegistry::init('NaiveBayesClassifier.BayesTokenCounter');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown()
	{
		unset($this->Classifier);
		unset($this->Controller);
		unset($this->BayesClass);
		unset($this->BayesToken);
		unset($this->BayesTokenCounter);

		parent::tearDown();
	}

/**
 * testClassify method
 *
 * @return void
 */
	public function testClassify()
	{
		$result = $this->Classifier->classify('This is a perfectly normal sentence about Steam games');
		$this->assertEqual($result, 'ham');

		$result = $this->Classifier->classify('Buy cheap replica watches for shits and giggles!');
		$this->assertEqual($result, 'spam');

		$result = $this->Classifier->classify('replica code');
		$this->assertEqual($result, false);
	}
/**
 * testTrain method
 *
 * @return void
 */
	public function testTrain()
	{
		$this->assertEqual($this->Classifier->train('Enlarge your rolex!', 'spam'), true);

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
		$this->assertEqual($this->Classifier->untrain('Enlarge your rolex!', 'spam'), true);

		$expected = array
			(
				'BayesClass' => array
				(
					'id' => 1,
					'label' => 'spam',
					'vector_count' => 9
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
}
