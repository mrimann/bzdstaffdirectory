<?php
/***************************************************************
* Copyright notice
*
* (c) 2008 Mario Rimann (typo3-coding@rimann.org)
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
 * The team class in the 'bzdstaffdirectory' extension.
 *
 * @package		TYPO3
 * @subpackage	tx_bzdstaffdirectory
 * @author		Mario Rimann <typo3-coding@rimann.org>
 */

require_once(t3lib_extMgm::extPath('bzdstaffdirectory').'class.tx_bzdstaffdirectory_objectFromDb.php');


class tx_bzdstaffdirectory_team extends tx_bzdstaffdirectory_objectFromDb{

	/**
	 * The constructor for this class.
	 */
	public function __construct($uid, $dbResult = null) {
		$this->tableName = 'tx_bzdstaffdirectory_groups';
		parent::__construct($uid, $dbResult);
	}

	/**
	 * Returns the title / team name of the team.
	 *
	 * @return	string		the title of the team
	 */
	public function getTitle() {
		return $this->getRecordPropertyString('group_name');
	}

	/**
	 * Returns the UID of the info page for this team.
	 *
	 * @return	integer		the UID of the infopage of this team
	 */
	public function getInfoPageUid() {
		return $this->getRecordPropertyInteger('infopage');
	}
}

?>
