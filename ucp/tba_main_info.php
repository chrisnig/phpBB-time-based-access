<?php

namespace chrisnig\tba\ucp;

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

class tba_main_info
{
	function module()
	{
		return [
			"filename" => '\chrisnig\tba\ucp\tba_main_module',
			"title" => "TBA_UCP_HEADING",
			"version" => "0.1",
			"modes" => [
				"manageme" => ["title" => "TBA_UCP_MANAGEME", "auth" => "ext_chrisnig/tba"],
				"manageothers" => ["title" => "TBA_UCP_MANAGEOTHERS", "auth" => "ext_chrisnig/tba"]
			],
		];
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}