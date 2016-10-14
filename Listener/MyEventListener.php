<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * @copyright (c) phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * For full copyright and license information, please see
 * the docs/CREDITS.txt file.
 *
 */

namespace chrisnig\tba\Listener;

use chrisnig\tba\Manager\AccessTimeManager;
use chrisnig\tba\migrations\MainMigration;
use phpbb\event\data;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use phpbb\template\template;

/**
 * Event listener
 */
class MyEventListener implements EventSubscriberInterface
{
	/* @var template */
	protected $template;

	/* @var AccessTimeManager */
	protected $access_manager;

	public function __construct(template $template, AccessTimeManager $access_manager)
	{
		$this->template = $template;
		$this->access_manager = $access_manager;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup' => 'init_ext',
			'core.display_forums_modify_sql' => 'triggerErrorIfAccessDenied',
			'core.viewforum_get_topic_ids_data' => 'triggerErrorIfAccessDenied',
			'core.viewtopic_get_post_data' => 'triggerErrorIfAccessDenied',
			'core.ucp_pm_view_messsage' => 'triggerErrorIfAccessDenied',
		);
	}

	public function triggerErrorIfAccessDenied(data $event) {
		if (!$this->access_manager->isAccessGranted()) {
			trigger_error('TBA_TIMEBASED_ACCESS_DENIED');
		}
	}

	public function init_ext($event)
	{
		global $user;
		$user->add_lang_ext('chrisnig/tba', 'lang');

		global $table_prefix;
		define('TBA_TABLE_USER_ACCESS', $table_prefix . MainMigration::$tableNames["user_access"]);
	}
}