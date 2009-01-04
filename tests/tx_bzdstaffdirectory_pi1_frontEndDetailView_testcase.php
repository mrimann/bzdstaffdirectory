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



}

?>