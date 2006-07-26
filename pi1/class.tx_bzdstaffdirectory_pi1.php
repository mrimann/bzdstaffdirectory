<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2006 Mario Rimann (typo3-coding@rimann.li)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Plugin 'BZD Staff Directory' for the 'bzd_staff_directory' extension.
 *
 * @author	Mario Rimann <typo3-coding@rimann.li>
 */


require_once(PATH_tslib.'class.tslib_pibase.php');

class tx_bzdstaffdirectory_pi1 extends tslib_pibase {
	var $prefixId = 'tx_bzdstaffdirectory_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_bzdstaffdirectory_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'bzd_staff_directory';	// The extension key.
	var $pi_checkCHash = TRUE;
	var $langArr;
	var $sys_language_mode;
	
	/**
	 * [Put your description here]
	 */
	function main($content,$conf)	{
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		// Init FlexForm configuration for plugin:
		$this->pi_initPIflexForm();
		$this->getTemplateCode();

		$this->arrConf = unserialize($GLOBALS["TYPO3_CONF_VARS"]["EXT"]["extConf"]['bzd_staff_directory']);



		// Define the path to the upload folder
		$this->uploadFolder = 'uploads/tx_bzdstaffdirectory/';

		// Deinfe the path to the media folder
		$this->mediaFolder = 'typo3conf/ext/bzd_staff_directory/media/';

		// include CSS in header of page
		if ($this->hasConfValueString('cssFile', 's_template_special')) {
			$GLOBALS['TSFE']->additionalHeaderData[] = '<style type="text/css">@import "'.$this->getConfValueString('cssFile', 's_template_special', true).'";</style>';
		}

		// load available syslanguages
		$this->initLanguages();
		// sys_language_mode defines what to do if the requested translation is not found
		$this->sys_language_mode = $this->conf['sys_language_mode']?$this->conf['sys_language_mode'] : $GLOBALS['TSFE']->sys_language_mode;

		// Get Listing-Type from Flexform-Settings
		$this->code = (string)strtoupper(trim($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'listtype','s_welcome')));
		
		// Get Configuration Data (TypoScript Setup). Depending on "CODE" (what to show)
		$this->lconf = $this->conf[$this->code."."];


		switch($this->code)
		{
			case "TEAMLIST"	:	$content .= $this->show_teamlist();
								break;
			case "BOX"		:	$content .= $this->show_box();
								break;
			case "DETAIL"	:	$content .= $this->show_detail();
								break;
			default			:	$content .= $this->pi_getLL('error_noListType');
								break;
		}
		return $this->pi_wrapInBaseClass($content);
	}




	/**
	 * Generates a teamlist. The teamleaders are shown on top of the list, then the rest of the team members follow.
	 * 
	 * @return	string		the complete HTML Output for this module
	 */
	function show_teamlist()	{
		// Define the team UID(s) that are selected in the flexform. This is a comma separated list if more than one UID.
		$team_uid = $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'usergroup','s_teamlist');

		// define the detail page (either from the global extension setting, or from the FlexForm).
		// FIXME: Change this configuration to either flexform or TS-Setup. No Settings in the Extension-Manager!
		if ($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'detailPage','s_teamlist') != '')	{
			$this->detailPage = $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'detailPage','s_teamlist');
		} else {
			$this->detailPage = $this->arrConf["InfoSite"];
		}

		// Define the sortOrder
		$teamListSortOrder = $this->getConfValueString('sortOrder', 's_teamlist');

		// Check if a detail page has been defined.
		if (!empty($this->detailPage)) {
			// create and display the list header
			$content = $this->createListHeader();

			if ($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'ignoreGroupSelection','s_teamlist')) {
				// Define the PID for the startingpoint
				$startingpoint = $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'startingpoint','s_teamlist');
	
				$teamMembersUIDArray = $this->getTeamMembersFromStartingpoint($startingpoint, $teamListSortOrder);

			} else {
				if (!empty($team_uid)) {
		
					// Select all teamleaders for the selected team(s).
					$res_leaders_mm = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'*',	// SELECT
						'tx_bzdstaffdirectory_groups_teamleaders_mm',	// FROM
						'uid_local IN('. $team_uid .')',	//WHERE
						'',	// GROUP BY
						'',	// ORDER BY
						''	//LIMIT
					);
		
					if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_leaders_mm) > 0) {
						// There's at least one leader for the selected team(s).
						$teamLeadersUIDArray = array();
						while($row_teamleader = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_leaders_mm)) {
							$content .= $this->showPersonInTeamList($row_teamleader['uid_foreign'], true);
							$teamLeadersUIDArray[] = $row_teamleader['uid_foreign'];
						}
					} else {
						// There's no group leader for the selected team(s).
					}
					// Select all members from the groups/persons MM table.
					$teamMembersUIDArray = $this->getTeamMembersFromMM($team_uid, $teamListSortOrder);
	
				} else {
					$content .= $this->pi_getLL('error_noGroupUID');
				}
	
			}

			if (count($teamMembersUIDArray) < 1) {
				// ERROR: There are no team members found for this/these team(s).
				// This can happen and won't be treated as an error at the moment (may be a team consists only of team leaders).
			} else {
				if (!is_array($teamLeadersUIDArray)) {
					// There are no team leaders (empty array), but there are team members:
	
					// Call the "output person record" function once per team member.
					foreach ($teamMembersUIDArray as $memberUID) {
						$content .= $this->showPersonInTeamList($memberUID, false);
					}
				} else {
					// There are team leaders!
	
					// Call the "output person record" function once per team member.
					foreach ($teamMembersUIDArray as $memberUID) {
						// Don't display this person in the team members section if it is a teamleader!
						if (!in_array($memberUID, $teamLeadersUIDArray)) {
							$content .= $this->showPersonInTeamList($memberUID, false);
						}
					}
				}
			}
			// add the table footer
			$content .= $this->createListFooter();
		} else {
			// no detail page defined
			$content .= $this->pi_getLL('error_noDetailPage');
		}

		return $content;
	}





	function show_box()	{
		$content = '';

		// get the UID of the person respective for this page
		$res_person_id = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'tx_bzdstaffdirectory_bzd_contact_person',	// SELECT
			'pages',	// FROM
			'uid = '.$GLOBALS["TSFE"]->id,	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			'1'	//LIMIT
		);

		// get the details of the contact person from the database
		$row_person_id = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_person_id);
		$person_uid = $row_person_id["tx_bzdstaffdirectory_bzd_contact_person"];
		$person = $this->getPersonDetails($person_uid);

		// check if fetching the person's details was successful
		if ($person) {
			// check whether a translation is requested or not
			if ($GLOBALS['TSFE']->sys_language_content) {
				// a translation is requested
				$OLmode = ($this->sys_language_mode == 'strict'?'hideNonTranslated':'');
				$translated_record = $GLOBALS['TSFE']->sys_page->getRecordOverlay('tx_bzdstaffdirectory_persons', $person,$GLOBALS['TSFE']->sys_language_content, $OLmode);
	
				//check if a valid translation is available
				if ($this->sys_language_mode != 'strict' OR !empty($translated_record['l18n_parent'])) {
					// found a valid translation, show the person with the translated information
					$content .= $this->showSinglePersonBox($translated_record);
				} else {
					$content .= $this->pi_getLL('error_contactPersonNotTranslated');
				}
			} else {
				// no translation requested
				$content .= $this->showSinglePersonBox($person);
			}
		} else {
			// $person is NULL
			$content .= $this->pi_getLL('error_personDetailsNotFetched');
		}

		return $content;
	}

	/**
	 * Generates the HTML Code to show a contact person box on the page
	 *
	 * @param	array		associative array containing all the information of a person record
	 *
	 * @return	string		the html code
	 */
	function showSinglePersonBox($person) {
		// Define the detail-Page (either from the global Extension-Setting, or from the FlexForm-Setting (only for this content-object)).
		if ($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'detailPage','s_contactbox') != '') {
			$this->detailPage = $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'detailPage','s_contactbox');
		} else {
			$this->detailPage = $this->arrConf["InfoSite"];
		}

		// define the header, will always be shown
		$this->setMarkerContent('header_contactperson', $this->pi_getLL('header_contactperson'));

		if ($this->hasValue('title', $person)) {
			$this->setMarkerContent('title', $this->getValue('title', $person, true));
			$this->setMarkerContent('label_title', $this->pi_getLL('label_title'));
		} else {
			$this->readSubpartsToHide('title', 'field_wrapper');
		}

		if ($this->hasValue('first_name', $person)) {
			$this->setMarkerContent('first_name', $this->getValue('first_name', $person, true));
			$this->setMarkerContent('label_first_name', $this->pi_getLL('label_first_name'));
		} else {
			$this->readSubpartsToHide('first_name', 'field_wrapper');
		}

		if ($this->hasValue('last_name', $person)) {
			$this->setMarkerContent('last_name', $this->getValue('last_name', $person, true));
			$this->setMarkerContent('label_last_name', $this->pi_getLL('label_last_name'));
		} else {
			$this->readSubpartsToHide('last_name', 'field_wrapper');
		}

		if ($this->hasValue('function', $person)) {
			$this->setMarkerContent('function', $this->getValue('function', $person, true));
			$this->setMarkerContent('label_function', $this->pi_getLL('label_function'));
		} else {
			$this->readSubpartsToHide('function', 'field_wrapper');
		}

		// define the marker for the image (always shown)
		$this->setMarkerContent('image', $this->getImage($person));

		// create the link to the detail page
		$linkParams = array(
			'tx_bzdstaffdirectory_pi1[showUid]' => $this->getValue('uid', $person),
			'tx_bzdstaffdirectory_pi1[backPid]' => $GLOBALS['TSFE']->id
		);
		$linkToDetailPage = $this->pi_linkTP($this->pi_getLL('label_link_detail'), $linkParams, true, $this->detailPage);
		$this->setMarkerContent('link_detail', $linkToDetailPage);
				
				// merge the marker content with the template
				$content .= $this->substituteMarkerArrayCached('TEMPLATE_BOX');
		return $content;
	}

	/**
	 * Initializes the detailed view for a certain person.
	 *
	 * @return	string		HTML Code to display
	 */
	function show_detail()	{
		$content = '';
		$this->backPid = intval($this->piVars['backPid']);
		$this->showUid = intval($this->piVars['showUid']);


		// exit this function if there's no UID transmitted, or if the transmitted
		// uid is not an integer of positive value within the URL (otherwise the SQL-query will fail)
		if (empty($this->showUid) OR $this->showUid < 0)	{
			$content .= $this->pi_getLL('error_noUID');
			return $content;
		}

		// get the details of the person
		$res_person = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons',	// FROM
			'uid = ' .$this->showUid. ' AND deleted = 0 AND hidden = 0',	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			'1'	//LIMIT
		);
		
		// Check if there's a person to display. Otherwise show an error message.
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_person) > 0 )	{
			$row_person = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_person);

			// get the translated record if the requested language is not the default language
			if ($GLOBALS['TSFE']->sys_language_content) {
				$OLmode = ($this->sys_language_mode == 'strict'?'hideNonTranslated':'');
				$translated_record = $GLOBALS['TSFE']->sys_page->getRecordOverlay('tx_bzdstaffdirectory_persons', $row_person,$GLOBALS['TSFE']->sys_language_content, $OLmode);
				if ($this->sys_language_mode != 'strict' OR !empty($translated_record['l18n_parent'])) {
					// found a valid translation, show the person with the translated information.
					$content .= $this->showSinglePerson($translated_record);
				} else {
					// There's an empty translation found (can only happen if sys_language_mode = strict).
					$content .= $this->pi_getLL('error_recordNotTranslated');
				}
			} else {
				// no translation requested or available - show the record in default language
				$content .= $this->showSinglePerson($row_person);
			}
		} else {
			// there's no person with this UID
			$content .= $this->pi_getLL('error_noPersonOnUID');
		}

		return $content;
	}

	/**
	 * Generates the HTML code to show a single person in single view.
	 *
	 * @param	array		associative array containing all details of the person
	 *
	 * @return	string		the HTML code
	 */
	function showSinglePerson($person) {
		// define all the markers
		if ($this->hasValue('first_name', $person)) {
			$this->setMarkerContent('first_name', $this->getValue('first_name', $person, true));
			$this->setMarkerContent('label_first_name', $this->pi_getLL('label_first_name'));
		} else {
			$this->readSubpartsToHide('first_name', 'field_wrapper');
		}

		if ($this->hasValue('last_name', $person)) {
			$this->setMarkerContent('last_name', $this->getValue('last_name', $person, true));
			$this->setMarkerContent('label_last_name', $this->pi_getLL('label_last_name'));
		} else {
			$this->readSubpartsToHide('last_name', 'field_wrapper');
		}

		if ($this->hasValue('function', $person)) {
			$this->setMarkerContent('function', $this->getValue('function', $person, true));
			$this->setMarkerContent('label_function', $this->pi_getLL('label_function'));
		} else {
			$this->readSubpartsToHide('function', 'field_wrapper');
		}

		if ($this->hasValue('location', $person)) {
			$this->setMarkerContent('location', $this->getValue('location', $person, true));
			$this->setMarkerContent('label_location', $this->pi_getLL('label_location'));
		} else {
			$this->readSubpartsToHide('location', 'field_wrapper');
		}

		if ($this->hasValue('phone', $person)) {
			$this->setMarkerContent('phone', $this->getValue('phone', $person, true));
			$this->setMarkerContent('label_phone', $this->pi_getLL('label_phone'));
		} else {
			$this->readSubpartsToHide('phone', 'field_wrapper');
		}

		if ($this->hasValue('tasks', $person)) {
			$this->setMarkerContent('tasks', $this->getValue('tasks', $person, true));
			$this->setMarkerContent('label_tasks', $this->pi_getLL('label_tasks'));
		} else {
			$this->readSubpartsToHide('tasks', 'field_wrapper');
		}

		if ($this->hasValue('opinion', $person)) {
			$this->setMarkerContent('opinion', $this->getValue('opinion', $person, true));
			$this->setMarkerContent('label_opinion', $this->pi_getLL('label_opinion'));
		} else {
			$this->readSubpartsToHide('opinion', 'field_wrapper');
		}

		if ($this->hasValue('room', $person)) {
			$this->setMarkerContent('room', $this->getValue('room', $person, true));
			$this->setMarkerContent('label_room', $this->pi_getLL('label_room'));
		} else {
			$this->readSubpartsToHide('room', 'field_wrapper');
		}

		if ($this->hasValue('officehours', $person)) {
			$this->setMarkerContent('officehours', $this->getValue('officehours', $person, true));
			$this->setMarkerContent('label_officehours', $this->pi_getLL('label_officehours'));
		} else {
			$this->readSubpartsToHide('officehours', 'field_wrapper');
		}

		if ($this->hasValue('title', $person)) {
			$this->setMarkerContent('title', $this->getValue('title', $person, true));
			$this->setMarkerContent('label_title', $this->pi_getLL('label_title'));
		} else {
			$this->readSubpartsToHide('title', 'field_wrapper');
		}

		if ($this->arrConf['useUniversalField_1'] && $this->hasValue('universal_field_1', $person)) {
			$this->setMarkerContent('universal_field_1', $this->getValue('universal_field_1', $person, true));
			$this->setMarkerContent('label_universal_field_1', $this->pi_getLL('label_universal_field_1'));
		} else {
			$this->readSubpartsToHide('universal_field_1', 'field_wrapper');
		}

		if ($this->arrConf['useUniversalField_2'] && $this->hasValue('universal_field_2', $person)) {
			$this->setMarkerContent('universal_field_2', $this->getValue('universal_field_2', $person, true));
			$this->setMarkerContent('label_universal_field_2', $this->pi_getLL('label_universal_field_2'));
		} else {
			$this->readSubpartsToHide('universal_field_2', 'field_wrapper');
		}

		if ($this->arrConf['useUniversalField_3'] && $this->hasValue('universal_field_3', $person)) {
			$this->setMarkerContent('universal_field_3', $this->getValue('universal_field_3', $person, true));
			$this->setMarkerContent('label_universal_field_3', $this->pi_getLL('label_universal_field_3'));
		} else {
			$this->readSubpartsToHide('universal_field_3', 'field_wrapper');
		}

		if ($this->arrConf['useUniversalField_4'] && $this->hasValue('universal_field_4', $person)) {
			$this->setMarkerContent('universal_field_4', $this->getValue('universal_field_4', $person, true));
			$this->setMarkerContent('label_universal_field_4', $this->pi_getLL('label_universal_field_4'));
		} else {
			$this->readSubpartsToHide('universal_field_4', 'field_wrapper');
		}

		if ($this->arrConf['useUniversalField_5'] && $this->hasValue('universal_field_5', $person)) {
			$this->setMarkerContent('universal_field_5', $this->getValue('universal_field_5', $person, true));
			$this->setMarkerContent('label_universal_field_5', $this->pi_getLL('label_universal_field_5'));
		} else {
			$this->readSubpartsToHide('universal_field_5', 'field_wrapper');
		}

		if ($this->hasValue('email', $person)) {
			$spamProtectionMode = $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'spamprotectionmode','s_detailview');
			$this->setMarkerContent('email', $this->getEmail($person, $spamProtectionMode));
			$this->setMarkerContent('label_email', $this->pi_getLL('label_email'));
		} else {
			$this->readSubpartsToHide('email', 'field_wrapper');
		}

		// Hide the groups line if the user is not member in any of the groups.
		if ($this->getMemberOfGroups($this->showUid)) {
			$this->setMarkerContent('groups', $this->getGroups());
		} else {
			$this->readSubpartsToHide('groups', 'field_wrapper');
		}

		if ($this->hasValue('date_incompany', $person)) {
			$this->setMarkerContent('date_incompany', $this->getFormattedDate($person['date_incompany']));
			$this->setMarkerContent('label_date_incompany', $this->pi_getLL('label_date_incompany'));
		} else {
			$this->readSubpartsToHide('date_incompany', 'field_wrapper');
		}

		if ($this->hasValue('files', $person)) {
			$this->setMarkerContent('files', $this->getFileList($person));
			$this->setMarkerContent('label_files', $this->pi_getLL('label_files'));
		} else {
			$this->readSubpartsToHide('files', 'field_wrapper');
		}

		// The image is shown in every case, the subpart will never be hidden.
		// If no image is stored for this user, a dummy picture will be shown.
		$this->setMarkerContent('image', $this->getImage($person));
		$this->setMarkerContent('label_image', $this->pi_getLL('label_image'));

		// define the marker for the back link
		// the link is only shown if $this->backPid is set!
		if ($this->backPid) {
			$this->setMarkerContent('link_back', $this->pi_linkTP($this->pi_getLL('label_link_back'), array(), true, $this->backPid));
		} else {
			$this->readSubpartsToHide('link_back', 'field_wrapper');
		}

		// define the person's name as the page title for indexing
		$personsTitle = ($this->hasValue('title', $person)) ? $this->getValue('title', $person) . ' ' : '';
		$indexedPageTitle =
			$personsTitle
			. $this->getValue('first_name', $person)
			. ' '
			. $this->getValue('last_name', $person);
		$GLOBALS['TSFE']->indexedDocTitle = $indexedPageTitle;

		// merge the marker content with the template
		$content .= $this->substituteMarkerArrayCached('TEMPLATE_DETAIL');

		return $content;
	}

	/**
	 * Returns a date formatted as a string.
	 * The date is formatted as given by the TS-Setup.
	 *
	 * @param	integer		the date as integer value
	 *
	 * @return	string		formatted date
	 */
	function getFormattedDate($dateInt) {
		$result = '';
		$result = strftime($this->getConfValueString('dateFormatYMD'), $dateInt);
		return $result;
	}

	/**
	 * Returns the HTML Code to show the list of files that are stored for this person.
	 *
	 * @param	array		associative array containing all the information
	 *
	 * @return	string		HTML Code
	 */
	function getFileList($person) {
		$files = explode(',', $person['files']);
		$fileList = '<ul>';
		foreach($files as $filename) {
// FIXME: Define the path in a global place!
			$fileList .= '<li><a href="uploads/tx_bzdstaffdirectory/'. $filename .'">' . $filename . '</a></li>';
		}
		$fileList .= '</ul>';

		return $fileList;
	}

	/**
	 * Returns the HTML Code needed to show a list of group names on which a user is a member.
	 *
	 * @return	string		HTML Code (unordered list if more than one group)
	 */
	function getGroups() {
		$result = '';
		$memberOf = $this->getMemberOfGroups($this->showUid);
		if (count($memberOf) > 1) {
			foreach ($memberOf as $actualGroupUID) {
				$actualGroup = $this->getTeamDetails($actualGroupUID);
				$memberOfList .= '<li>'. htmlspecialchars($actualGroup['group_name']) .'</li>';
			}
			
			$result = '<ul>' . $memberOfList . '</ul>';
			$this->setMarkerContent('label_groups', $this->pi_getLL('label_groups_plural'));
		} else {
			$actualGroup = $this->getTeamDetails($memberOf[0]);
			$result = htmlspecialchars($actualGroup['group_name']);
			$this->setMarkerContent('label_groups', $this->pi_getLL('label_groups_singular'));
		}

		return $result;
	}

	/**
	 * Returns the HTML Code to show the image of the person.
	 *
	 * @param	array		associative array containing all the information
	 *
	 * @return	string		the HTML Code
	 */
	function getImage($person) {
		$result = '';
		$fN = $this->getValue('image', $person);

		// Get Configuration Data (TypoScript Setup). Depending on "CODE" (what to show)
		$lconf = $this->conf[$this->code.'.'];

		if (empty($fN)) {
// FIXME: Define the path in a global place
			$lconf['image.']['file'] = 'typo3conf/ext/bzd_staff_directory/media/noimg.jpg';
		} else {
// FIXME: Define the paths in a global place
			$lconf['image.']['file'] = 'uploads/tx_bzdstaffdirectory/' . $fN;
		}

		// Depending on the settings in the Flexform of the content object, the image will be wrapped with a link (to click enlarge the image).
		$imageconf = array();
		if ( $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'click_enlarge','s_detailview') == TRUE AND $fN != '')	{
			$imageconf['enable'] = 1;
			$imageconf['JSwindow'] = 1;
			$imageconf['wrap'] = '<a href="javascript: close();"> | </a>';
			$result = $this->cObj->imageLinkWrap($this->cObj->IMAGE($lconf['image.']),$lconf['image.']['file'],$imageconf);
		} else	{
			$result = $this->cObj->IMAGE($lconf['image.']);
		}

		return $result;
	}

	/**
	 * Generates the E-mail address for the detail view.
	 *
	 * @param	array		associative array containing all the information
	 * @param	string		the mode selected in the configuration / flexform, may be empty
	 *
	 * @return	string		the HTML code for displaying the E-Mail address
	 */
	function getEmail($person, $spamProtectionMode = '') {
		$emailArray = array();
		$email = '';
		$address = $this->getValue('email', $person);

		// Output of the e-mail address depending on the settings from flexform (spam protection mode)
		switch($spamProtectionMode)
		{
			case "jsencrypted"	:	$emailArray = $this->email_jsencrypted($address);
								break;
			case "asimage"		:	$emailArray = $this->email_asimage($address);
								break;
			case "asimagejsencrypted":	$emailArray = $this->email_asimage($address, true);
								break;
			case "plain"		:	
			default				:	$emailArray['display'] = $address;
								break;
		}
		$email = $emailArray['begin'] . $emailArray['display'] . $emailArray['end'];

		return $email;
	}

	/**
	 * Converts an Array (which contains UIDs) to a comma separated string to use in DB queries.
	 * 
	 * @param	array	the UIDs
	 * 
	 * @return	string	the UIDs, comma separated
	 */
	function convertArrayToCommaseparatedString($inputArray) {
		$result = '';

		foreach ($inputArray as $uid) {
			$result .= ', ' . $uid;
		}

		return trim($result, ',');	
	}


	/**
	 * Queries the Database to select all details of a single person.
	 * If requested, it gets overlayed with a valid translation and given back as a translated record.
	 * 
	 * @param	integer		the uid of the person to fetch from the database
	 * @param	bolean		whether it should get translated or not, default is not to translate
	 *
	 * @return	array		associative array containing all the information, may be NULL
	 */
	function getPersonDetails($uid, $doTranslate = false) {
		$res_personDetails = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons',	// FROM
			'uid = ' . $uid .t3lib_pageSelect::enableFields('tx_bzdstaffdirectory_persons'),	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			'1'	//LIMIT
		);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_personDetails) > 0) {
			$person = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_personDetails);

			// get the translated record if the content language is not the default language
			if ($GLOBALS['TSFE']->sys_language_content && $doTranslate) {
				$OLmode = ($this->sys_language_mode == 'strict'?'hideNonTranslated':'');
				$translated_record = $GLOBALS['TSFE']->sys_page->getRecordOverlay('tx_bzdstaffdirectory_persons', $person,$GLOBALS['TSFE']->sys_language_content, $OLmode);
				if ($this->sys_language_mode != 'strict' OR !empty($translated_record['l18n_parent'])) {
					// found a valid translation, return the person with the translated information.
					$person = $translated_record;
				} else {
					// There's an empty translation found (can only happen if sys_language_mode = strict).
					// Act as if NO person could be retrieved from the database.
					$person = NULL;
				}
			} else {
				// no translation requested or available - return the record in default language
			}
		} else {
			$person = NULL;
		}

		return $person;
	}

	/**
	 * Gets all the persons from a given startingpoint.
	 * Used for the teamlist with active "ignoreGroupSelection" flag
	 *
	 * @param	string		comma separated list of PIDs
	 * @param	string		the sort order (a field name)
	 *
	 * @return	array		array of the persons uids
	 */
	function getTeamMembersFromStartingpoint($pidList, $sortOrder) {
		$groupMembers = array();

		$res_groupMembers = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'uid',	// SELECT
			'tx_bzdstaffdirectory_persons',	// FROM
			'pid IN(' . $pidList . ') AND l18n_parent = 0',	//WHERE
			'',	// GROUP BY
			$sortOrder,	// ORDER BY
			''	//LIMIT
		);
		while($member = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groupMembers))	{
			$groupMembers[] = $member['uid'];
		}


		return $groupMembers;
	}

	/**
	 * Gets all associated team members for a given team. The persons can be sorted by a given sort order.
	 * 
	 * @param	string		comma separated list of team UIDs to look for
	 * @param	string		field name used to order the records
	 * 
	 * @return	array		array of all member uids
	 */
	function getTeamMembersFromMM($team_uid, $sortOrder = '') {
		$groupMembers = array();
		$groupMembersSorted = array();
		
		$res_groupMembers = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons_usergroups_mm',	// FROM
			'uid_foreign IN(' . $team_uid .')',	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			''	//LIMIT
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_groupMembers) > 0)	{
			while($member = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groupMembers))	{
				$groupMembers[] = $member['uid_local'];
			}

			// Second call to the DB: get the right order!
			$groupMembersUIDList = $this->convertArrayToCommaseparatedString($groupMembers);
			$res_groupMembersSorted = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'uid',	// SELECT
				'tx_bzdstaffdirectory_persons',	// FROM
				'uid IN(' . $groupMembersUIDList . ') AND l18n_parent = 0',	//WHERE
				'',	// GROUP BY
				$sortOrder,	// ORDER BY
				''	//LIMIT
			);
			while($member = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groupMembersSorted))	{
				$groupMembersSorted[] = $member['uid'];
			}
		}
		return $groupMembersSorted;
	}


	/**
	 * Generates the HTML output for the list entry of exact one person.
	 * 
	 * @param	integer		the uid of the person to show
	 * @param	boolean		whether this person is a group leader or not
	 * 
	 * @return	string		the complete HTML for this persons entry in the list
	 */
	function showPersonInTeamList($uid, $isLeader = false)	{
		$result = '';
		$person = array();
		$showImage = $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'showimages','s_teamlist');

		// get the details of the actual person
		$person = $this->getPersonDetails($uid, true);
		if ($person) {
			if ($isLeader) {
				$this->setMarkerContent('class', 'tx_bzdstaffdirectory_teamlist_person, leader');
			} else {
				$this->setMarkerContent('class', 'tx_bzdstaffdirectory_teamlist_person');
			}

			if ($showImage) {
				// The image is shown in every case, the subpart will never be hidden.
				// If no image is stored for this user, a dummy picture will be shown.
				$this->setMarkerContent('image', $this->getImage($person));
			} else {
				$this->readSubpartsToHide('image', 'listitem_wrapper');
			}

			// define all the markers for this person
			if ($this->hasValue('title', $person)) {
				$this->setMarkerContent('list_title', $this->getValue('title', $person, true));
				$this->setMarkerContent('label_title', $this->pi_getLL('label_title'));
			} else {
				$this->readSubpartsToHide('title', 'listitem_wrapper');
			}

			if ($this->hasValue('first_name', $person)) {
				$this->setMarkerContent('first_name', $this->getValue('first_name', $person, true));
				$this->setMarkerContent('label_first_name', $this->pi_getLL('label_first_name'));
			} else {
				$this->readSubpartsToHide('first_name', 'listitem_wrapper');
			}

			if ($this->hasValue('last_name', $person)) {
				$this->setMarkerContent('last_name', $this->getValue('last_name', $person, true));
				$this->setMarkerContent('label_last_name', $this->pi_getLL('label_last_name'));
			} else {
				$this->readSubpartsToHide('last_name', 'listitem_wrapper');
			}

			if ($this->hasValue('function', $person)) {
				$this->setMarkerContent('function', $this->getValue('function', $person, true));
				$this->setMarkerContent('label_function', $this->pi_getLL('label_function'));
			} else {
				$this->readSubpartsToHide('function', 'listitem_wrapper');
			}

			if ($this->hasValue('phone', $person)) {
				$this->setMarkerContent('phone', $this->getValue('phone', $person, true));
				$this->setMarkerContent('label_phone', $this->pi_getLL('label_phone'));
			} else {
				$this->readSubpartsToHide('phone', 'listitem_wrapper');
			}

			if ($this->hasValue('email', $person)) {
				$spamProtectionMode = $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'spamprotectionmode','s_teamlist');
				$this->setMarkerContent('email', $this->getEmail($person, $spamProtectionMode));
				$this->setMarkerContent('label_email', $this->pi_getLL('label_email'));
			} else {
				$this->readSubpartsToHide('email', 'listitem_wrapper');
			}

			// create the link to the detail page
			$linkParams = array(
				'tx_bzdstaffdirectory_pi1[showUid]' => $this->getValue('uid', $person),
				'tx_bzdstaffdirectory_pi1[backPid]' => $GLOBALS['TSFE']->id
			);
			$linkToDetailPage = $this->pi_linkTP($this->pi_getLL('label_link_detail'), $linkParams, true, $this->detailPage);
			$this->setMarkerContent('link_detail', $linkToDetailPage);

			// merge the marker content with the template
			$result .= $this->substituteMarkerArrayCached('LIST_ITEM');

			// reset the hidden subparts (may be they are needed in the next row)
			$this->subpartsToHide = array();
		}

		return $result;
	}



	/**
	 * Returns an image containing the provided e-mail address
	 * 
	 * @param	string		the e-mail address to protect
	 * @param	boolean		whether the image should include an encrypted link
	 * 
	 * @return	array		associative array containing the infos to fill the markers
	 */
	function email_asimage($email, $includeEncryptedLink = false)	{
		$emailconf["image."]["file"] = 'GIFBUILDER';
		$emailconf["image."]["file."]["10"] = 'TEXT';
		$emailconf["image."]["file."]["10."]["text"] = $email;
// FIXME: Make Font, Fontsize etc. configurable via Flexform!!
		$emailconf["image."]["file."]["10."]["fontFile"] = 't3lib/fonts/verdana.ttf';
		$emailconf["image."]["file."]["10."]["fontSize"] = '11';
		$emailconf["image."]["file."]["10."]["offset"] = '0, 14';
		$emailconf["image."]["file."]["10."]["nicetext"] = 1;
		$emailconf["image."]["file."]["XY"] ='[10.w]+1, [10.h]+4';

		$result['display'] = $this->cObj->IMAGE($emailconf['image.']);
		if ($includeEncryptedLink) {
			$encrypted = $this->email_jsencrypted($email);
			$result['begin'] = $encrypted['begin'];
			$result['end'] = $encrypted['end'];
		} else {
			$result['begin'] = '';
			$result['end'] = '';
		}
		
		return $result;
	}


	/**
	 * Returns the provided e-mail address encrypted with the default
	 * TYPO3-JavaScript-Encryption.
	 * 
	 * @param	string		the e-mail address to protect
	 * 
	 * @return	array		associative array containing the parts to fill the markers
	 */
	function email_jsencrypted($email)	{
		$mailto = $this->cObj->getMailTo($email,$email);
		$result = array();
		$result['display'] = $mailto[1];
		$result['begin'] = '<a href="'.$mailto[0].'">';
		$result['end'] = '</a>';

		return $result;
	}


	/**
	 * Queries the database and gets all details on the selected groups/teams.
	 * 
	 * @param	integer		the UID of the team to select
	 * 
	 * @return	array		all the fields of the selected team
	 */
	function getTeamDetails($uid) {
		$res_groupDetails = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_groups',	// FROM
			'uid = ' . $uid .t3lib_pageSelect::enableFields('tx_bzdstaffdirectory_groups'),	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			'1'	//LIMIT
		);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_groupDetails) > 0) {
			$group = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groupDetails);
		} else {
			$group = NULL;
		}

		return $group;
	}

	/**
	 * Returns an array containing team UIDs of which the provided person is memberOf.
	 * 
	 * @param	integer		UID of the person to search for
	 * 
	 * @return	array		containing team UIDs
	 */
	function getMemberOfGroups($uid) {
		$groups = array();
		
		$res_groups = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons_usergroups_mm',	// FROM
			'uid_local IN(' . $uid .')',	//WHERE
			'',	// GROUP BY
			'sorting',	// ORDER BY
			''	//LIMIT
		);

		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_groups) > 0)	{
			while($member = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_groups))	{
				$groups[] = $member['uid_foreign'];
			}
		}
		return $groups;
	}

	/**
	 * Gets a value from flexforms or TS setup.
	 * The priority lies on flexforms; if nothing is found there, the value
	 * from TS setup is returned. If there is no field with that name in TS setup,
	 * an empty string is returned.
	 *
	 * @param	string		field name to extract
	 * @param	string		sheet pointer, eg. "sDEF"
	 * @param	string		whether this is a filename, which has to be combined with a path
	 *
	 * @return	string		the value of the corresponding flexforms or TS setup entry (may be empty)
	 *
	 * @access	private
	 */
	function getConfValue($fieldName, $sheet = 'sDEF', $isFileName = false) {
		$flexformsValue = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], $fieldName, $sheet);
		if ($isFileName && !empty($flexformsValue)) {
			$flexformsValue = $this->addPathToFileName($flexformsValue);
		}
		$confValue = isset($this->conf[$fieldName]) ? $this->conf[$fieldName] : '';

		return ($flexformsValue) ? $flexformsValue : $confValue;
	}

	/**
	 * Gets a trimmed string value from flexforms or TS setup.
	 * The priority lies on flexforms; if nothing is found there, the value
	 * from TS setup is returned. If there is no field with that name in TS setup,
	 * an empty string is returned.
	 *
	 * @param	string		field name to extract
	 * @param	string		sheet pointer, eg. "sDEF"
	 * @param	string		whether this is a filename, which has to be combined with a path
	 *
	 * @return	string		the trimmed value of the corresponding flexforms or TS setup entry (may be empty)
	 *
	 * @access	protected
	 */
	function getConfValueString($fieldName, $sheet = 'sDEF', $isFileName = false) {
		return trim($this->getConfValue($fieldName, $sheet, $isFileName));
	}

	/**
	 * Checks whether a string value from flexforms or TS setup is set.
	 * The priority lies on flexforms; if nothing is found there, the value
	 * from TS setup is checked. If there is no field with that name in TS setup,
	 * false is returned.
	 *
	 * @param	string		field name to extract
	 * @param	string		sheet pointer, eg. "sDEF"
	 *
	 * @return	boolean		whether there is a non-empty value in the corresponding flexforms or TS setup entry
	 *
	 * @access	protected
	 */
	function hasConfValueString($fieldName, $sheet = 'sDEF') {
		return ($this->getConfValueString($fieldName, $sheet) != '');
	}

	/**
	 * Gets an integer value from flexforms or TS setup.
	 * The priority lies on flexforms; if nothing is found there, the value
	 * from TS setup is returned. If there is no field with that name in TS setup,
	 * zero is returned.
	 *
	 * @param	string		field name to extract
	 * @param	string		sheet pointer, eg. "sDEF"
	 *
	 * @return	integer		the inval'ed value of the corresponding flexforms or TS setup entry
	 *
	 * @access	protected
	 */
	function getConfValueInteger($fieldName, $sheet = 'sDEF') {
		return intval($this->getConfValue($fieldName, $sheet));
	}

	/**
	 * Checks whether an integer value from flexforms or TS setup is set and non-zero.
	 * The priority lies on flexforms; if nothing is found there, the value
	 * from TS setup is checked. If there is no field with that name in TS setup,
	 * false is returned.
	 *
	 * @param	string		field name to extract
	 * @param	string		sheet pointer, eg. "sDEF"
	 *
	 * @return	boolean		whether there is a non-zero value in the corresponding flexforms or TS setup entry
	 *
	 * @access	protected
	 */
	function hasConfValueInteger($fieldName, $sheet = 'sDEF') {
		return (boolean) $this->getConfValueInteger($fieldName, $sheet);
	}

	/**
	 * Gets a boolean value from flexforms or TS setup.
	 * The priority lies on flexforms; if nothing is found there, the value
	 * from TS setup is returned. If there is no field with that name in TS setup,
	 * false is returned.
	 *
	 * @param	string		field name to extract
	 * @param	string		sheet pointer, eg. "sDEF"
	 *
	 * @return	boolean		the boolean value of the corresponding flexforms or TS setup entry
	 *
	 * @access	protected
	 */
	function getConfValueBoolean($fieldName, $sheet = 'sDEF') {
		return (boolean) $this->getConfValue($fieldName, $sheet);
	}

	/**
	 * fills the internal array '$this->langArr' with the available syslanguages
	 *
	 * @return	void
	 */
	function initLanguages () {

		$lres = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',
			'sys_language',
			'1=1' . $this->cObj->enableFields('sys_language'));


		$this->langArr = array();
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($lres)) {
			$this->langArr[$row['uid']] = $row;
		}

		return;
	}

	/**
	 * Sets a marker's content.
	 *
	 * Example: If the prefix is "field" and the marker name is "one", the marker
	 * "###FIELD_ONE###" will be written.
	 *
	 * If the prefix is empty and the marker name is "one", the marker
	 * "###ONE###" will be written.
	 *
	 * @param	string		the marker's name without the ### signs, case-insensitive, will get uppercased, must not be empty
	 * @param	string		the marker's content, may be empty
	 * @param	string		prefix to the marker name (may be empty, case-insensitive, will get uppercased)
	 *
	 * @access	protected
	 */
	function setMarkerContent($markerName, $content, $prefix = '') {
		$this->markers[$this->createMarkerName($markerName, $prefix)] = $content;

		return;
	}

	/**
	 * Takes a comma-separated list of subpart names and writes them to $this->subpartsToHide.
	 * In the process, the names are changed from 'aname' to '###BLA_ANAME###' and used as keys.
	 * The corresponding values in the array are empty strings.
	 *
	 * Example: If the prefix is "field" and the list is "one,two", the array keys
	 * "###FIELD_ONE###" and "###FIELD_TWO###" will be written.
	 *
	 * If the prefix is empty and the list is "one,two", the array keys
	 * "###ONE###" and "###TWO###" will be written.
	 *
	 * @param	string		comma-separated list of at least 1 subpart name to hide (case-insensitive, will get uppercased)
	 * @param	string		prefix to the subpart names (may be empty, case-insensitive, will get uppercased)
	 *
	 * @access	protected
	 */
	function readSubpartsToHide($subparts, $prefix = '') {
		$subpartNames = explode(',', $subparts);

		foreach ($subpartNames as $currentSubpartName) {
			$this->subpartsToHide[$this->createMarkerName($currentSubpartName, $prefix)] = '';
		}

		return;
	}

	/**
	 * Creates an uppercase marker (or subpart) name from a given name and an optional prefix.
	 *
	 * Example: If the prefix is "field" and the marker name is "one", the result will be
	 * "###FIELD_ONE###".
	 *
	 * If the prefix is empty and the marker name is "one", the result will be "###ONE###".
	 *
	 * @param	string		the name of the marker, case insensitive (will be uppercased), must not be empty
	 * @param	string		the prefix, case insensitive (will be uppercased), may be empty
	 *
	 * @access	private
	 */
	function createMarkerName($markerName, $prefix = '') {
		// if a prefix is provided, uppercase it and separate it with an underscore
		if ($prefix) {
			$prefix = strtoupper($prefix).'_';
		}

		return '###'.$prefix.strtoupper(trim($markerName)).'###';
	}

	/**
	 * Multi substitution function with caching. Wrapper function for cObj->substituteMarkerArrayCached(),
	 * using $this->markers and $this->subparts as defaults.
	 *
	 * During the process, the following happens:
	 * 1. $this->subpartsTohide will be removed
	 * 2. for the other subparts, the subpart marker comments will be removed
	 * 3. markes are replaced with their corresponding contents.
	 *
	 * @param	string		key of the subpart from $this->templateCache, e.g. 'LIST_ITEM' (without the ###)
	 *
	 * @return	string		content stream with the markers replaced
	 *
	 * @access	protected
	 */
	function substituteMarkerArrayCached($key) {
		// remove subparts (lines) that will be hidden
		$noHiddenSubparts = $this->cObj->substituteMarkerArrayCached($this->templateCache[$key], array(), $this->subpartsToHide);

		// remove subpart markers by replacing the subparts with just their content
		$noSubpartMarkers = $this->cObj->substituteMarkerArrayCached($noHiddenSubparts, array(), $this->templateCache);

		// replace markers with their content
		return $this->cObj->substituteMarkerArrayCached($noSubpartMarkers, $this->markers);
	}

	/**
	 * Retrieves all subparts from the plugin template and write them to $this->templateCache.
	 *
	 * The subpart names are automatically retrieved from the template file set in $this->conf['templateFile']
	 * (or via flexforms) and are used as array keys. For this, the ### are removed, but the names stay uppercase.
	 *
	 * Example: The subpart ###MY_SUBPART### will be stored with the array key 'MY_SUBPART'.
	 *
	 * Please note that each subpart may only occur once in the template file.
	 *
	 * @access	protected
	 */
	function getTemplateCode() {
		/** the whole template file as a string */
		$templateRawCode = $this->cObj->fileResource($this->getConfValueString('templateFile', 's_template', true));
		$this->markerNames = $this->findMarkers($templateRawCode);

		$subpartNames = $this->findSubparts($templateRawCode);

		foreach ($subpartNames as $currentSubpartName) {
			$this->templateCache[$currentSubpartName] = $this->cObj->getSubpart($templateRawCode, $currentSubpartName);
		}
		return;
	}

	/**
	 * Finds all subparts within a template.
	 * The subparts must be within HTML comments.
	 *
	 * @param	string		the whole template file as a string
	 *
	 * @return	array		a list of the subpart names (uppercase, without ###, e.g. 'MY_SUBPART')
	 *
	 * @access	protected
	 */
	function findSubparts($templateRawCode) {
		$matches = array();
		preg_match_all('/<!-- *(###)([^#]+)(###)/', $templateRawCode, $matches);

		return array_unique($matches[2]);
	}

	/**
	 * Finds all markers within a template.
	 * Note: This also finds subpart names.
	 *
	 * The result is one long string that is easy to process using regular expressions.
	 *
	 * Example: If the markers ###FOO### and ###BAR### are found, the string "#FOO#BAR#" would be returned.
	 *
	 * @param	string		the whole template file as a string
	 *
	 * @return	string		a list of markes as one long string, separated, prefixed and postfixed by '#'
	 *
	 * @access	private
	 */
	function findMarkers($templateRawCode) {
		$matches = array();
		preg_match_all('/(###)([^#]+)(###)/', $templateRawCode, $matches);

		$markerNames = array_unique($matches[2]);

		return '#'.implode('#', $markerNames).'#';
	}

	/**
	 * Checks whether a given person record has a certain field set.
	 *
	 * @param	string		field name to check
	 * @param	array		associative array containing all the information
	 *
	 * @returm	boolean		the answer
	 */
	function hasValue($key, $person) {
		$result = false;

		if (!empty($person[$key])) {
			$result = true;
		}

		return $result;
	}

	/**
	 * Returns the value of a field contained in an array.
	 * The result can optionally be htmlspecialchars'ed.
	 *
	 * @param	string		field name
	 * @param	array		associative array containing all the information
	 * @param	boolean		whether the string should be htmlspecialchars'ed befor beeing returned
	 *
	 * @return	string		the field value
	 */
	function getValue($key, $person, $doHtmlSpecialChars = false) {
		$result = '';
		if ($doHtmlSpecialChars) {
			$result = htmlspecialchars($person[$key]);
		} else {
			$result = $person[$key];
		}
		
		return $result;
	}

	/**
	 * Returns the list view header: Start of table, header row, start of table body.
	 * Columns listed in $this->subpartsToHide are hidden (ie. not displayed).
	 *
	 * @return	string		HTML output, the table header
	 *
	 * @access	protected
	 */
	function createListHeader() {
		$result = $this->substituteMarkerArrayCached('LIST_HEADER');

		return $result;
	}

	/**
	 * Returns the list view footer: end of table body, end of table.
	 *
	 * Columns listed in $this->subpartsToHide are hidden (ie. not displayed).
	 *
	 * @return	string		HTML output, the table header
	 *
	 * @access	protected
	 */
	function createListFooter() {
		$result = $this->substituteMarkerArrayCached('LIST_FOOTER');

		return $result;
	}

	/**
	 * Adds a path in front of the file name.
	 * This is used for files that are selected in the Flexform of the front end plugin.
	 *
	 * If no path is provided, the default (uploads/[extension_name]/) is used as path.
	 *
	 * An example (default, with no path provided):
	 * If the file is named 'template.tmpl', the output will be 'uploads/[extension_name]/template.tmpl'.
	 * The '[extension_name]' will be replaced by the name of the calling extension.
	 *
	 * @param	string		the file name
	 * @param	string		the path to the file (without filename), must contain a slash at the end, may contain a slash at the beginning (if not relative)
	 *
	 * @return	string		the complete path including file name
	 * 
	 * @access	protected
	 */
	function addPathToFileName($fileName, $path = '') {
		if (empty($path)) {
			$path = 'uploads/tx_bzdstaffdirectory/';
		}

		return $path.$fileName;
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzd_staff_directory/pi1/class.tx_bzdstaffdirectory_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzd_staff_directory/pi1/class.tx_bzdstaffdirectory_pi1.php']);
}

?>
