<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2013 Mario Rimann (typo3-coding@rimann.org)
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
 * Class 'tx_bzdstaffdirectory_Mapper' for the 'bzdstaffdirectory' extension.
 *
 * This class represents a generic mapper to be extended for the individual
 * implementataions.
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Mario Rimann <typo3-coding@rimann.org>
 */
class tx_bzdstaffdirectory_Mapper_Generic extends tx_oelib_DataMapper {

	/**
	 * List of fields to be overlayed, needs to be set in the specific
	 * implementation of this mapper.
	 *
	 * @var array
	 */
	protected $fieldsToOverlay = array();

	/**
	 * Takes an object in the default language and tries to overlay it's values
	 * with localized values.
	 *
	 * @param tx_bzdstaffdirectory_Model_Generic the object to overlay
	 * @param integer the language UID
	 */
	public function overlayRecord(tx_bzdstaffdirectory_Model_Generic $person, $sysLanguageUid) {
		try {
			$overlayRecord = tx_oelib_db::selectSingle(
				'*',
				$this->tableName,
				'sys_language_uid = ' . $sysLanguageUid . ' AND l18n_parent=' . $person->getUid() . ' AND deleted=0 AND hidden=0'
			);
		} catch (Exception $e) {
			// whoopsie, looks like there's no translation for this person
			// return the original person object instead
			return $person;
		}

		foreach($this->fieldsToOverlay as $key) {
			$person->setValue($key, $overlayRecord[$key]);
		}

		return $person;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Mapper/class.tx_bzdstaffdirectory_Mapper.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/Mapper/class.tx_bzdstaffdirectory_Mapper.php']);
}
?>