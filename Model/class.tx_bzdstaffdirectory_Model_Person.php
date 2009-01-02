<?php
/***************************************************************
* Copyright notice
*
* (c) 2008-2009 Oliver Klee <typo3-coding@oliverklee.de>
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
 * Class 'tx_bzdstaffdirectory_Model_Person' for the 'bzdstaffdirectory' extension.
 * The original code is from the Seminar Manager (tx_seminars), thanks Oli!
 *
 * This class represents a person.
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 * @author Mario Rimann <mario@screenteam.com>
 */
class tx_bzdstaffdirectory_Model_Person extends tx_oelib_Model {
	/**
	 * Returns the first name of the person.
	 *
	 * @return string the first name of the person, plain text
	 */
	public function getFirstName() {
		return $this->getAsString('first_name');
	}

	/**
	 * Returns the last name of the person.
	 *
	 * @return string the last name of the person, plain text
	 */
	public function getLastName() {
		return $this->getAsString('last_name');
	}

	/**
	 * Returns the age of the person.
	 *
	 * @return integer the age in years, rounded
	 */
	public function getAge() {
		return $this->getAsInteger('date_birthdate');
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/oelib/Model/class.tx_bzdstaffdirectory_Model_Person.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/oelib/Model/class.tx_bzdstaffdirectory_Model_Person.php']);
}
?>