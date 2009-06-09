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
 * Class 'tx_bzdstaffdirectory_Model_Location' for the 'bzdstaffdirectory' extension.
 *
 * This class represents a location.
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Mario Rimann <mario@screenteam.com>
 */
class tx_bzdstaffdirectory_Model_Location extends tx_oelib_Model {
	/**
	 * Returns the title of this location
	 *
	 * @return string the title of this location
	 */
	public function getTitle() {
		return $this->getAsString('title');
	}

	/**
	 * Checks whether this location has an info page selected
	 *
	 * @return boolean true if the location has a page defined, false otherwise
	 */
	public function hasInfopage() {
		return $this->hasInteger('infopage');
	}

	/**
	 * Returns the PID of the info page of this location.
	 *
	 * @return integer the PID of the infopage
	 */
	public function getInfopagePid() {
		return $this->getAsInteger('infopage');
	}

	/**
	 * Checks whether this location has an address stored.
	 *
	 * @return boolean true if the address field is not empty, false otherwise
	 */
	public function hasAddress() {
		return $this->hasString('address');
	}

	/**
	 * Returns the whole address for this location
	 *
	 * @return string the address
	 */
	public function getAddress() {
		return $this->getAsString('address');
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Model/class.tx_bzdstaffdirectory_Model_Location.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Model/class.tx_bzdstaffdirectory_Model_Location.php']);
}
?>