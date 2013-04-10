<?php
/***************************************************************
* Copyright notice
*
* (c) 2009-2012 Mario Rimann (typo3-coding@rimann.org)
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
 * Class 'tx_bzdstaffdirectory_Model_Function' for the 'bzdstaffdirectory' extension.
 *
 * This class represents a function.
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Mario Rimann <typo3-coding@rimann.org>
 */
class tx_bzdstaffdirectory_Model_Function extends tx_bzdstaffdirectory_Model_Generic {
	/**
	 * Returns the title of this location
	 *
	 * @return string the title of this location
	 */
	public function getTitle() {
		return $this->getAsString('title');
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Model/class.tx_bzdstaffdirectory_Model_Function.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Model/class.tx_bzdstaffdirectory_Model_Function.php']);
}
?>