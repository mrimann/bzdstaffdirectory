<?php
/***************************************************************
* Copyright notice
*
* (c) 2009 Mario Rimann (mario@screenteam.com)
* All rights reserved
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Class 'frontEndDetailView for the 'bzdstaffdirectory' extension.
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Mario Rimann <mario@screenteam.com>
 */
class tx_bzdstaffdirectory_pi1_frontEndDetailView extends tx_bzdstaffdirectory_pi1_frontEndView {
	/**
	 * @var string path to this script relative to the extension dir
	 */
	public $scriptRelPath = 'pi1/class.tx_bzdstaffdirectory_pi1_frontEndDetailView.php';

	/**
	 * @var tx_bzdstaffdirectory_person the person for which we want to show the
	 *                          detail view.
	 */
	private $person = null;

	/**
	 * @var boolean true if the current object is in test mode, false otherwise
	 */
	private $testMode = false;

	/**
	 * The destructor.
	 */
	public function __destruct() {
		if ($this->person) {
			$this->person->__destruct();
			unset($this->person);
		}

		parent::__destruct();

	}

	public function setPerson($personUid) {
		$this->person = $this->createPerson($personUid);
	}

	/**
	 * Creates a person in $this->person.
	 *
	 * @param integer a person's UID, must be >= 0
	 */
	private function createPerson($personUid) {
		try {
			$person = tx_oelib_MapperRegistry::get('tx_bzdstaffdirectory_Mapper_Person')
					->find($personUid);
		} catch (tx_oelib_Exception_NotFound $exception) {
			$person = null;
		}

		return $person;
	}

	/**
	 * Creates a countdown to the next upcoming event.
	 *
	 * @return string HTML code of the countdown or a message if no upcoming
	 *                event found
	 */
	public function render() {
		// merge the marker content with the template
		$this->setMarker('first_name', $this->person->getFirstName());
		$result .= $this->getSubpart('TEMPLATE_DETAIL');

		return $result;


		$this->setMarker('count_down_message', $message);
		$result = $this->getSubpart('COUNTDOWN');

		$this->checkConfiguration();
		$result .= $this->getWrappedConfigCheckMessage();

		return $result;
	}

	/**
	 * Returns a localized string representing an amount of seconds in words.
	 * For example:
	 * 150000 seconds -> "1 day"
	 * 200000 seconds -> "2 days"
	 * 50000 seconds -> "13 hours"
	 * The function uses localized strings and also looks for proper usage of
	 * singular/plural.
	 *
	 * @param integer the amount of seconds to rewrite into words
	 *
	 * @return string a localized string representing the time left until the
	 *                event starts
	 */
	private function createCountdownMessage($seconds) {
		if ($seconds > 82800) {
			// more than 23 hours left, show the time in days
			$countdownValue = round($seconds / ONE_DAY);
			if ($countdownValue > 1) {
				$countdownText = $this->translate('countdown_days_plural');
			} else {
				$countdownText = $this->translate('countdown_days_singular');
			}
		} elseif ($seconds > 3540) {
			// more than 59 minutes left, show the time in hours
			$countdownValue = round($seconds / 3600);
			if ($countdownValue > 1) {
				$countdownText = $this->translate('countdown_hours_plural');
			} else {
				$countdownText = $this->translate('countdown_hours_singular');
			}
		} elseif ($seconds > 59) {
			// more than 59 seconds left, show the time in minutes
			$countdownValue = round($seconds / 60);
			if ($countdownValue > 1) {
				$countdownText = $this->translate('countdown_minutes_plural');
			} else {
				$countdownText = $this->translate('countdown_minutes_singular');
			}
		} else {
			// less than 60 seconds left, show the time in seconds
			$countdownValue = $seconds;
			$countdownText = $this->translate('countdown_seconds_plural');
		}

		return sprintf(
			$this->translate('message_countdown'),
			$countdownValue,
			$countdownText
		);
	}

	/**
	 * Enables the test mode for the current object.
	 */
	public function setTestMode() {
		$this->testMode = true;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/seminars/pi1/class.tx_bzdstaffdirectory_pi1_frontEndDetailView.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/seminars/pi1/class.tx_bzdstaffdirectory_pi1_frontEndDetailView.php']);
}
?>