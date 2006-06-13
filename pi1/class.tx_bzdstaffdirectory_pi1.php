<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005 Mario Rimann (mario.rimann@bbzdietikon.ch)
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
 * @author	Mario Rimann <mario.rimann@bbzdietikon.ch>
 */


require_once(PATH_tslib.'class.tslib_pibase.php');

class tx_bzdstaffdirectory_pi1 extends tslib_pibase {
	var $prefixId = 'tx_bzdstaffdirectory_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_bzdstaffdirectory_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey = 'bzd_staff_directory';	// The extension key.
	var $pi_checkCHash = TRUE;
	
	/**
	 * [Put your description here]
	 */
	function main($content,$conf)	{

	
		$this->conf=$conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();

		$this->arrConf = unserialize($GLOBALS["TYPO3_CONF_VARS"]["EXT"]["extConf"]['bzd_staff_directory']);

		// Init FlexForm configuration for plugin:
		$this->pi_initPIflexForm();

		// Define the path to the upload folder
		$this->uploadFolder = 'uploads/tx_bzdstaffdirectory/';

		// Deinfe the path to the media folder
		$this->mediaFolder = 'typo3conf/ext/bzd_staff_directory/media/';

		// include CSS in header of page
		if ($this->hasConfValueString('cssFile', 's_template_special')) {
			$GLOBALS['TSFE']->additionalHeaderData[] = '<style type="text/css">@import "'.$this->getConfValueString('cssFile', 's_template_special', true).'";</style>';
		}


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
			default			:	$content .= "tx_bzdstaffdirectory / no List-Type defined";
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

		// Define the detail-Page (either from the global Extension-Setting, or from the FlexForm-Setting (only for this content-object)).
		// FIXME: Change this configuration to either flexform or TS-Setup. No Settings in the Extension-Manager!
		if ($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'detailpage','s_teamlist') != '')	{
			$this->detailpage = $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'detailpage','s_teamlist');
		} else {
			$this->detailpage = $this->arrConf["InfoSite"];
		}

		// Before getting the records, get the startingpoint. Either from the actual page, or from a given startingpoint
		if ($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'startingpoint','s_teamlist') != '')	{
			$startingpoint = intval($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'startingpoint','s_teamlist'));
		}
		else	{
			$startingpoint = intval($GLOBALS["TSFE"]->id);
		}

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
			// There's no group leader for this team(s).
		}

		// Select all members from the groups/persons MM table.
		$teamListSortOrder = $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'sortOrder','s_teamlist');;
		$teamMembersUIDArray = $this->getTeamMembersFromMM($team_uid, $teamListSortOrder);

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
	return '<table class="tx_bzdstaffdirectory_teamlist">' . $content . '</table>';
	}





	function show_box()	{
	
		$content = '';
//		$content .= "BOX output<br>";

//		$content .= $GLOBALS["TSFE"]->id;


		// Get the UID of the person respective for this page
		$res_person_id = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'tx_bzdstaffdirectory_bzd_contact_person',	// SELECT
			'pages',	// FROM
			'uid = '.$GLOBALS["TSFE"]->id,	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			'1'	//LIMIT
		);

		while($row_person_id = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_person_id))	{

			// Get the details of the person
			$res_person = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',	// SELECT
				'tx_bzdstaffdirectory_persons',	// FROM
				'uid = '.$row_person_id["tx_bzdstaffdirectory_bzd_contact_person"] . t3lib_pageSelect::enableFields('tx_bzdstaffdirectory_persons'),	//WHERE
				'',	// GROUP BY
				'',	// ORDER BY
				'1'	//LIMIT
			);

			while($row_person = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_person))	{

				// Define the detail-Page (either from the global Extension-Setting, or from the FlexForm-Setting (only for this content-object)).
				if ($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'detailpage','s_teamlist') != '') {
					$detailpage = $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'detailpage','s_teamlist');
				} else {
					$detailpage = $this->arrConf["InfoSite"];
				}


				$template = $this->getTemplateCode();
				$arrMarker["###FIRST_NAME###"] = $row_person["first_name"];
				$arrMarker["###LAST_NAME###"] = $row_person["last_name"];
				$arrMarker["###FUNCTION###"] = $row_person["function"];


				$lconf = $this->conf[$this->code."."];
				if ($row_person["image"] == "")	{
					$lconf["image."]["file"] = "typo3conf/ext/bzd_staff_directory/media/noimg.jpg";
				}
				else	{
					$lconf["image."]["file"] = "uploads/tx_bzdstaffdirectory/" . $row_person["image"];
				}

				$arrMarker["###IMAGE###"] = $this->cObj->IMAGE($lconf["image."]);
				
				$arrWrappedSubpart["###LINK_DETAIL###"]= array('<A href="'. $this->pi_linkTP_keepPIvars_url(array('showUid' => $row_person_id["tx_bzdstaffdirectory_bzd_contact_person"], 'backId' => $GLOBALS["TSFE"]->id), 0, 1, $detailpage) .'">','</A>');

				$content.=$this->cObj->substituteMarkerArrayCached($template[$this->code],$arrMarker,array(),$arrWrappedSubpart);
			}


		}


	return $content;
	}









	function show_detail()	{
	
		$content = '';
		$showUid = $this->piVars['showUid'];


		// exit this function if there's no UID transmitted, or if the transmitted
		// uid is not an integer of positive value within the URL (otherwise the SQL-Query will fail)
		if (empty($showUid) OR !is_numeric($showUid) OR $showUid < 0)	{
			$content .= 'Error: No UID to display (maybe you called this page directly instead of another way, ...)<br>';
			$content .= 'Or the transmitted Uid is not an integer of positive value.';
			return $content;
		}


		// Get the Details of the person
		$res_person = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons',	// FROM
			'uid = '.$showUid.' AND deleted = 0 AND hidden = 0',	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			'1'	//LIMIT
		);
		
		// Check if there's a person to display - if not: show error message and exit.
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_person) < 1 )	{
			$content .= 'There is no person with this UID to display.';
			return $content;
		}
		while($row_person = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_person))	{


			// Get Configuration Data (TypoScript Setup). Depending on "CODE" (what to show)
			$lconf = $this->conf[$this->code."."];


			if (empty($row_person['image']))
				{
// FIXME: Define the path in a global place
					$lconf['image.']["file"] = "typo3conf/ext/bzd_staff_directory/media/noimg.jpg";
				}
				else
				{
// FIXME: Define the paths in a global place
					$lconf['image.']['file'] = 'uploads/tx_bzdstaffdirectory/' . $row_person['image'];
				}



			$template = $this->getTemplateCode();
			$arrMarker["###FIRST_NAME###"] = $row_person["first_name"];
			$arrMarker["###LAST_NAME###"] = $row_person["last_name"];
			$arrMarker["###FUNCTION###"] = $row_person["function"];
			$arrMarker["###LOCATION###"] = $row_person["location"];
			$arrMarker["###PHONE###"] = $row_person["phone"];
			$arrMarker["###TASKS###"] = $row_person["tasks"];
			$arrMarker["###OPINION###"] = $row_person["opinion"];
			$arrMarker["###ROOM###"] = $row_person["room"];
			$arrMarker["###OFFICEHOURS###"] = $row_person["officehours"];



			// Output of the e-mail address depending on the settings from flexform (spam protection mode)
			switch($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'spamprotectionmode','s_detailview'))
			{
				case "jsencrypted"	:	$emailArray = $this->email_jsencrypted($row_person["email"]);
									break;
				case "asimage"		:	$emailArray = $this->email_asimage($row_person["email"], false);
									break;
				case "asimagejsencrypted":	$emailArray = $this->email_asimage($row_person["email"], true);
									break;
				case "plain"		:	
				default				:	$emailArray['display'] = $row_person['email'];
										$emailArray['begin'] = '<a href="mailto:'.$row_person["email"].'">';
										$emailArray['end'] = '</a>';
									break;
			}
			$arrMarker['###EMAIL###'] = $emailArray['display'];
			$arrWrappedSubpart['###LINK_EMAIL###'] = array($emailArray['begin'],$emailArray['end']);



			// Depending on the settings in the Flexform of the content object, the image will be wrapped with a link (to click enlarge the image).
			$conf = array();
			if ( $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'click_enlarge','s_detailview') == TRUE AND $row_person["image"] != '')	{
				$conf["enable"] = 1;
				$conf["JSwindow"] = 1;
				$conf['wrap'] = '<a href="javascript: close();"> | </a>';
				$arrMarker["###IMAGE###"] = $this->cObj->imageLinkWrap($this->cObj->IMAGE($lconf["image."]),$lconf["image."]["file"],$conf);
			} else	{
				$arrMarker["###IMAGE###"] = $this->cObj->IMAGE($lconf["image."]);
			}


			$memberOf = $this->getMemberOfGroups($showUid);

			if ($memberOf) {
				foreach ($memberOf as $actualGroupUID) {
					$actualGroup = $this->getTeamDetails($actualGroupUID);
					$memberOfList .= '<li>'. $actualGroup['group_name'] .'</li>';
				}
			}
			$arrMarker["###GROUPS###"] = '<ul>'.$memberOfList.'</ul>';


			// Display all the files that are stored for this person
			if (!empty($row_person['files'])) {
				$files = explode(',', $row_person['files']);
				$file_list = '<ul>';
				foreach($files as $filename) {
// FIXME: Define the path in a global place!
					$file_list .= '<li><a href="uploads/tx_bzdstaffdirectory/'. $filename .'">' . $filename . '</a></li>';
				}
				$file_list .= '</ul>';

				$arrMarker['###FILES###'] = $file_list;
			} else {
				$arrMarker['###FILES###'] = '';
			}



			// defining the Back-Link (to travel from the detail-page back to the referring page)
			$arrWrappedSubpart["###LINK_BACK###"] = array('<A href="'. $this->pi_linkTP_keepPIvars_url(array(), 0, 1, $this->piVars["backId"]) .'">','</A>');



			$content.=$this->cObj->substituteMarkerArrayCached($template[$this->code],$arrMarker,array(),$arrWrappedSubpart);


		}

	return $content;
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
	 * Queries the Database to select all details of ALL Members that are provided as an array.
	 * 
	 * @param	array		containing all UIDs of the members to select
	 */
	function getPersonsDetails($uid) {
		$res_membersDetails = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
			'*',	// SELECT
			'tx_bzdstaffdirectory_persons',	// FROM
			'uid = ' . $uid .t3lib_pageSelect::enableFields('tx_bzdstaffdirectory_persons'),	//WHERE
			'',	// GROUP BY
			'',	// ORDER BY
			'1'	//LIMIT
		);
		if ($GLOBALS['TYPO3_DB']->sql_num_rows($res_membersDetails) > 0) {
			$person = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res_membersDetails);
		} else {
			$person = NULL;
		}

		return $person;
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
				'uid IN(' . $groupMembersUIDList . ')',	//WHERE
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
/*		$result .= 'uid: ' . $uid . ' ';
		$result .= ($isLeader) ? 'is TeamLeader' : '';
		$result .= '<br>';
*/
		$actual_person = $this->getPersonsDetails($uid);
		if ($actual_person) {
			// show images or not (depending on settings of the plugin)
			if ($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'showimages','s_teamlist') == TRUE) {
				if ($actual_person["image"] == "") {
// FIXME: Define the path in a global place
					$this->lconf["image."]["file"] = $this->mediaFolder.'noimg.jpg';
				} else {
					$this->lconf["image."]["file"] = $this->uploadFolder . $actual_person["image"];
				}
				$arrMarker['###IMAGE###'] = $this->cObj->IMAGE($this->lconf['image.']);
			} else {
				$arrMarker['###IMAGE###'] = ''; 
			}
	
			// reading the template, filling the markers and then output the mixture
			$template = $this->getTemplateCode();
	
			$arrMarker['###CLASS###'] = ($isLeader) ? 'tx_bzdstaffdirectory_teamlist_person, leader': 'tx_bzdstaffdirectory_teamlist_person';
			$arrMarker['###FIRST_NAME###'] = $actual_person['first_name'];
			$arrMarker['###LAST_NAME###'] = $actual_person['last_name'];
			$arrMarker['###FUNCTION###'] = $actual_person['function'];
			$arrWrappedSubpart['###LINK_DETAIL###'] = array('<A href="'. $this->pi_linkTP_keepPIvars_url(array('showUid' => $actual_person["uid"], 'backId' => $GLOBALS["TSFE"]->id), 0, 1, $this->detailpage) .'">','</A>');
	
			$result .= $this->cObj->substituteMarkerArrayCached($template[$this->code],$arrMarker,array(),$arrWrappedSubpart);
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


	function getTemplateCode()	{

		if ( $this->pi_getFFvalue($this->cObj->data['pi_flexform'],'template_file','s_template') != '' )	{
			$templateCode = $this->cObj->fileResource($this->pi_getFFvalue($this->cObj->data['pi_flexform'],'template_file','s_template'));
		}
		else	{
			$templateCode = $this->cObj->fileResource($this->arrConf["templateFile"]);
		}


		$template = array();

		$template["TEAMLIST"] = $this->cObj->getSubpart($templateCode,"###TEMPLATE_TEAMLIST###");
		$template["DETAIL"] = $this->cObj->getSubpart($templateCode,"###TEMPLATE_DETAIL###");
		$template["BOX"] = $this->cObj->getSubpart($templateCode,"###TEMPLATE_BOX###");


		return $template;
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
//			print_r($group);
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
			'',	// ORDER BY
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

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzd_staff_directory/pi1/class.tx_bzdstaffdirectory_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/bzd_staff_directory/pi1/class.tx_bzdstaffdirectory_pi1.php']);
}

?>
