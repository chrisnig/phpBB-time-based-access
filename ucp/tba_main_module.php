<?php

namespace chrisnig\tba\ucp;

class tba_main_module
{
	public $u_action;
	public $tpl_name;
	public $page_title;

	public function main($id, $mode)
	{
		global $db, $user, $auth, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		$this->page_title = "TBA_UCP_HEADING";
		$this->tpl_name = "main_result";
	}
}