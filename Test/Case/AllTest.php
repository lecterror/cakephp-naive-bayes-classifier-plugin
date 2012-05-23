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

class AllNaiveBayesClassifierTests extends PHPUnit_Framework_TestSuite
{
	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite('All NaiveBayesClassifier tests');

		$suite->addTestFile(dirname(__FILE__).DS.'Model'.DS.'BayesClassTest.php');
		$suite->addTestFile(dirname(__FILE__).DS.'Model'.DS.'BayesTokenTest.php');

		$suite->addTestFile(dirname(__FILE__).DS.'Controller'.DS.'Component'.DS.'ClassifierComponentTest.php');

		$suite->addTestFile(dirname(__FILE__).DS.'Model'.DS.'Behavior'.DS.'ClassifiableBehaviorTest.php');

		return $suite;
	}
}
