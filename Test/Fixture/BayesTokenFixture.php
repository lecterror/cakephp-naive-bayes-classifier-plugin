<?php
/**
 * BayesTokenFixture
 *
 */
class BayesTokenFixture extends CakeTestFixture
{

/**
 * Fields
 *
 * @var array
 */
	public $fields = array
		(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'value' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'ix_bayes_tokens_value' => array('column' => 'value', 'unique' => 1)),
			'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
		);

/**
 * Records
 *
 * @var array
 */
	public $records = array
		(
			// some classics...
			array
			(
				'id' => 1,
				'value' => 'viagra'
			),
			array
			(
				'id' => 2,
				'value' => 'cialis'
			),
			array
			(
				'id' => 3,
				'value' => 'replica'
			),
			array
			(
				'id' => 4,
				'value' => 'watches'
			),
			array
			(
				'id' => 5,
				'value' => 'rolex'
			),
			array
			(
				'id' => 6,
				'value' => 'enlarge'
			),
			array
			(
				'id' => 7,
				'value' => 'free'
			),
			array
			(
				'id' => 8,
				'value' => 'premium'
			),
			array
			(
				'id' => 9,
				'value' => 'cheap'
			),
			array
			(
				'id' => 10,
				'value' => 'pharmacy'
			),
			// some harmless tokens...
			array
			(
				'id' => 11,
				'value' => 'code'
			),
			array
			(
				'id' => 12,
				'value' => 'cakephp'
			),
			array
			(
				'id' => 13,
				'value' => 'decision'
			),
			array
			(
				'id' => 14,
				'value' => 'complex'
			),
			array
			(
				'id' => 15,
				'value' => 'system'
			),
			array
			(
				'id' => 16,
				'value' => 'fishing'
			),
			array
			(
				'id' => 17,
				'value' => 'steam'
			),
			array
			(
				'id' => 18,
				'value' => 'application'
			),
			array
			(
				'id' => 19,
				'value' => 'yello'
			),
			array
			(
				'id' => 20,
				'value' => 'rubberbandman'
			),
			// somewhat common for both classes
			array
			(
				'id' => 21,
				'value' => 'purchase'
			),
		);
}
