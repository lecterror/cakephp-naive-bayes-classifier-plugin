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

/**
 * BayesClassFixture
 *
 */
class BayesClassFixture extends CakeTestFixture
{

/**
 * Fields
 *
 * @var array
 */
	public $fields = array
		(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'label' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'vector_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
			'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
		);

/**
 * Records
 *
 * @var array
 */
	public $records = array
		(
			array
			(
				'id' => 1,
				'label' => 'spam',
				'vector_count' => 10
			),
			array
			(
				'id' => 2,
				'label' => 'ham',
				'vector_count' => 10
			),
		);
}
