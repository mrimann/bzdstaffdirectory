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

require_once(t3lib_extMgm::extPath('oelib') . 'class.tx_oelib_Autoloader.php');

/**
 * Class 'frontEndVcfView for the 'bzdstaffdirectory' extension.
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Mario Rimann <mario@screenteam.com>
 */
class tx_bzdstaffdirectory_pi1_frontEndVcfView extends tx_bzdstaffdirectory_pi1_frontEndView {
	/**
	 * @var string path to this script relative to the extension dir
	 */
	public $scriptRelPath = 'pi1/class.tx_bzdstaffdirectory_pi1_frontEndVcfView.php';

	/**
	 * @var tx_bzdstaffdirectory_Model_Person the person for which we want to show the
	 *                          detail view.
	 */
	private $person = null;

	/**
	 * @var boolean true if the current object is in test mode, false otherwise
	 */
	private $testMode = false;

	/**
	 * The constructor.
	 *
	 * @param integer UID of the person to show
	 * @param array TypoScript configuration for the plugin
	 * @param tslib_cObj the parent cObj content, needed for the flexforms
	 */
	public function __construct($personUid, $configuration, $cObj) {
		$this->cObj = $cObj;
		$this->init($configuration);
		$this->pi_initPIflexForm();

		$this->getTemplateCode();

		// Generates the person object and stores it in $this->person.
		$this->createPerson($personUid);
	}

	/**
	 * The destructor.
	 */
	public function __destruct() {
		if ($this->person) {
			$this->person->__destruct();
			unset($this->person);
		}

		parent::__destruct();

	}

	/**
	 * Creates a person in $this->person.
	 *
	 * @param integer a person's UID, must be >= 0
	 */
	private function createPerson($personUid) {
		try {
			$mapper = tx_oelib_MapperRegistry::get('tx_bzdstaffdirectory_Mapper_Person');
			if ($mapper->existsModel($personUid)) {
				$this->person = $mapper->find($personUid);
			}
		} catch (tx_oelib_Exception_NotFound $exception) {
			$this->person = null;
		}
	}

	/**
	 * Creates a vCard for a single person.
	 *
	 * @return string the vCard
	 */
	public function render() {
		// Set's the name and title markers
		$this->setMarker('first_name', $this->person->getFirstName());
		$this->setMarker('last_name', $this->person->getLastName());
		$this->setMarker('function', $this->person->getFunction()->getTitle());
		$this->setMarker('phone', $this->person->getStandardField('phone'));
		$this->setMarker('email', $this->person->getStandardField('email'));
		$this->setMarker('company', 'Pumuckl');

		$rev = date('Ymd\THis\Z', $this->person->getLastUpdateTimestamp());
		$this->setMarker('rev', $rev);


		$result = $this->getSubpart('TEMPLATE_VCF');

		return $result;
	}

	/**
	 * Enables the test mode for the current object.
	 */
	public function setTestMode() {
		$this->testMode = true;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/pi1/class.tx_bzdstaffdirectory_pi1_frontEndVcfView.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzdstaffdirectory/pi1/class.tx_bzdstaffdirectory_pi1_frontEndVcfView.php']);
}
?>