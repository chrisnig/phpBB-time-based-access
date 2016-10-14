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

	public function getRestrictionMode($userId = null) {
		if (!$userId) {
			$userId = $this->user->data['user_id'];
		}

		// TODO refactor this so it also returns guardian mode when it is implemented
		if ($userId === ANONYMOUS) {
			// we do not store data for anon users, they always have access
			return "none";
		}

		/* @var \phpbb\db\driver\driver_interface $db */
		global $db;
		$query = $db->sql_build_query("SELECT", array(
			'SELECT' => "'1'",
			'FROM' => array(
				TBA_TABLE_USER_ACCESS => 'tua'
			),
			'WHERE' => 'tua.user_id = ' . $userId
		));

		$result = $db->sql_query($query);

		if ($result->num_rows === 0) {
			// user does not have time-based access check set up, so they always have access
			return "none";
		} else {
			return "me";
		}
	}

	public function getAccessTimes($userId = null)
	{
		if (!$userId) {
			$userId = $this->user->data['user_id'];
		}

		if ($userId === ANONYMOUS) {
			return null;
		}

		/* @var \phpbb\db\driver\driver_interface $db */
		global $db;
		$query = $db->sql_build_query("SELECT", array(
			'SELECT' => 'mon_start, mon_end, tue_start, tue_end, wed_start, wed_end, thu_start, thu_end, fri_start, '.
				'fri_end, sat_start, sat_end, sun_start, sun_end',
			'FROM' => array(
				TBA_TABLE_USER_ACCESS => 'tua'
			),
			'WHERE' => 'tua.user_id = ' . $userId
		));

		$result = $db->sql_query($query);

		if ($result->num_rows === 0) {
			// user does not have time-based access check set up, so they always have access
			return true;
		}

		$resultRow = $result->fetch_row();

		return array(
			"mon_start" => $resultRow[0],
			"mon_end" => $resultRow[1],
			"tue_start" => $resultRow[2],
			"tue_end" => $resultRow[3],
			"wed_start" => $resultRow[4],
			"wed_end" => $resultRow[5],
			"thu_start" => $resultRow[6],
			"thu_end" => $resultRow[7],
			"fri_start" => $resultRow[8],
			"fri_end" => $resultRow[9],
			"sat_start" => $resultRow[10],
			"sat_end" => $resultRow[11],
			"sun_start" => $resultRow[12],
			"sun_end" => $resultRow[13]
		);
	}

	private function checkAccessGranted($userId = null)
	{
		if (!$userId) {
			$userId = $this->user->data['user_id'];
		}

		if ($userId === ANONYMOUS) {
			// we do not store data for anon users, they always have access
			return true;
		}

		$now = new \DateTime();
		$todayWeekDay = strtolower($now->format("D"));

		/* @var \phpbb\db\driver\driver_interface $db */
		global $db;
		$query = $db->sql_build_query("SELECT", array(
			'SELECT' => $todayWeekDay . '_start, ' . $todayWeekDay . '_end',
			'FROM' => array(
				TBA_TABLE_USER_ACCESS => 'tua'
			),
			'WHERE' => 'tua.user_id = ' . $userId
		));

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