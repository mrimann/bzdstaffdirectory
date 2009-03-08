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
 * Class 'frontEndDetailView for the 'bzdstaffdirectory' extension.
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Mario Rimann <mario@screenteam.com>
 */
class tx_bzdstaffdirectory_pi1_frontEndDetailView extends tx_bzdstaffdirectory_pi1_frontEndView {
	/**
	 * @var string path to this script relative to the extension dir
	 */
	public $scriptRelPath = 'pi1/class.tx_bzdstaffdirectory_pi1_frontEndDetailView.php';

	/**
	 * @var tx_bzdstaffdirectory_person the person for which we want to show the
	 *                          detail view.
	 */
	private $person = null;

	/**
	 * @var boolean true if the current object is in test mode, false otherwise
	 */
	private $testMode = false;

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

	public function setPerson($personUid) {
		$this->person = $this->createPerson($personUid);
	}

	/**
	 * Creates a person in $this->person.
	 *
	 * @param integer a person's UID, must be >= 0
	 */
	private function createPerson($personUid) {
		try {
			$person = tx_oelib_MapperRegistry::get('tx_bzdstaffdirectory_Mapper_Person')
					->find($personUid);
		} catch (tx_oelib_Exception_NotFound $exception) {
			$person = null;
		}

		return $person;
	}

	/**
	 * Creates a countdown to the next upcoming event.
	 *
	 * @return string HTML code of the countdown or a message if no upcoming
	 *                event found
	 */
	public function render() {
		// Set's the name and title markers
		$this->setMarker('first_name', $this->person->getFirstName());
		$this->setMarker('last_name', $this->person->getLastName());

		if ($this->person->hasTitle()) {
			$this->setMarker('title', $this->person->getTitle());
		} else {
			$this->hideSubparts('title', 'field_wrapper');
		}


		// Fills the markers of all the standard fields
		foreach($this->person->getStandardFieldList() as $key) {
			if ($this->person->hasStandardField($key)) {
				$this->setMarker($key, $this->person->getStandardField($key));
			} else {
				$this->hideSubparts($key, 'field_wrapper');
			}
		}

		// TODO: Use setOrDeletedMarkerIfNotEmpty() !!!

		if ($this->person->hasStandardField('email')) {
			$this->setMarker('email', $this->getEmail());
		} else {
			$this->hideSubparts('email', 'field_wrapper');
		}

		// Fills the markers for birth date or age depending on the configuration.
		if ($this->person->hasBirthDate()) {
			if (!$this->getConfValueBoolean('showAgeInsteadOfBirthdate', 's_detailview')) {
				$format = $this->getConfValueString('dateFormatBirthday');
				$format = (!empty($format)) ? $format : 'F Y';
				$this->setMarker('date_birthdate', $this->person->getBirthDate()->format($format));
			} else {
				$this->setMarker('date_birthdate', $this->person->getAge());
				$this->setMarker('label_date_birthdate', $this->translate('label_date_age'));
			}
		} else {
			$this->hideSubparts('date_birthdate', 'field_wrapper');
		}

		// Shows the date since when this person is in the company.
		if ($this->person->hasDateInCompany()) {
			$format = $this->getConfValueString('dateFormatInCompany');
			$format = (!empty($format)) ? $format : 'F Y';
			$this->setMarker('date_incompany', $this->person->getDateInCompany()->format($format));
		} else {
			$this->hideSubparts('date_incompany', 'field_wrapper');
		}

		// Shows a XING Icon that is linked to the person's XING profile, if
		// a URL to the profile was stored for this person.
		if ($this->person->HasXingProfile()) {
			$this->setMarker('xing', $this->getLinkedXingIcon());
		} else {
			$this->hideSubparts('xing', 'field_wrapper');
		}


		$result .= $this->getSubpart('TEMPLATE_DETAIL');

		$this->checkConfiguration();
		$result .= $this->getWrappedConfigCheckMessage();

		return $result;
	}

	/**
	 * Returns the HTML sourcecode to display a XING Icon that is linked to
	 * the persons personal XING profile. Result will be an empty string if that
	 * person has no URL for the profile in it's record - this must be checked
	 * before!!
	 *
	 * @return	string		HTML source for the linked icon, may be empty
	 */
	private function getLinkedXingIcon() {
		$url = $this->person->getXingProfileLink();

		if ($url == '') {
			return '';
		}

		$icon = 'http://www.xing.com/img/buttons/1_de_btn.gif';

		$result = '<a href="' . $url . '" target="_blank"><img src="' .
			$icon . '" width="85" height="23" alt="XING" border="0" /></a>';

		return $result;
	}

	/**
	 * Generates the E-mail address for the detail view. The return can be
	 * a plain-text address or wrapped in <a> tags.
	 *
	 * @return string the HTML code for displaying the email address (may be just plain-text)
	 */
	private function getEmail() {
		$emailArray = array();
		$result = '';
		$address = $this->person->getStandardField('email');

		switch($this->getConfValueString('spamProtectionMode', 's_detailview'))
		{
			case 'jsencrypted'	:	$emailArray = $this->getEmailJsEncrypted($address);
								break;
			case 'asimage'		:	$emailArray = $this->getEmailAsImage($address);
								break;
			case 'asimagejsencrypted':	$emailArray = $this->getEmailAsImage($address, true);
								break;
			case 'plain'		:
			default				:	$emailArray['display'] = $address;
								break;
		}
		$result = $emailArray['begin'] . $emailArray['display'] . $emailArray['end'];

		return $result;
	}

	/**
	 * Returns an image containing the provided e-mail address.
	 *
	 * @param boolean whether the image should include an encrypted link
	 *
	 * @return array associative array containing the information to fill the markers
	 */
	private function getEmailAsImage($includeEncryptedLink = false)	{
		$email = $this->person->getStandardField('email');

		$emailconf['image.']['file'] = 'GIFBUILDER';
		$emailconf['image.']['file.']['10'] = 'TEXT';
		$emailconf['image.']['file.']['10.']['text'] = $email;
		$emailconf['image.']['file.']['10.']['fontFile'] = 't3lib/fonts/vera.ttf';
		$emailconf['image.']['file.']['10.']['fontSize'] = '11';
		$emailconf['image.']['file.']['10.']['offset'] = '0, 14';
		$emailconf['image.']['file.']['10.']['nicetext'] = 1;
		$emailconf['image.']['file.']['XY'] ='[10.w]+1, [10.h]+4';

		$result['display'] = $this->cObj->IMAGE($emailconf['image.']);
		if ($includeEncryptedLink) {
			$encrypted = $this->getEmailJsEncrypted($email);
			$result['begin'] = $encrypted['begin'];
			$result['end'] = $encrypted['end'];
		} else {
			$result['begin'] = '';
			$result['end'] = '';
		}

		return $result;
	}

	/**
	 * Returns the person's email address with the default TYPO3-JavaScript-Encryption.
	 *
	 * @return array associative array containing the parts to fill the markers
	 */
	private function getEmailJsEncrypted()	{
		$email = $this->person->getStandardField('email');

		$mailto = $this->cObj->getMailTo($email,$email);
		$result = array();
		$result['display'] = $mailto[1];
		$result['begin'] = '<a href="'.$mailto[0].'">';
		$result['end'] = '</a>';

		return $result;
	}

	/**
	 * Enables the test mode for the current object.
	 */
	public function setTestMode() {
		$this->testMode = true;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/seminars/pi1/class.tx_bzdstaffdirectory_pi1_frontEndDetailView.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/seminars/pi1/class.tx_bzdstaffdirectory_pi1_frontEndDetailView.php']);
}
?>