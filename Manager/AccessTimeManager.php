<?php

namespace chrisnig\tba\Manager;

use phpbb\db\driver\driver_interface;
use phpbb\user;

class AccessTimeManager
{
	/* @var user */
	protected $user;

	/* @var bool|null */
	protected $isGranted;

	/** @var \phpbb\db\driver\driver_interface $db */
	protected $db;

	public function __construct(user $user, driver_interface $db)
	{
		$this->user = $user;
		$this->db = $db;
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
		// TODO refactor this so it also returns guardian mode when it is implemented
		if (!$userId) {
			$userId = $this->user->data['user_id'];
		}

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

	public function setAccessTimes($userId, $accessTimes) {
		if (count($accessTimes) !== 14) {
			throw new \Exception('Invalid number of entries in access time array!');
		}

		foreach ($accessTimes as $key => $time) {
			$accessTimes[$key] = $this->fixTime($time);
			if (!$this->validateTime($accessTimes[$key])) {
				if (strpos($key, 'start')) {
					$accessTimes[$key] = '00:00';
				} else {
					$accessTimes[$key] = '23:59';
				}
			}
		}

		$this->removeTimeRestrictions($userId);

		$accessTimes['user_id'] = $userId;
		$query = 'INSERT INTO ' . TBA_TABLE_USER_ACCESS . ' ' . $this->db->sql_build_array('INSERT', $accessTimes);
		$this->db->sql_query($query);
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

	public function removeAccessRestrictions($userId = null)
	{
		if (!$userId) {
			$userId = $this->user->data['user_id'];
		}

		$this->removeGuardian($userId);
		$this->removeTimeRestrictions($userId);
	}

	public function removeTimeRestrictions($userId) {
		global $db;
		$db->sql_query('DELETE FROM ' . TBA_TABLE_USER_ACCESS .
			' WHERE user_id = ' . $userId . ';');
	}

	public function setGuardianByUsername($userId, $guardianUsername) {
		// TODO
		$this->removeTimeRestrictions($userId);
	}

	public function removeGuardian($userId) {
		// TODO
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

	private function fixTime($time) {
		$time = str_replace('.', ':', $time);
		if (strlen($time) < 5) {
			$time = '0' . $time;
		}
		return $time;
	}

	private function validateTime($time) {
		return preg_match('/([01][0-9]|2[0-3]):[0-5][0-9]/', $time) === 1;
	}
}