<?php

App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');

//App::uses('BayesClass', 'NaiveBayesClassifier.Model');
//App::uses('BayesToken', 'NaiveBayesClassifier.Model');
//App::uses('BayesTokenCounter', 'NaiveBayesClassifier.Model');
App::uses('ClassifierComponent', 'NaiveBayesClassifier.Controller/Component');
//
//
//class ClassifierComponentTestController extends Controller
//{
//	public $uses = null;
//}

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
//	public $fixtures = array
//		(
//			'plugin.naive_bayes_classifier.bayes_class',
//			'plugin.naive_bayes_classifier.bayes_token',
//			'plugin.naive_bayes_classifier.bayes_token_counter'
//		);

//	/**
//	 *
//	 * @var Controller
//	 */
//	public $Controller = null;
//
//	/**
//	 *
//	 * @var ClassifierComponent
//	 */
//	public $Classifier = null;
//
//	/**
//	 *
//	 * @var BayesClass
//	 */
//	public $BayesClass = null;
//
//	/**
//	 *
//	 * @var BayesToken
//	 */
//	public $BayesToken = null;
//
//	/**
//	 *
//	 * @var BayesTokenCounter
//	 */
//	public $BayesTokenCounter = null;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp()
	{
		parent::setUp();

//		$request = new CakeRequest('/');
//		$response = new CakeResponse();
//		$this->Controller = new ClassifierComponentTestController($request, $response);
//		$this->Controller->constructClasses();
//		$this->Classifier = new ClassifierComponent($this->Controller->Components);
//
//		$this->BayesClass = ClassRegistry::init('NaiveBayesClassifier.BayesClass');
//		$this->BayesToken = ClassRegistry::init('NaiveBayesClassifier.BayesToken');
//		$this->BayesTokenCounter = ClassRegistry::init('NaiveBayesClassifier.BayesTokenCounter');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown()
	{
//		unset($this->Classifier);
//		unset($this->Controller);
//		unset($this->BayesClass);
//		unset($this->BayesToken);
//		unset($this->BayesTokenCounter);

		parent::tearDown();
	}

/**
 * testClassify method
 *
 * @return void
 */
	public function testClassify()
	{
		$this->skipIf(true, 'Skipping empty test');
	}
/**
 * testTrain method
 *
 * @return void
 */
	public function testTrain()
	{
		$this->skipIf(true, 'Skipping empty test');
	}
}
