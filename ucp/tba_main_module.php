<?php

namespace chrisnig\tba\ucp;

/**
 * @ignore
 */
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;

if (!defined('IN_PHPBB'))
{
	exit;
}

class tba_main_module
{
	public $u_action;
	public $tpl_name;
	public $page_title;
	private $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	public function main($id, $mode)
	{
		global $db, $user, $auth, $template, $container;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$this->page_title = "TBA_UCP_HEADING";

		switch ($mode) {
			case "manageme":
				$this->tpl_name = 'tba_my_restrictions';
				$template->assign_vars([
					'TBA_UCP_MYACC_EXPLAIN' => $user->lang['TBA_UCP_MYACC_EXPLAIN'],
					'ACTION' => $this->u_action
				]);
				break;
			case "manageothers":
				$this->tpl_name = 'main_result';
				break;
		}
	}
}