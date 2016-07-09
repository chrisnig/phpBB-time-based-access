<?php

namespace chrisnig\tba\Manager;

use phpbb\user;

class AccessTimeManager
{
	/* @var user */
	protected $user;

	/* @var bool|null */
	protected $isGranted;

	public function __construct(user $user)
	{
		$this->user = $user;
		$this->isGranted = null;
	}

	/**
	 * Checks whether a user is allowed to access the forums at the current time.
	 *
	 * @return bool
	 */
	public function isAccessGranted()
	{
		if ($this->isGranted !== null) {
			return $this->isGranted;
		}

		return $this->isGranted = $this->checkAccessGranted();
	}

	private function checkAccessGranted() {
		if ($this->user->data['user_id'] === ANONYMOUS) {
			// we do not store data for anon users, they always have access
			return true;
		}

		$now = new \DateTime();
		$todayWeekDay = strtolower($now->format("D"));

		/* @var \phpbb\db\driver\driver_interface $db */
		global $db;
		$query = $db->sql_build_query("SELECT", [
			'SELECT' => $todayWeekDay. '_start, ' . $todayWeekDay . '_end',
			'FROM' => [
				'phpbb_tba_user_access' => 'tua' // TODO: refactor to get correct table prefix
			],
			'WHERE' => 'tua.user_id = ' . $this->user->data['user_id']
		]);

		$result = $db->sql_query($query);

		if ($result->num_rows === 0) {
			// user does not have time-based access check set up, so they always have access
			return true;
		}

		$resultRow = $result->fetch_row();
		$startTime = \DateTime::createFromFormat("H:i:s", $resultRow[0] . ":00");
		$endTime = \DateTime::createFromFormat("H:i:s", $resultRow[1] . ":59");

		return $now >= $startTime && $now <= $endTime;
	}
}