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

class AllNaiveBayesClassifierTests extends CakeTestSuite
{
	public static function suite()
	{
		$suite = new CakeTestSuite('All NaiveBayesClassifier tests');

		$suite->addTestDirectoryRecursive(App::pluginPath('NaiveBayesClassifier').'Test'.DS.'Case');

		return $suite;
	}
}
