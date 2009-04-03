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
		$this->setLabels();
		$this->setCSS();

		// Generates the person object and stores it in $this->person.
		$this->createPerson($personUid);


		// Sets the person's name as page title (mainly for indexing)
		$indexedPageTitle =
			($this->person->hasTitle()) ? $this->person->getTitle() . ' ' : '' .
			$this->person->getFirstName() .
			' ' .
			$this->person->getLastName();
		$GLOBALS['TSFE']->indexedDocTitle = $indexedPageTitle;
		$GLOBALS['TSFE']->page['title'] = $indexedPageTitle;
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

		// The image is shown in every case, the subpart will never be hidden.
		// If no image is stored for this user, a dummy picture will be shown.
		$this->setMarker('image', $this->getImage());


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

		// Shows the opinion of this person.
		if ($this->person->hasOpinion()) {
			$this->setMarker(
				'opinion',
				$this->pi_RTEcssText($this->person->getOpinion())
			);
		} else {
			$this->hideSubparts('opinion', 'field_wrapper');
		}

		// Shows the teams on which the person is member of.
		if ($this->person->hasTeams()) {
			$this->setMarker(
				'groups',
				$this->getTeamsAsList()
			);
		} else {
			$this->hideSubparts('groups', 'field_wrapper');

		}

		// Shows the location to which this user is assigned.
		if ($this->person->hasLocation()) {
			$this->setMarker(
				'location',
				$this->getLocationAsList()
			);
		} else {
			$this->hideSubparts('location', 'field_wrapper');
		}

		// Shows the files assigned to this person
		if ($this->person->hasFiles()) {
			$this->setMarker('files', $this->getFileList());
		} else {
			$this->hideSubparts('files', 'field_wrapper');
		}

		// Shows the back link if needed
		if ($this->weAreInPopUp) {
			// Render a "close" link as we are in a popUp
			$linkTag = '<a href="#" onClick="window.close()">'
				.$this->pi_getLL('label_link_close')
				.'</a>';
			$this->setMarker(
				'link_back',
				$linkTag
			);
		} else {
			if ($this->conf['backPid']) {
				$this->setMarker(
					'link_back',
					$this->cObj->getTypoLink(
						$this->pi_getLL('label_link_back'),
						$this->conf['backPid'])
					);
			} else {
				$this->hideSubparts('link_back', 'field_wrapper');
			}
		}


		$result .= $this->getSubpart('TEMPLATE_DETAIL');

		$this->checkConfiguration();
		$result .= $this->getWrappedConfigCheckMessage();

		return $result;
	}

	/**
	 * Returns the HTML code to show the image of the person. If the person has
	 * an image, this one is rendered. If person has no image assigned, a gender-
	 * specific dummy image is shown if the gender is set (otherwise a general
	 * dummy image is shown).
	 *
	 * @return string HTML code for the image
	 */
	function getImage() {
		$result = '';

		// Get Configuration Data (TypoScript Setup). Depending on "CODE" (what to show)
		$lconf = $this->conf['DETAIL.'];

		if ($this->person->hasImage()) {
			$lconf['image.']['file'] = 'uploads/tx_bzdstaffdirectory/' .
				$this->person->getImage();
		}

		if ($this->getConfValueBoolean('showDummyPictures', 's_template')) {
			switch($this->person->getGender())
			{
				case 2	:	$lconf['image.']['file'] = $this->getConfValueString('dummyPictureFemale', $sheet = 's_template', true);
									break;
				case 1	:	$lconf['image.']['file'] = $this->getConfValueString('dummyPictureMale', $sheet = 's_template', true);
									break;
				case 0	:	// The fallthrough is intended.
				default	:	// no gender specified or "not defined" is selected
									break;
			}

			// just set the unisex dummy image, if this is not forbidden in the setup
			if ($lconf['image.']['file'] == '' && $this->getConfValueBoolean('showUnisexDummyImage', 's_template')) {
				$lconf['image.']['file'] = $this->getConfValueString('dummyPictureDefault', $sheet = 's_template', true);
			}
		} else {
			//
		}

		// Depending on the settings in the Flexform of the content object, the image will be wrapped with a link (to click enlarge the image).
		$imageconf = array();
		if ($this->getConfValueBoolean('click_enlarge', 's_detailview') && $this->person->hasImage())	{
			// Render the pop-up image with the size limitations from TS Setup.
			$popUpImageArray = $this->cObj->getImgResource($lconf['image.']['file'], $lconf['image.']['popup.']);

			$imageconf['enable'] = 1;
			$imageconf['JSwindow'] = 1;
			$imageconf['wrap'] = '<a href="javascript: close();"> | </a>';

			$result = $this->cObj->imageLinkWrap($this->cObj->IMAGE($lconf['image.']),$popUpImageArray[3],$imageconf);

		} else	{
			$result = $this->cObj->IMAGE($lconf['image.']);
		}

		return $result;
	}

	/**
	 * Returns either a single file name or a list of file names and sets the
	 * appropriate label.
	 *
	 * @return string HTML to show the list of files
	 */
	private function getFileList() {
		$files = $this->person->getFiles();

		if (count($files) == 0) {
			return '';
		}

		if (count($files) > 1) {
			$fileList = '';
			foreach ($files as $currentFile) {
				$fileList .= '<li>' . $currentFile . '</li>';
			}
			$result = '<ul>' . $fileList . '</ul>';
			$this->setMarker('label_files', $this->translate('label_files_plural'));
		} else {
			$result = $files[0];
			$this->setMarker('label_files', $this->translate('label_files_singular'));
		}

		return $result;
	}

	/**
	 * Returns HTML source to display either a single entry or a whole list of
	 * teams.
	 *
	 * @return string HTML source for the team list, may be empty if no teams are assigned
	 */
	private function getTeamsAsList() {
		$result = '';
		$teams = $this->person->getTeams();

		// Exit if no teams are assigned
		if ($teams->count() == 0) {
			return $result;
		}

		if ($teams->count() > 1) {
			// we have more than one group and need to build a list
			while ($currentTeam = $teams->current()) {

				// check if the team name should be linked to the team page
				if ($currentTeam->hasInfopage()) {
					$teamName = $this->cObj->getTypoLink(
						htmlspecialchars($currentTeam->getTitle()),
						$currentTeam->getInfopagePid()
					);
				} else {
					$teamName = htmlspecialchars($currentTeam->getTitle());
				}
				$memberOfList .= '<li>' . $teamName . '</li>';
				$teams->next();
			}
			$result = '<ul>' . $memberOfList . '</ul>';
			$this->setMarker('label_groups', $this->translate('label_groups_plural'));
		} else {
			// just one single group found, no list is needed
			$currentTeam = $teams->current();

			// check if the team name should be linked to the team page
			if ($currentTeam->hasInfopage()) {
				$result = $this->cObj->getTypoLink(
					htmlspecialchars($currentTeam->getTitle()),
					$currentTeam->getInfopagePid()
				);
			} else {
				$result = htmlspecialchars($currentTeam->getTitle());
			}
			$this->setMarker('label_groups', $this->translate('label_groups_singular'));
		}

		return $result;
	}

	/**
	 * Returns HTML source to display either a single entry or a whole list of
	 * locations.
	 *
	 * @return string HTML source for the location list, may be empty if no locations are assigned
	 */
	private function getLocationAsList() {
		$result = '';
		$locations = $this->person->getLocations();

		// Exit if no locations are assigned
		if ($locations->count() == 0) {
			return $result;
		}

		if ($locations->count() > 1) {
			// we have more than one location and need to build a list
			while ($currentLocation = $locations->current()) {

				// check if the location name should be linked to the infopage of the location
				if ($currentLocation->hasInfopage()) {
					$locationName = $this->cObj->getTypoLink(
						htmlspecialchars($currentLocation->getTitle()),
						$currentLocation->getInfopagePid()
					);
				} else {
					$locationName = htmlspecialchars($currentLocation->getTitle());
				}
				$memberOfList .= '<li>' . $locationName . '</li>';
				$locations->next();
			}
			$result = '<ul>' . $memberOfList . '</ul>';
			$this->setMarker('label_location', $this->translate('label_location_plural'));
		} else {
			// just one single location found, no list is needed
			$currentLocation = $locations->current();

			// check if the location name should be linked to the infopage of the team
			if ($currentLocation->hasInfopage()) {
				$result = $this->cObj->getTypoLink(
					htmlspecialchars($currentLocation->getTitle()),
					$currentLocation->getInfopagePid()
				);
			} else {
				$result = htmlspecialchars($currentLocation->getTitle());
			}
			$this->setMarker('label_location', $this->translate('label_location_singular'));
		}

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