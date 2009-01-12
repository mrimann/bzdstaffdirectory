<?php
/***************************************************************
* Copyright notice
*
* (c) 2008-2009 Mario Rimann (mario@screenteam.com)
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
 * Testcase for the 'frontEndDetailView' class in the 'bzdstaffdirectory' extension.
 * The original code is from the Seminar Manager (tx_seminars), thanks Oli and Niels!
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 * @author Niels Pardon <mail@niels-pardon.de>
 * @author Mario Rimann <mario@screenteam.com>
 */
class tx_bzdstaffdirectory_frontEndDetailView_testcase extends tx_phpunit_testcase {
	/**
	 * @var tx_bzdstaffdirectory_pi1_frontEndDetailView
	 */
	private $fixture;
	/**
	 * @var tx_oelib_testingFramework
	 */
	private $testingFramework;

	/**
	 * @var integer the UID of a person to which the fixture relates
	 */
	private $personUid;

	public function setUp() {
		$this->testingFramework = new tx_oelib_testingFramework('tx_bzdstaffdirectory');
		$this->testingFramework->createFakeFrontEnd();

		$this->personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array(
				'first_name' => 'John',
				'last_name' => 'Doe',
				'title' => 'Dr.',
				'room' => '301',
				'officehours' => '07:00 - 17:00',
				'function' => 'Master of Desaster',
				'phone' => '+41 44 123 45 67',
				'email' => 'chief@example.org',
				'universal_field_1' => 'Universal Value',
				'date_birthdate' => strtotime("-10 years"),
			)
		);

		$this->fixture = new tx_bzdstaffdirectory_pi1_frontEndDetailView(
			array(
				'isStaticTemplateLoaded' => 1,
				'templateFile' => 'EXT:bzdstaffdirectory/media/bzdstaff_template.htm',
			),
			$GLOBALS['TSFE']->cObj
		);
		$this->fixture->setPerson($this->personUid);
		$this->fixture->setTestMode();
	}

	public function tearDown() {
		$this->testingFramework->cleanUp();
		$this->fixture->__destruct();

		unset($this->fixture, $this->testingFramework);
	}


	//////////////////////////////////////////
	// General tests concerning the fixture.
	//////////////////////////////////////////

	public function testFixtureIsAFrontEndDetailViewObject() {
		$this->assertTrue(
			$this->fixture instanceof tx_bzdstaffdirectory_pi1_frontEndDetailView
		);
	}


	////////////////////////////////
	// Tests for render()
	////////////////////////////////

	public function testRenderContainsFirstName() {
		$this->assertContains(
			'John',
			$this->fixture->render()
		);
	}

	public function testRenderContainsLastName() {
		$this->assertContains(
			'Doe',
			$this->fixture->render()
		);
	}

	public function testRenderContainsTitle() {
		$this->assertContains(
			'Dr.',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainTitleMarkerIfTitleNotSet() {
		$this->personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->fixture->setPerson($this->personUid);

		$this->assertNotContains(
			'###TITLE###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsRoom() {
		$this->assertContains(
			'301',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainRoomMarkerIfRoomNotSet() {
		$this->personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->fixture->setPerson($this->personUid);

		$this->assertNotContains(
			'###ROOM###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsOfficeHours() {
		$this->assertContains(
			'07:00 - 17:00',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainOfficeHoursMarkerIfOfficeHoursNotSet() {
		$this->personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->fixture->setPerson($this->personUid);

		$this->assertNotContains(
			'###OFFICEHOURS###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsPhone() {
		$this->assertContains(
			'+41 44 123 45 67',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainPhoneMarkerIfPhoneNotSet() {
		$this->personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->fixture->setPerson($this->personUid);

		$this->assertNotContains(
			'###PHONE###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsFunction() {
		$this->assertContains(
			'Master of Desaster',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainFunctionMarkerIfFunctionNotSet() {
		$this->personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->fixture->setPerson($this->personUid);

		$this->assertNotContains(
			'###FUNCTION###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsUniversalField1() {
		$this->assertContains(
			'Universal Value',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainUniversalField1MarkerIfUniversalField1NotSet() {
		$this->personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->fixture->setPerson($this->personUid);

		$this->assertNotContains(
			'###UNIVERSAL_FIELD_1###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsEmailAsPlainTextByDefault() {
		$this->assertContains(
			'chief@example.org',
			$this->fixture->render()
		);
	}

	public function testRenderContainsEmailAsPlainTextByConfiguration() {
		$this->fixture->setConfigurationValue('spamProtectionMode', 'plain');

		$this->assertContains(
			'chief@example.org',
			$this->fixture->render()
		);
	}


	public function testRenderContainsEmailJavaScriptEncrypted() {
		$this->fixture->setConfigurationValue('spamProtectionMode', 'jsencrypted');
		$GLOBALS['TSFE']->spamProtectEmailAddresses = 1;

		$this->assertContains(
			'<a href="javascript:linkTo_UnCryptMailto(\'nbjmup+dijfgAfybnqmf/psh\');">',
			$this->fixture->render()
		);
	}

	public function testRenderContainsEmailAsImage() {
		$this->fixture->setConfigurationValue('spamProtectionMode', 'asimage');

		$this->assertContains(
			'<img src="typo3temp/GB/',
			$this->fixture->render()
		);

		$this->assertContains(
			'gif" width="104" height="19" alt="" title="" />',
			$this->fixture->render()
		);
	}

	public function testRenderContainsEmailJSEncryptedImage() {
		$this->fixture->setConfigurationValue('spamProtectionMode', 'asimagejsencrypted');
		$GLOBALS['TSFE']->spamProtectEmailAddresses = 1;

		$this->assertContains(
			'chief@example.orgJSIMG',
			$this->fixture->render()
		);
	}


	public function testRenderDoesNotContainEmailMarkerIfEmailNotSet() {
		$this->personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->fixture->setPerson($this->personUid);

		$this->assertNotContains(
			'###EMAIL###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsBirthDate() {
		$this->assertContains(
			date('F Y', strtotime("-10 years")),
			$this->fixture->render()
		);
	}

	public function testRenderTakesFormatForBirthDateIntoAccount() {
		$this->fixture->setConfigurationValue('dateFormatBirthday', 'j. F Y');

		$this->assertContains(
			date('j. F Y', strtotime("-10 years")),
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainBirthDateMarkerIfBirthDateNotSet() {
		$this->personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->fixture->setPerson($this->personUid);

		$this->assertNotContains(
			'###DATE_BIRTHDATE###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsAgeIfShowAgeInsteadOfBirthDateIsActive() {
		$this->fixture->setConfigurationValue('showAgeInsteadOfBirthdate', 1);

		$this->assertContains(
			'10',
			$this->fixture->render()
		);
	}

	public function testRenderContainsAgeLabelIfShowAgeInsteadOfBirthDateIsActive() {
		$this->fixture->setConfigurationValue('showAgeInsteadOfBirthdate', 1);

		$this->assertContains(
			'Age',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainBirthDateLabelIfShowAgeInsteadOfBirthDateIsActive() {
		$this->fixture->setConfigurationValue('showAgeInsteadOfBirthdate', 1);

		$this->assertNotContains(
			'Birthdate',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainAgeMarkerIfBirthDateNotSet() {
		$this->fixture->setConfigurationValue('showAgeInsteadOfBirthdate', 1);

		$this->personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->fixture->setPerson($this->personUid);

		$this->assertNotContains(
			'###DATE_BIRTHDATE###',
			$this->fixture->render()
		);
	}



}

?>