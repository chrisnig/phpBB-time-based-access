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

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'TBA_TIMEBASED_ACCESS_DENIED'                     => 'Due to time-based restrictions, you cannot access the forums right now.',
	'TBA_UCP_CAT'                                     => 'Time-based access',
	'TBA_UCP_HEADING'                                 => 'Time-based access controls',
	'TBA_UCP_MANAGEME'                                => 'My access',
	'TBA_UCP_MANAGEOTHERS'                            => 'Others\' access',
	'TBA_UCP_MYACC_EXPLAIN'                           => 'Here you can manage time-based access restrictions for your own account. Outside of these times, you will not be able to access the forums.',
	'TBA_UCP_MYACC_RESTRICTMODE'                      => 'Restriction mode',
	'TBA_UCP_MYACC_REST_NONE'                         => 'No restrictions',
	'TBA_UCP_MYACC_REST_ME'                           => 'Restrictions specified by myself',
	'TBA_UCP_MYACC_REST_GUARDIAN'                     => 'Restrictions specified by a time guardian',
	'TBA_UCP_MYACC_GUARDIAN'                          => 'Time guardian',
	'TBA_UCP_MYACC_ALLOWED_TIMES'                     => 'Times during which board is accessible',
	'TBA_UCP_MYACC_FROM'                              => 'from',
	'TBA_UCP_MYACC_UNTIL'                             => 'until',
));