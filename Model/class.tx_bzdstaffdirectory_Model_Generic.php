<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Mario Rimann <typo3-coding@rimann.org>
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
 * Class 'tx_bzdstaffdirectory_Model_Generic' for the 'bzdstaffdirectory' extension.
 *
 * This class represents a generic model object.
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Mario Rimann <typo3-coding@rimann.org>
 */
class tx_bzdstaffdirectory_Model_Generic extends tx_oelib_Model {
	/**
	 * Sets a value within this object. Basically needed to make the protected
	 * function of tx_oelib_Model accessible from the outside.
	 *
	 * @param the key (e.g. database field name)
	 * @param the value to be stored
	 */
	public function setValue($key, $value) {
		$this->set($key, $value);
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Model/class.tx_bzdstaffdirectory_Model_Generic.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Model/class.tx_bzdstaffdirectory_Model_Generic.php']);
}
?>