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
 * The person class in the 'bzd_staff_directory' extension.
 *
 * @package		TYPO3
 * @subpackage	tx_bzdstaffdirectory
 * @author		Mario Rimann <mario@screenteam.com>
 */

require_once(t3lib_extMgm::extPath('bzd_staff_directory').'class.tx_bzdstaffdirectory_objectFromDb.php');


class tx_bzdstaffdirectory_person extends tx_bzdstaffdirectory_objectFromDb{

	/**
	 * The constructor for this class.
	 */
	public function __construct($uid, $dbResult = null) {
		$this->tableName = 'tx_bzdstaffdirectory_persons';
		parent::__construct($uid, $dbResult);
	}

	/**
	 * Returns the first name of the person.
	 *
	 * @return	string		the first name of the person, plain text
	 */
	public function getFirstName() {
		return $this->getRecordPropertyString('first_name');
	}

	/**
	 * Returns the last name of the person.
	 *
	 * @return	string		the last name of the person, plain text
	 */
	public function getLastName() {
		return $this->getRecordPropertyString('last_name');
	}


}

?>
