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

App::uses('ClassifiableBehavior', 'NaiveBayesClassifier.Model/Behavior');

/**
 * Test class
 *
 * @package       Cake.Test.Case.Model
 */
class BehaviorTest extends CakeTestModel
{

/**
 * name property
 *
 * @var string 'BehaviorTest'
 */
	public $name = 'BehaviorTest';

/**
 * schema property
 *
 * @var array
 */
	protected $_schema = array
		(
			'id' => array('type' => 'integer', 'null' => '', 'default' => '1', 'length' => '8', 'key' => 'primary'),
			'name' => array('type' => 'string', 'null' => '', 'default' => '', 'length' => '50'),
			'email' => array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '50'),
			'comment' => array('type' => 'text', 'null' => '1', 'default' => '', 'length' => '255'),
			'class' => array('tyoe' => 'string', 'null' => '1', 'default' => '', 'length' => '50'),
			'type' => array('type' => 'integer', 'null' => '1', 'default' => null, 'length' => '8'),
			'created' => array('type' => 'date', 'null' => '1', 'default' => '', 'length' => null),
			'updated' => array('type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null)
		);

}

/**
 * ClassifiableBehavior Test Case
 *
 * @property BehaviorTest $BehaviorTest
 *
 */
class ClassifiableBehaviorTest extends CakeTestCase
{
	public $fixtures = array
		(
			'plugin.naive_bayes_classifier.bayes_class',
			'plugin.naive_bayes_classifier.bayes_token',
			'plugin.naive_bayes_classifier.bayes_token_counter',
			'plugin.naive_bayes_classifier.behavior_test',
		);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BehaviorTest = new BehaviorTest();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BehaviorTest);

		parent::tearDown();
	}

	public function testClassifyBeforeSave()
	{
		// test basic configuration
		$this->BehaviorTest->Behaviors->attach
			(
				'NaiveBayesClassifier.Classifiable',
				array
				(
					'on' => 'save',
					'classify' => array('name', 'email', 'comment'),
					'destination' => 'class',
				)
			);

		$this->BehaviorTest->create();
		$result = $this->BehaviorTest->save
			(
				array
				(
					'name' => 'john smith',
					'email' => 'admin@example.com',
					'comment' => 'Enlarge your replica rolex for her pleasure!',
				)
			);

		$saved = $this->BehaviorTest->find('first', array('conditions' => array('id' => 2)));
		$this->assertEqual($saved['BehaviorTest']['class'], 'spam');
		$this->BehaviorTest->Behaviors->detach('NaiveBayesClassifier.Classifiable');

		// test optional configuration: mapping
		$this->BehaviorTest->Behaviors->attach
			(
				'NaiveBayesClassifier.Classifiable',
				array
				(
					'on' => 'save',
					'classify' => array('name', 'email', 'comment'),
					'destination' => 'type',
					'map' => array
					(
						'spam' => -1,
						'ham' => 1,
					),
				)
			);
		$this->BehaviorTest->create();
		$result = $this->BehaviorTest->save
			(
				array
				(
					'name' => 'johnny b. goode',
					'email' => 'johnny@b.com',
					'comment' => 'I was thinking of going fishing with the rubberbandman, whaddya think?',
				)
			);

		$saved = $this->BehaviorTest->find('first', array('conditions' => array('id' => 3)));
		$this->assertEqual($saved['BehaviorTest']['type'], 1);
		$this->BehaviorTest->Behaviors->detach('NaiveBayesClassifier.Classifiable');

		// test optional configuration: classification options
		// in this case, absurd threshold...
		$this->BehaviorTest->Behaviors->attach
			(
				'NaiveBayesClassifier.Classifiable',
				array
				(
					'on' => 'save',
					'classify' => array('name', 'email', 'comment'),
					'destination' => 'class',
					'options' => array
					(
						'laplace_smoothing' => 2,
						'threshold' => 3.0,
					),
				)
			);

		$this->BehaviorTest->create();
		$result = $this->BehaviorTest->save
			(
				array
				(
					'name' => 'john smith',
					'email' => 'admin@example.com',
					'comment' =>	'The 50-50-90 rule: Anytime you have a 50-50 chance of getting something right, '.
									'there\'s a 90% probability you\'ll get it wrong. Also, cialis and purchase.',
				)
			);

		$saved = $this->BehaviorTest->find('first', array('conditions' => array('id' => 4)));
		$this->assertEqual($saved['BehaviorTest']['class'], null);
		$this->BehaviorTest->Behaviors->detach('NaiveBayesClassifier.Classifiable');
	}

	public function testClassifyValidate()
	{
		// test basic configuration
		$this->BehaviorTest->Behaviors->attach
			(
				'NaiveBayesClassifier.Classifiable',
				array
				(
					'on' => 'validate',
					'classify' => array('name', 'email', 'comment'),
					'destination' => 'class',
					'valid_class' => 'ham',
				)
			);

		$this->BehaviorTest->create();
		$result = $this->BehaviorTest->save
			(
				array
				(
					'name' => 'john smith',
					'email' => 'admin@example.com',
					'comment' => 'Enlarge your replica rolex for her pleasure!',
				)
			);

		$this->assertEqual($result, false);
		$expected = array('class' => array('classification'));
		$this->assertEqual($this->BehaviorTest->validationErrors, $expected);
		$this->BehaviorTest->Behaviors->detach('NaiveBayesClassifier.Classifiable');
	}
}
