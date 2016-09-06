<?php

namespace chrisnig\tba\migrations;

use phpbb\db\migration\migration;

class MainMigration extends migration
{
	static $catInfo = [
		"ucp",
		0,
		"TBA_UCP_CAT"
	];

	static $moduleInfo = [
		"ucp",
		"TBA_UCP_CAT",
		[
			"module_basename" => '\chrisnig\tba\ucp\tba_main_module'
		]
	];

	public function update_data()
	{
		return [
			['module.add', self::$catInfo],
			['module.add', self::$moduleInfo]
		];
	}

	public function revert_data()
	{
		return [
			['module.remove', self::$moduleInfo],
			['module.remove', self::$catInfo]
		];
	}

	public function update_schema() {
		return [
			'add_tables' => [
				$this->table_prefix . "tba_user_access" => [
					"COLUMNS" => [
						"user_id" => ["UINT", 0, 'UNSIGNED'],
						"mon_start" => ["VCHAR:5", ""], // it would be great if we could use TIME here, but phpBB schema
						"mon_end" => ["VCHAR:5", ""],   // tool does not support this type
						"tue_start" => ["VCHAR:5", ""],
						"tue_end" => ["VCHAR:5", ""],
						"wed_start" => ["VCHAR:5", ""],
						"wed_end" => ["VCHAR:5", ""],
						"thu_start" => ["VCHAR:5", ""],
						"thu_end" => ["VCHAR:5", ""],
						"fri_start" => ["VCHAR:5", ""],
						"fri_end" => ["VCHAR:5", ""],
						"sat_start" => ["VCHAR:5", ""],
						"sat_end" => ["VCHAR:5", ""],
						"sun_start" => ["VCHAR:5", ""],
						"sun_end" => ["VCHAR:5", ""]
					],
					"PRIMARY_KEY" => "user_id"
				]
			]
		];
	}

	public function revert_schema() {
		return [
			"drop_tables" => [
				$this->table_prefix . "tba_user_access"
			]
		];
	}
}