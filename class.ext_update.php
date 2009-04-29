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
 * Class 'ext_update' for the 'bzdstaffdirectory' extension.
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Mario Rimann <mario@screenteam.com>
 */
class ext_update {

	private $requiresUpdate = false;

	/**
	 * Returns true all the time to show the menu in any case.
	 *
	 * @return unknown
	 */
	public function access() {
		return true;
	}

	public function main() {
		$result = '';

		// Show the status
		$result .= '<h2>Changes from 0.7.x to 0.8.0</h2>';
		$result .= $this->showStatus(
			$this->checkIfOldExtensionKeyIsStillLoaded(),
			'Check that the old extension key (tx_bzd_staff_directory) is not loaded anymore.'
		);
		$result .= $this->showStatus(
			$this->checkContentElementsForOldExtensionKey(),
			'Check Content Elements for old Extension Key (replace "tx_bzd_staff_directory" with "tx_bzdstaffdirectory").'
		);
		$result .= $this->showStatus(
			$this->checkTemplateRecordsForOldExtensionKey(),
			'Check Template Records for old Extension Key (replace "tx_bzd_staff_directory" with "tx_bzdstaffdirectory").'
		);
		$result .= $this->showStatus(
			$this->checkTemplateRecordsForOldExtensionKeyInIncludeStaticTemplate(),
			'Check Template Records for old Extension Key regarding the "include static template" (replace "tx_bzd_staff_directory" with "tx_bzdstaffdirectory").'
		);
		if ($this->requiresUpdate) {
			$result .= '<hr /><b style="color: red;">Please perform the required changes and re-check with this update script.</b>';
		} else {
			$result .= '<hr /><b style="color: green;">Seems that everything is up to date!</b>' .
				'<p>If you still encounter any problem, please open a bug report at <a href="https://bugs.oliverklee.com/">https://bugs.oliverklee.com/</a> - thanks!';
		}

		return $result;
	}

	/**
	 * Shows the status line for a certain check.
	 *
	 * @param boolean whether this check requires any update
	 * @param string the message/description to show for this check
	 * @return string HTML for the whole status line
	 */
	private function showStatus($checkResult, $message) {
		$result = '<p>';
		if (!$checkResult) {
			$result .= '<img src="../../../sysext/t3skin/icons/gfx/icon_ok.gif" />';
		} else {
			$result .= '<img src="../../../sysext/t3skin/icons/gfx/icon_warning.gif" />';
			$this->requiresUpdate = true;
		}
		$result .= $message;
		$result .= '</p>';

		return $result;
	}

	/**
	 * Checks whether the old extension key is still loaded in the system.
	 *
	 * @return boolean true if action is required
	 */
	private function checkIfOldExtensionKeyIsStillLoaded() {
		return t3lib_extMgm::isLoaded('bzd_staff_directory');
	}

	/**
	 * Checks whether there are content records of type plugin where the list_type
	 * is pointing to the old extension key.
	 *
	 * @return boolean true if at least one offending record was found, false otherwise
	 */
	private function checkContentElementsForOldExtensionKey() {
		$dbResult = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'tt_content',
			'list_type="bzd_staff_directory_pi1" AND deleted=0 AND hidden=0'
		);

		if ($dbResult && $GLOBALS['TYPO3_DB']->sql_num_rows($dbResult)) {
			return true;
		}

		return false;
	}

	/**
	 * Checks whether there are template records that contain config stuff for
	 * the old extension key.
	 *
	 * @return boolean true if there is at least one offending template record, false otherwise
	 */
	private function checkTemplateRecordsForOldExtensionKey() {
		$dbResult = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'sys_template',
			'config LIKE "%bzd_staff_directory%" AND deleted=0 AND hidden=0'
		);

		if ($dbResult && $GLOBALS['TYPO3_DB']->sql_num_rows($dbResult)) {
			return true;
		}

		return false;
	}

	/**
	 * Checks whether there are template records that include the old static
	 * template from the extension.
	 *
	 * @return boolean true if at least one offending template record was found, false otherwise
	 */
	private function checkTemplateRecordsForOldExtensionKeyInIncludeStaticTemplate() {
		$dbResult = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'sys_template',
			'include_static_file LIKE "%bzd_staff_directory%" AND deleted=0 AND hidden=0'
		);

		if ($dbResult && $GLOBALS['TYPO3_DB']->sql_num_rows($dbResult)) {
			return true;
		}

		return false;
	}
}
?>