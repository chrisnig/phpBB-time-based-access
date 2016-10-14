<?php

namespace chrisnig\tba\ucp;

/**
 * @ignore
 */

use chrisnig\tba\Manager\AccessTimeManager;

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

	public function main($id, $mode)
	{
		global $db, $user, $auth, $template;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$this->page_title = "TBA_UCP_HEADING";
		$submit = isset($_POST['submit']);

		switch ($mode) {
			case "manageme":
				$accessTimes = array(
					"mon_start" => "",
					"mon_end" => "",
					"tue_start" => "",
					"tue_end" => "",
					"wed_start" => "",
					"wed_end" => "",
					"thu_start" => "",
					"thu_end" => "",
					"fri_start" => "",
					"fri_end" => "",
					"sat_start" => "",
					"sat_end" => "",
					"sun_start" => "",
					"sun_end" => ""
				);

				if ($submit) {

				} else {
					global $phpbb_container;
					/** @var AccessTimeManager $accessManager */
					$accessManager = $phpbb_container->get("chrisnig.tba.access_time_manager");
					$restrictionMode = $accessManager->getRestrictionMode();
					switch ($restrictionMode) {
						case 'guardian':
							$selectedNone = false;
							$selectedMe = false;
							$selectedGuardian = true;
							break;
						case 'me':
							$selectedNone = false;
							$selectedMe = true;
							$selectedGuardian = false;
							break;
						case 'none':
							$selectedNone = true;
							$selectedMe = false;
							$selectedGuardian = false;
							break;
						default:
							throw new \Exception("Invalid restriction mode '".$restrictionMode."'!");
					}

					if ($restrictionMode === 'guardian' || $restrictionMode === 'me') {
						$accessTimes = $accessManager->getAccessTimes();
					}
				}

				$this->tpl_name = 'tba_my_restrictions';
				$template->assign_vars($accessTimes);
				$template->assign_vars(array(
					'TBA_UCP_MYACC_EXPLAIN' => $user->lang['TBA_UCP_MYACC_EXPLAIN'],
					'TBA_UCP_MYACC_RESTRICTMODE' => $user->lang['TBA_UCP_MYACC_RESTRICTMODE'],
					'RESTRICTMODE_NONE_SELECTED' => $selectedNone ? "checked" : "",
					'RESTRICTMODE_ME_SELECTED' => $selectedMe ? "checked" : "",
					'RESTRICTMODE_GUARDIAN_SELECTED' => $selectedGuardian ? "checked" : "",
					'TBA_UCP_MYACC_REST_NONE' => $user->lang['TBA_UCP_MYACC_REST_NONE'],
					'TBA_UCP_MYACC_REST_ME' => $user->lang['TBA_UCP_MYACC_REST_ME'],
					'TBA_UCP_MYACC_REST_GUARDIAN' => $user->lang['TBA_UCP_MYACC_REST_GUARDIAN'],
					'TBA_UCP_MYACC_GUARDIAN' => $user->lang['TBA_UCP_MYACC_GUARDIAN'],
					'TBA_UCP_MYACC_ALLOWED_TIMES' => $user->lang['TBA_UCP_MYACC_ALLOWED_TIMES'],
					'Monday' => $user->lang(array('datetime', 'Monday')),
					'Tuesday' => $user->lang(array('datetime', 'Tuesday')),
					'Wednesday' => $user->lang(array('datetime', 'Wednesday')),
					'Thursday' => $user->lang(array('datetime', 'Thursday')),
					'Friday' => $user->lang(array('datetime', 'Friday')),
					'Saturday' => $user->lang(array('datetime', 'Saturday')),
					'Sunday' => $user->lang(array('datetime', 'Sunday')),
					'TBA_UCP_MYACC_FROM' => $user->lang['TBA_UCP_MYACC_FROM'],
					'TBA_UCP_MYACC_UNTIL' => $user->lang['TBA_UCP_MYACC_UNTIL'],
					'ACTION' => $this->u_action
				));
				break;
			case "manageothers":
				$this->tpl_name = 'main_result';
				break;
		}
	}
}