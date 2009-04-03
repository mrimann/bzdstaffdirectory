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
 * Testcase for the person class in the 'bzdstaffdirectory' extension.
 *
 * @package		TYPO3
 * @subpackage	tx_bzdstaffdirectory
 * @author		Mario Rimann <mario@screenteam.com>
 */
class tx_bzdstaffdirectory_Model_Person_testcase extends tx_phpunit_testcase {
	private $fixture;
	private $uid;

	protected function setUp() {
		$this->testingFramework = new tx_oelib_testingFramework('tx_bzdstaffdirectory');

		$this->uid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array(
				'first_name' => 'Max',
				'last_name' => 'Muster',
				'title' => 'Dr.',
				'officehours' => '07:00 - 17:00',
				'function' => 'Master of Desaster',
				'nickname' => 'Mickey Mouse',
				'phone' => '+41 44 123 45 67',
				'mobile_phone' => '+41 79 123 45 67',
				'room' => '301',
				'email' => 'chief@example.org',
				'date_birthdate' => strtotime('-10 years'),
				'date_incompany' => strtotime('-10 years'),
				'xing_profile_url' => 'http://www.xing.com/profile/foo.bar',
				'opinion' => 'louder, harder, scooter - or so',
				'image' => 'smurf300.jpg',
				'gender' => 1,
			)
		);
		$this->createPerson($this->uid);
	}

	protected function tearDown() {
		$this->testingFramework->cleanUp();
		unset($this->testingFramework);
		unset($this->fixture);
	}

	/**
	 * Creates a person in $this->fixture.
	 *
	 * @param integer a person's UID, must be >= 0
	 */
	private function createPerson($personUid) {
		try {
			$this->fixture = tx_oelib_MapperRegistry::get('tx_bzdstaffdirectory_Mapper_Person')
					->find($personUid);
		} catch (tx_oelib_Exception_NotFound $exception) {
			$this->fixture = null;
		}
	}

	/**
	 * Creates a new location record in the database and creates a relation between
	 * the new location record and a person record identified by the personUID.
	 *
	 * @param integer the person's UID
	 * @return integer the new location record's UID
	 */
	private function createLocationAndAssignPerson($personUid, $locationTitle = 'dummy Location') {
		$locationUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_locations',
			array(
				'title' => $locationTitle
			)
		);

		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_bzdstaffdirectory_persons',
			$personUid,
			$locationUid,
			'location'
		);

		return $locationUid;
	}


	public function testGetUid() {
		$this->assertEquals(
			$this->uid,
			$this->fixture->getUid()
		);
	}

	public function testGetFirstName() {
		$this->assertEquals(
			'Max',
			$this->fixture->getFirstName()
		);
	}

	public function testGetLastName() {
		$this->assertEquals(
			'Muster',
			$this->fixture->getLastName()
		);
	}

	public function testHasTitleReturnsTrueWithSetTitle() {
		$this->assertTrue(
			$this->fixture->hasTitle()
		);
	}

	public function testGetTitleReturnsTitle() {
		$this->assertEquals(
			'Dr.',
			$this->fixture->getTitle()
		);
	}

	public function testHasStandardFieldReturnsTrueOnValidKeyAndFilledField() {
		$this->assertTrue($this->fixture->hasStandardField('room'));
	}

	public function testHasStandardFieldReturnsFalseOnValidKeyButEmptyField() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array()
		);
		$this->createPerson($personUid);

		$this->assertFalse($this->fixture->hasStandardField('function'));
	}

	public function testHasStandardFieldThrowsExceptionOnEmptyKey() {
		$this->setExpectedException(
			'Exception', '$key must not be empty!'
		);

		$this->fixture->hasStandardField('');
	}

	public function testHasStandardFieldThrowsExceptionOnIllegalKey() {
		$this->setExpectedException(
			'Exception', 'foobar was an illegal key!'
		);

		$this->fixture->hasStandardField('foobar');
	}

	public function testGetStandardFieldThrowsExceptionOnIllegalKey() {
		$this->setExpectedException(
			'Exception', 'foobar was an illegal key!'
		);

		$this->fixture->getStandardField('foobar');
	}

	public function testGetStandardFieldThrowsExceptionOnEmptyKey() {
		$this->setExpectedException(
			'Exception', '$key must not be empty!'
		);

		$this->fixture->getStandardField('');
	}

	public function testGetStandardFieldWithRoom() {
		$this->assertEquals(
			'301',
			$this->fixture->getStandardField('room')
		);
	}

	public function testGetStandardFieldWithOfficeHours() {
		$this->assertEquals(
			'07:00 - 17:00',
			$this->fixture->getStandardField('officehours')
		);
	}

	public function testGetStandardFieldWithNickname() {
		$this->assertEquals(
			'Mickey Mouse',
			$this->fixture->getStandardField('nickname')
		);
	}

	public function testGetStandardFieldWithPhone() {
		$this->assertEquals(
			'+41 44 123 45 67',
			$this->fixture->getStandardField('phone')
		);
	}

	public function testGetStandardFieldWithMobilePhone() {
		$this->assertEquals(
			'+41 79 123 45 67',
			$this->fixture->getStandardField('mobile_phone')
		);
	}

	public function testGetStandardFieldWithFunction() {
		$this->assertEquals(
			'Master of Desaster',
			$this->fixture->getStandardField('function')
		);
	}

	public function testGetStandardFieldWithEmail() {
		$this->assertEquals(
			'chief@example.org',
			$this->fixture->getStandardField('email')
		);
	}

	public function testHasBirthDateReturnsTrueIfBirthDateSet() {
		$this->assertTrue(
			$this->fixture->hasBirthDate()
		);
	}

	public function testHasBirthDateReturnsFalseIfNoBirthDateSet() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array()
		);
		$this->createPerson($personUid);

		$this->assertFalse(
			$this->fixture->hasBirthDate()
		);
	}

	public function testHasBirthDateReturnsTrueIfBirthdateBefore1970IsSet() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array(
				'date_birthdate' => strtotime('2 November 1969'),
			)
		);

		$this->createPerson($personUid);

		$this->assertTrue(
			$this->fixture->hasBirthDate()
		);
	}

	public function testGetBirthDateReturnsDateObject() {
		$this->assertTrue(
			is_a($this->fixture->getBirthDate(), DateTime)
		);
	}

	public function testGetBirthDateReturnsCorrectBirthDate() {
		$this->assertEquals(
			date('Y-m-d', strtotime("-10 years")),
			$this->fixture->getBirthDate()->format('Y-m-d')
		);
	}

	public function testGetAgeReturnsInteger() {
		$this->assertTrue(
			is_int($this->fixture->getAge())
		);
	}

	public function testGetAgeReturnsCorrectValue() {
		$this->assertEquals(
			10,
			$this->fixture->getAge()
		);
	}

	public function testHasXingProfileReturnsTrueIfLinkIsSet() {
		$this->assertTrue(
			$this->fixture->hasXingProfile()
		);
	}

	public function testHasXingProfileReturnsFalseIfNoLinkIsSet() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array()
		);
		$this->createPerson($personUid);

		$this->assertFalse(
			$this->fixture->hasXingProfile()
		);
	}

	public function testGetXingProfileLinkReturnsTheStoredLink() {
		$this->assertEquals(
			'http://www.xing.com/profile/foo.bar',
			$this->fixture->getXingProfileLink()
		);
	}

	public function testGetXingProfileLinkReturnsEmptyStringIfNoProfileSet() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array()
		);
		$this->createPerson($personUid);

		$this->assertEquals(
			'',
			$this->fixture->getXingProfileLink()
		);
	}

	public function testHasDateInCompanyReturnsTrueIfDateIsSet() {
		$this->assertTrue(
			$this->fixture->hasDateInCompany()
		);
	}

	public function testHasDateInCompanyReturnsFalseIfNoDateIsSet() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array()
		);
		$this->createPerson($personUid);

		$this->assertFalse(
			$this->fixture->hasDateInCompany()
		);
	}

	public function testGetDateInCompanyReturnsDateObject() {
		$this->assertTrue(
			is_a($this->fixture->getDateInCompany(), DateTime)
		);
	}

	public function testGetDateInCompanyReturnsCorrectBirthDate() {
		$this->assertEquals(
			date('Y-m-d', strtotime("-10 years")),
			$this->fixture->getDateInCompany()->format('Y-m-d')
		);
	}

	public function testHasOpinionReturnsTrueIfOpinionIsSet() {
		$this->assertTrue($this->fixture->hasOpinion());
	}

	public function testHasOpinionReturnsFalseIfNoOpinionSet() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array()
		);
		$this->createPerson($personUid);

		$this->assertFalse($this->fixture->hasOpinion());
	}

	public function testGetOpinionReturnsTheStoredValue() {
		$this->assertEquals(
			'louder, harder, scooter - or so',
			$this->fixture->getOpinion()
		);
	}

	public function testHasTeamsReturnsFalseOnNoTeamMemberhip() {
		$this->assertFalse($this->fixture->hasTeams());
	}

	public function testHasTeamsReturnsTrueOnSingleTeamMembership() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array()
		);
		$teamUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array()
		);
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_bzdstaffdirectory_persons',
			$personUid,
			$teamUid,
			'usergroups'
		);
		$this->createPerson($personUid);

		$this->assertTrue($this->fixture->hasTeams());
	}

	public function testHasTeamsReturnsTrueOnMultipleTeamMembership() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array()
		);
		$teamUid1 = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array()
		);
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_bzdstaffdirectory_persons',
			$personUid,
			$teamUid1,
			'usergroups'
		);
		$teamUid2 = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array()
		);
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_bzdstaffdirectory_persons',
			$personUid,
			$teamUid2,
			'usergroups'
		);
		$this->createPerson($personUid);

		$this->assertTrue($this->fixture->hasTeams());
	}

	public function testGetTeamsReturnsEmptyListOnNoMembership() {
		$this->assertTrue(
			$this->fixture->getTeams()->isEmpty()
		);
	}

	public function testGetTeamsReturnsArrayWithOneUidOnSingleTeamMembership() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array()
		);
		$teamUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array()
		);
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_bzdstaffdirectory_persons',
			$personUid,
			$teamUid,
			'usergroups'
		);
		$this->createPerson($personUid);

		$this->assertEquals(
			1,
			$this->fixture->getTeams()->count()
		);
	}

	public function testGetTeamsReturnsArrayWithMultipleUidsOnMultipleTeamMembership() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array()
		);
		$teamUid1 = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array()
		);
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_bzdstaffdirectory_persons',
			$personUid,
			$teamUid1,
			'usergroups'
		);
		$teamUid2 = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array()
		);
		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_bzdstaffdirectory_persons',
			$personUid,
			$teamUid2,
			'usergroups'
		);
		$this->createPerson($personUid);

		$this->assertEquals(
			2,
			$this->fixture->getTeams()->count()
		);
	}

	public function testHasImageReturnsFalseIfNoImageSet() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array()
		);
		$this->createPerson($personUid);

		$this->assertFalse(
			$this->fixture->hasImage()
		);
	}

	public function testHasImageReturnsTrueIfAnImageIsSet() {
		$this->assertTrue(
			$this->fixture->hasImage()
		);
	}

	public function getImageReturnsTheStoredFileName() {
		$this->assertEquals(
			'smurf300.jpg',
			$this->fixture->getImage()
		);
	}

	public function testHasGenderReturnsFalseIfGenderUndefined() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array()
		);
		$this->createPerson($personUid);

		$this->assertFalse(
			$this->fixture->hasGender()
		);
	}

	public function testGetGenderReturnsZeroIfGenderUndefined() {
		$personUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array()
		);
		$this->createPerson($personUid);

		$this->assertEquals(
			0,
			$this->fixture->getGender()
		);
	}

	public function testGetGenderReturnsSetGender() {
		$this->assertEquals(
			1,
			$this->fixture->getGender()
		);
	}

	public function testHasLocationReturnsFalseIfNoLocationIsAssigned() {
		$this->assertFalse(
			$this->fixture->hasLocation()
		);
	}

	public function testHasLocationReturnsTrueOnOneAssignedLocation() {
		$this->createLocationAndAssignPerson($this->uid);

		$this->assertTrue(
			$this->fixture->hasLocation()
		);
	}

	public function testGetLocationsReturnsEmptyListOnNoLocation() {
		$this->assertTrue(
			$this->fixture->getLocations()->isEmpty()
		);
	}

	public function testGetLocationsReturnsListOfLocations() {
		$this->createLocationAndAssignPerson($this->uid);

		$this->assertEquals(
			1,
			$this->fixture->getLocations()->count()
		);
	}

	public function testGetLocationsThrowsExceptionOnNoLocations() {
		$this->createLocationAndAssignPerson($this->uid, 'Team A');
		$this->createLocationAndAssignPerson($this->uid, 'Team B');

		$this->assertEquals(
			2,
			$this->fixture->getLocations()->count()
		);
	}
}

?>
