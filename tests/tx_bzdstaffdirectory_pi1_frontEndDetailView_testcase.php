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
				'nickname' => 'Mickey Mouse',
				'phone' => '+41 44 123 45 67',
				'mobile_phone' => '+41 79 123 45 67',
				'email' => 'chief@example.org',
				'universal_field_1' => 'Universal Value',
				'date_birthdate' => strtotime('-10 years'),
				'date_incompany' => strtotime('-2 years'),
				'xing_profile_url' => 'http://www.xing.com/profile/foo.bar',
				'opinion' => 'louder, harder, scooter - or so',
			)
		);

		// Generates the fixture in $this->fixture.
		$this->getNewFixture($this->personUid);
	}

	public function tearDown() {
		$this->testingFramework->cleanUp();
		$this->fixture->__destruct();

		unset($this->fixture, $this->testingFramework);
	}

	/**
	 * Creates a new fixture in $this->fixture. This is mainly used to render
	 * the detail view for a different (less general) person record for testing.
	 *
	 * @param integer UID of the person to render the detail view for
	 */
	private function getNewFixture($personUid) {
		unset($this->fixture);

		$this->fixture = new tx_bzdstaffdirectory_pi1_frontEndDetailView(
			$personUid,
			array(
				'isStaticTemplateLoaded' => 1,
				'templateFile' => 'EXT:bzdstaffdirectory/media/bzdstaff_template.htm',
			),
			$GLOBALS['TSFE']->cObj
		);

		$this->fixture->setTestMode();
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
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

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
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

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
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

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
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

		$this->assertNotContains(
			'###PHONE###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsMobilePhone() {
		$this->assertContains(
			'+41 79 123 45 67',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainMobilePhoneMarkerIfMobilePhoneNotSet() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

		$this->assertNotContains(
			'###MOBILE_PHONE###',
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
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

		$this->assertNotContains(
			'###FUNCTION###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsNickname() {
		$this->assertContains(
			'Mickey Mouse',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainNicknameMarkerIfNicknameNotSet() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

		$this->assertNotContains(
			'###NICKNAME###',
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
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

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
        $this->markTestIncomplete(
          'This test does not work as expected yet, some path/permission issues with generated images.'
        );

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
        $this->markTestIncomplete(
          'This test does not work as expected yet, some path/permission issues with generated images.'
        );

		$this->fixture->setConfigurationValue('spamProtectionMode', 'asimagejsencrypted');
		$GLOBALS['TSFE']->spamProtectEmailAddresses = 1;

		$this->assertContains(
			'chief@example.orgJSIMG',
			$this->fixture->render()
		);
	}


	public function testRenderDoesNotContainEmailMarkerIfEmailNotSet() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

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

	public function testRenderContainsBirthDateBefore1970() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array(
				'title' => 'foo',
				'date_birthdate' => strtotime('2 November 1969'),
			)
		);
		$this->getNewFixture($personUid);

		$this->assertContains(
			date('F Y', strtotime("2 November 1969")),
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
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

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
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

		$this->fixture->setConfigurationValue('showAgeInsteadOfBirthdate', 1);


		$this->assertNotContains(
			'###DATE_BIRTHDATE###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsXingIconAndLinkIfUrlWasStoredInPersonRecord() {
		$this->assertContains(
			'<img src="http://www.xing.com/img/buttons/1_de_btn.gif" width="85" height="23" alt="XING" border="0" />',
			$this->fixture->render()
		);
		$this->assertContains(
			'<a href="http://www.xing.com/profile/foo.bar" target="_blank">',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainXingMarkerIfXingLinkNotSet() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

		$this->assertNotContains(
			'###XING###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsDateInCompany() {
		$this->assertContains(
			date('F Y', strtotime("-2 years")),
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainDateInCompanyMarkerIfDateNotSet() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

		$this->assertNotContains(
			'###DATE_INCOMPANY###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsOpinion() {
		$this->assertContains(
			'louder, harder, scooter - or so',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainOpinionMarkerIfOpinionNotSet() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$this->getNewFixture($personUid);

		$this->assertNotContains(
			'###OPINION###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsTeamEntryOnSingleTeamMembership() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$teamUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array(
				'group_name' => 'Test-Team',
			)
		);
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_bzdstaffdirectory_persons',
			$personUid,
			$teamUid,
			'usergroups'
		);
		$this->getNewFixture($personUid);

		$this->assertContains(
			'<td>Test-Team</td>',
			$this->fixture->render()
		);
	}

	public function testRenderContainsTeamListOnMultipleTeamMembership() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$teamUid1 = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array(
				'group_name' => 'Test-Team',
			)
		);
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_bzdstaffdirectory_persons',
			$personUid,
			$teamUid1,
			'usergroups'
		);
		$teamUid2 = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array(
				'group_name' => 'Second-Team',
			)
		);
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_bzdstaffdirectory_persons',
			$personUid,
			$teamUid2,
			'usergroups'
		);
		$this->getNewFixture($personUid);

		$this->assertContains(
			'<li>Test-Team</li>',
			$this->fixture->render()
		);

		$this->assertContains(
			'<li>Second-Team</li>',
			$this->fixture->render()
		);
	}

	public function testRenderContainsLinkedTeamNameIfTeamHasInfopage() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons'
		);
		$pid = $this->testingFramework->createFrontEndPage(

		);
		$teamUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array(
				'group_name' => 'Test-Team',
				'infopage' => $pid,
			)
		);
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_bzdstaffdirectory_persons',
			$personUid,
			$teamUid,
			'usergroups'
		);
		$this->getNewFixture($personUid);

		$this->assertContains(
			'<a href="?id=' . $pid . '" >Test-Team</a>',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainTeamsMarkerIfNoTeamsAssigned() {
		$this->assertNotContains(
			'###GROUPS###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsBackLinkIfPidDefined() {
		$pid = $this->testingFramework->createFrontEndPage();
		$this->fixture->setConfigurationValue('backPid', $pid);

		$this->assertContains(
			'<a href="?id=' . $pid . '" >back</a>',
			$this->fixture->render()
		);
	}

	public function testRenderDoesNotContainBackLinkMarkerIfNoPidSet() {
		$this->assertNotContains(
			'###LINK_BACK###',
			$this->fixture->render()
		);
	}

	public function testRenderContainsImageOfPersonWithImage() {
		$this->markTestIncomplete(
          'This test does not work as expected yet, generated images can not be tested right now.'
        );

        $this->assertContains(
			'smurf300.jpg',
			$this->fixture->render()
		);
	}

	public function testRenderContainsUnisexDummyImageForPersonWithoutImageAndNoGenderSet() {
		$this->markTestIncomplete(
          'This test does not work as expected yet, generated images can not be tested right now.'
        );

        $this->assertContains(
			'unisex_dummy.jpg',
			$this->fixture->render()
		);
	}

	public function testRenderContainsMaleDummyImageForMalePersonWithoutImage() {
		$this->markTestIncomplete(
          'This test does not work as expected yet, generated images can not be tested right now.'
        );

        $this->assertContains(
			'male_dummy.jpg',
			$this->fixture->render()
		);
	}

	public function testRenderContainsFemaleDummyImageForFemalePersonWithoutImage() {
		$this->markTestIncomplete(
          'This test does not work as expected yet, generated images can not be tested right now.'
        );

        $this->assertContains(
			'female_dummy.jpg',
			$this->fixture->render()
		);
	}
}

?>