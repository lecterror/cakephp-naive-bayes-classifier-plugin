<?php
/**
 * BayesTokenCounterFixture
 *
 */
class BayesTokenCounterFixture extends CakeTestFixture
{

/**
 * Fields
 *
 * @var array
 */
	public $fields = array
		(
			'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
			'bayes_class_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
			'bayes_token_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
			'count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
			'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'ix_bayes_token_counters_class_id_token_id' => array('column' => array('bayes_class_id', 'bayes_token_id'), 'unique' => 1), 'ix_bayes_token_counters_token_id_class_id' => array('column' => array('bayes_token_id', 'bayes_class_id'), 'unique' => 1)),
			'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'MyISAM')
		);

/**
 * Records
 *
 * @var array
 */
	public $records = array
		(
			// spam
			array
			(
				'id' => 1,
				'bayes_class_id' => 1,
				'bayes_token_id' => 1,
				'count' => 8
			),
			array
			(
				'id' => 2,
				'bayes_class_id' => 1,
				'bayes_token_id' => 2,
				'count' => 5
			),
			array
			(
				'id' => 3,
				'bayes_class_id' => 1,
				'bayes_token_id' => 3,
				'count' => 7
			),
			array
			(
				'id' => 4,
				'bayes_class_id' => 1,
				'bayes_token_id' => 4,
				'count' => 2
			),
			array
			(
				'id' => 5,
				'bayes_class_id' => 1,
				'bayes_token_id' => 5,
				'count' => 4
			),
			array
			(
				'id' => 6,
				'bayes_class_id' => 1,
				'bayes_token_id' => 6,
				'count' => 7
			),
			array
			(
				'id' => 7,
				'bayes_class_id' => 1,
				'bayes_token_id' => 7,
				'count' => 7
			),
			array
			(
				'id' => 8,
				'bayes_class_id' => 1,
				'bayes_token_id' => 8,
				'count' => 4
			),
			array
			(
				'id' => 9,
				'bayes_class_id' => 1,
				'bayes_token_id' => 9,
				'count' => 2
			),
			array
			(
				'id' => 10,
				'bayes_class_id' => 1,
				'bayes_token_id' => 10,
				'count' => 7
			),
			// ham
			array
			(
				'id' => 11,
				'bayes_class_id' => 2,
				'bayes_token_id' => 11,
				'count' => 7
			),
			array
			(
				'id' => 12,
				'bayes_class_id' => 2,
				'bayes_token_id' => 12,
				'count' => 5
			),
			array
			(
				'id' => 13,
				'bayes_class_id' => 2,
				'bayes_token_id' => 13,
				'count' => 2
			),
			array
			(
				'id' => 14,
				'bayes_class_id' => 2,
				'bayes_token_id' => 14,
				'count' => 2
			),
			array
			(
				'id' => 15,
				'bayes_class_id' => 2,
				'bayes_token_id' => 15,
				'count' => 3
			),
			array
			(
				'id' => 16,
				'bayes_class_id' => 2,
				'bayes_token_id' => 16,
				'count' => 2
			),
			array
			(
				'id' => 17,
				'bayes_class_id' => 2,
				'bayes_token_id' => 17,
				'count' => 7
			),
			array
			(
				'id' => 18,
				'bayes_class_id' => 2,
				'bayes_token_id' => 18,
				'count' => 6
			),
			array
			(
				'id' => 19,
				'bayes_class_id' => 2,
				'bayes_token_id' => 19,
				'count' => 4
			),
			array
			(
				'id' => 20,
				'bayes_class_id' => 2,
				'bayes_token_id' => 20,
				'count' => 10
			),
			// both
			array
			(
				'id' => 21,
				'bayes_class_id' => 1,
				'bayes_token_id' => 21,
				'count' => 5
			),
			array
			(
				'id' => 22,
				'bayes_class_id' => 2,
				'bayes_token_id' => 21,
				'count' => 8
			),
		);
}
