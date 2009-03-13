<?php
/***************************************************************
* Copyright notice
*
* (c) 2009 Mario Rimann <mario@screenteam.com>
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

require_once(t3lib_extMgm::extPath('oelib') . 'class.tx_oelib_Autoloader.php');

/**
 * Class 'tx_bzdstaffdirectory_Model_Team' for the 'bzdstaffdirectory' extension.
 *
 * This class represents a team.
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Mario Rimann <mario@screenteam.com>
 */
class tx_bzdstaffdirectory_Model_Team extends tx_oelib_Model {

	/**
	 * The constructor for this class.
	 */
	public function __construct() {
	}

	/**
	 * Returns a boolean value whether the person has a title set or not.
	 *
	 * @return boolean whether a title is set or not
	 */
	public function hasTitle() {
		return $this->hasString('title');
	}

	/**
	 * Returns the title of the person.
	 *
	 * @return string the title of the person, may be empty
	 */
	public function getTitle() {
		return $this->getAsString('group_name');
	}

	/**
	 * Checks whether this team has an info page selected
	 *
	 * @return boolean true if the team has a page defined, false otherwise
	 */
	public function hasInfopage() {
		return $this->hasInteger('infopage');
	}

	/**
	 * Returns the PID of the info page of this team.
	 *
	 * @return integer the PID of the infopage
	 */
	public function getInfopagePid() {
		return $this->getAsInteger('infopage');
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Model/class.tx_bzdstaffdirectory_Model_Team.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Model/class.tx_bzdstaffdirectory_Model_Team.php']);
}
?>