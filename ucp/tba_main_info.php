<?php

namespace chrisnig\tba\ucp;

class tba_main_info
{
	function module()
	{
		return [
			"filename" => 'chrisnig\tba\ucp\tba_main_module',
			"title" => "TBA_UCP_HEADING",
			"version" => "0.1",
			"modes" => [
				"manageme" => ["title" => "TBA_UCP_MANAGEME", "auth" => ""],
				"manageothers" => ["title" => "TBA_UCP_MANAGEOTHERS", "auth" => ""]
			]
		];
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}