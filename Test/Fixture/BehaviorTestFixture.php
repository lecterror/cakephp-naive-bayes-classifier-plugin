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
 * BehaviorTestFixture
 *
 */
class BehaviorTestFixture extends CakeTestFixture
{

/**
 * Fields
 *
 * @var array
 */
	public $fields = array
		(
			'id' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => '8', 'key' => 'primary'),
			'name' => array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '50'),
			'email' => array('type' => 'string', 'null' => '1', 'default' => '', 'length' => '50'),
			'comment' => array('type' => 'text', 'null' => '1', 'default' => '', 'length' => '255'),
			'class' => array('tyoe' => 'string', 'null' => '1', 'default' => '', 'length' => '50'),
			'type' => array('type' => 'integer', 'null' => '1', 'default' => null, 'length' => '8'),
			'created' => array('type' => 'date', 'null' => '1', 'default' => '', 'length' => null),
			'updated' => array('type' => 'datetime', 'null' => '1', 'default' => '', 'length' => null),
			'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
		);

/**
 * records property
 *
 * @var array
 */
	public $records = array
		(
			array('id' => 1, 'name' => 'test', 'email' => 'test@example.com', 'comment' => ''),
		);
}