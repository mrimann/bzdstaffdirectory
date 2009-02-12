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
 * Fixture class which extends the objectFromDb class in the 'bzd_staff_directory' extension for testing.
 *
 * @package		TYPO3
 * @subpackage	tx_bzdstaffdirectory
 * @author		Mario Rimann <mario@screenteam.com>
 */

require_once(t3lib_extMgm::extPath('bzd_staff_directory').'class.tx_bzdstaffdirectory_objectFromDb.php');


class tx_bzdstaffdirectory_objectFromDb_child extends tx_bzdstaffdirectory_objectFromDb {

	public function __construct($uid, $dbResult = null) {
		$this->tableName = 'tx_bzdstaffdirectory_groups';
		parent::__construct($uid, $dbResult);
	}

}

?>
