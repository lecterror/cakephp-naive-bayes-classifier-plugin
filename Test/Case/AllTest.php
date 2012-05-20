<?php

class AllNaiveBayesClassifierTests extends PHPUnit_Framework_TestSuite
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('All NaiveBayesClassifier tests');

		$suite->addTestFile(dirname(__FILE__).DS.'Model'.DS.'BayesClassTest.php');
		$suite->addTestFile(dirname(__FILE__).DS.'Model'.DS.'BayesTokenTest.php');

		$suite->addTestFile(dirname(__FILE__).DS.'Controller'.DS.'Component'.DS.'ClassifierComponentTest.php');

		return $suite;
	}
}
