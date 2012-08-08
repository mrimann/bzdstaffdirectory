<?php
/***************************************************************
* Copyright notice
*
* (c) 2009-2012 Mario Rimann (typo3-coding@rimann.org)
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
 * Testcase for the 'frontEndVcfView' class in the 'bzdstaffdirectory' extension.
 * The original code is from the Seminar Manager (tx_seminars), thanks Oli and Niels!
 *
 * @package TYPO3
 * @subpackage tx_bzdstaffdirectory
 *
 * @author Mario Rimann <typo3-coding@rimann.org>
 */
class tx_bzdstaffdirectory_frontEndVcfView_testcase extends tx_phpunit_testcase {
	/**
	 * @var tx_bzdstaffdirectory_pi1_frontEndVcfView
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
				'phone' => '+41 44 123 45 67',
				'email' => 'chief@example.org',
			)
		);
		$this->createFunctionAndAssignToPerson(
			$this->personUid
		);
		$this->createLocationAndAssignPerson(
			$this->personUid,
			'Dummy Location',
			'Street 42',
			'8000',
			'Luzern',
			'Schweiz'
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
	 * the vCard view for a different (less general) person record for testing.
	 *
	 * @param integer UID of the person to render the detail view for
	 */
	private function getNewFixture($personUid) {
		unset($this->fixture);

		$this->fixture = new tx_bzdstaffdirectory_pi1_frontEndVcfView(
			$personUid,
			array(
				'isStaticTemplateLoaded' => 1,
				'templateFile' => 'EXT:bzdstaffdirectory/media/bzdstaff_template.htm',
			),
			$GLOBALS['TSFE']->cObj
		);

		$this->fixture->setTestMode();
	}

	/**
	 * Creates a new location record in the database and creates a relation between
	 * the new location record and a person record identified by the personUID.
	 *
	 * @param integer the person's UID
	 * @param string the location's title
	 * @param string the address
	 *
	 * @return integer the new location record's UID
	 */
	private function createLocationAndAssignPerson($personUid, $locationTitle = 'Dummy Location', $address = '', $zip = '', $city = '', $country = '') {
		$locationUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_locations',
			array(
				'title' => $locationTitle,
				'address' => $address,
				'zip' => $zip,
				'city' => $city,
				'country' => $country
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

	/**
	 * Creates a new function record in the database and creates a relation between
	 * the new function record and a person record identified by the personUID.
	 *
	 * @param integer the person's UID
	 * @param string the title of the function record, optional
	 *
	 * @return integer the new location record's UID
	 */
	private function createFunctionAndAssignToPerson($personUid, $functionTitle = 'Bla bla specialist') {
		$functionUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_functions',
			array(
				'title' => $functionTitle
			)
		);

		$this->testingFramework->createRelationAndUpdateCounter(
			'tx_bzdstaffdirectory_persons',
			$personUid,
			$functionUid,
			'functions'
		);

		return $functionUid;
	}

	//////////////////////////////////////////
	// General tests concerning the fixture.
	//////////////////////////////////////////

	public function testFixtureIsAFrontEndVcfViewObject() {
		$this->assertTrue(
			$this->fixture instanceof tx_bzdstaffdirectory_pi1_frontEndVcfView
		);
	}


	////////////////////////////////
	// Tests for render()
	////////////////////////////////

	public function testRenderContainsFirstAndLastName() {
		$this->assertContains(
			'N;CHARSET=utf-8:Doe;John',
			$this->fixture->render()
		);
	}

	public function testRenderContainsFullName() {
		$this->assertContains(
			'FN;CHARSET=utf-8:John Doe',
			$this->fixture->render()
		);
	}

	public function testRenderContainsCompanyName() {
		$this->fixture->setConfigurationValue('companyNameToShowInVCard', 'Dummy Company');

		$this->assertContains(
			'ORG;CHARSET=utf-8:Dummy Company',
			$this->fixture->render()
		);
	}

	public function testRenderContainsLocationAddress() {
		$this->assertContains(
			'ADR;CHARSET=utf-8;TYPE=WORK:;;Street 42;Luzern;;8000;Schweiz',
			$this->fixture->render()
		);
	}

	public function testRenderContainsPhoneNumber() {
		$this->assertContains(
			'TEL;TYPE=WORK,VOICE:+41 44 123 45 67',
			$this->fixture->render()
		);
	}

	public function testRenderContainsEmailAddress() {
		$this->assertContains(
			'EMAIL;TYPE=PREF,INTERNET:chief@example.org',
			$this->fixture->render()
		);
	}

	public function testRenderContainsFunction() {
		$this->assertContains(
			'TITLE;CHARSET=utf-8:Bla bla specialist',
			$this->fixture->render()
		);
	}
}

?>