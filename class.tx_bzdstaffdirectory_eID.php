<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Mario Rimann (mario@screenteam.com)
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

require_once(t3lib_extMgm::extPath('oelib') . 'class.tx_oelib_Autoloader.php');

/**
 * eID stuff for the Staff Directory extension.
 *
 * @author	Mario Rimann <mario@screenteam.com>
 */
class tx_bzdstaffdirectory_eID extends tx_oelib_templatehelper {
	function main(){
//		$feUserObj = tslib_eidtools::initFeUser(); // Initialize FE user object
		tslib_eidtools::connectDB(); //Connect to database

//		$personUid = intval(t3lib_div::_GET('personUid'));
		$person = new tx_bzdstaffdirectory_Model_Person($personUid);

//		header("Pragma: public"); // required
//		header("Expires: 0");
//		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//		header("Cache-Control: private",false); // required for certain browsers
//		header("Content-Type: text/x-vcard");
//		header("Content-Disposition: attachment; filename=\"foo.vcf\";" );
//		header("Content-Transfer-Encoding: binary");
//		header("Content-Length: 20".(2));

		return $this->renderVcf($person);
	}

	private function renderVcf($person) {
		return 'BEGIN:VCARD
VERSION:3.0
N:Gump;Forrest
FN:Forrest Gump
ORG:Bubba Gump Shrimp Co.
TITLE:Shrimp Man
TEL;TYPE=WORK,VOICE:(111) 555-1212
TEL;TYPE=HOME,VOICE:(404) 555-1212
ADR;TYPE=WORK:;;100 Waters Edge;Baytown;LA;30314;United States of America
LABEL;TYPE=WORK:100 Waters Edge\nBaytown, LA 30314\nUnited States of America
ADR;TYPE=HOME:;;42 Plantation St.;Baytown;LA;30314;United States of America
LABEL;TYPE=HOME:42 Plantation St.\nBaytown, LA 30314\nUnited States of America
EMAIL;TYPE=PREF,INTERNET:forrestgump@example.com
REV:20080424T195243Z
END:VCARD';
	}
}

$output = t3lib_div::makeInstance('tx_bzdstaffdirectory_eID');
echo $output->main();
//exit;
?>