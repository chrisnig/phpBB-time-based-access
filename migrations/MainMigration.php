<?php

namespace chrisnig\tba\migrations;

use phpbb\db\migration\migration;

class MainMigration extends migration
{
	static $catInfo = array(
		"ucp",
		0,
		"TBA_UCP_CAT"
	);

	static $moduleInfo = array(
		"ucp",
		"TBA_UCP_CAT",
		array(
			"module_basename" => '\chrisnig\tba\ucp\tba_main_module'
		)
	);

	static $tableNames = array(
		"user_access" => "tba_user_access",
		"guardians" => "tba_guardians"
	);

	public function update_data()
	{
		return array(
			array('module.add', self::$catInfo),
			array('module.add', self::$moduleInfo)
		);
	}

	public function revert_data()
	{
		return array(
			array('module.remove', self::$moduleInfo),
			array('module.remove', self::$catInfo)
		);
	}

	public function update_schema() {
		return array(
			'add_tables' => array(
				$this->table_prefix . self::$tableNames["user_access"] => array(
					'COLUMNS' => array(
						'user_id' => array('UINT', 0, 'UNSIGNED'),
						'mon_start' => array('VCHAR:5', ''), // it would be great if we could use TIME here, but phpBB schema
						'mon_end' => array('VCHAR:5', ''),   // tool does not support this type
						'tue_start' => array('VCHAR:5', ''),
						'tue_end' => array('VCHAR:5', ''),
						'wed_start' => array('VCHAR:5', ''),
						'wed_end' => array('VCHAR:5', ''),
						'thu_start' => array('VCHAR:5', ''),
						'thu_end' => array('VCHAR:5', ''),
						'fri_start' => array('VCHAR:5', ''),
						'fri_end' => array('VCHAR:5', ''),
						'sat_start' => array('VCHAR:5', ''),
						'sat_end' => array('VCHAR:5', ''),
						'sun_start' => array('VCHAR:5', ''),
						'sun_end' => array('VCHAR:5', '')
					),
					'PRIMARY_KEY' => 'user_id'
				),
				$this->table_prefix . self::$tableNames['guardians'] => array(
					'COLUMNS' => array(
						'guardian_id' => array('UINT', 0, 'UNSIGNED'),
						'charge_id' => array('UINT', 0, 'UNSIGNED')
					),
					'PRIMARY_KEY' => 'guardian_id, charge_id'
				)
			)
		);
	}

	public function revert_schema() {
		return array(
			"drop_tables" => array(
				$this->table_prefix . self::$tableNames['user_access'],
				$this->table_prefix . self::$tableNames['guardians']
			)
		);
	}
}