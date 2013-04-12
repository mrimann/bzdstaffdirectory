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
 * Testcase for the team model in the 'bzdstaffdirectory' extension.
 *
 * @package		TYPO3
 * @subpackage	tx_bzdstaffdirectory
 * @author		Mario Rimann <typo3-coding@rimann.org>
 */
class tx_bzdstaffdirectory_Model_Team_testcase extends tx_phpunit_testcase {
	private $fixture;
	private $uid;

	protected function setUp() {
		$this->testingFramework = new tx_oelib_testingFramework('tx_bzdstaffdirectory');

		$this->uid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array(
				'group_name' => 'Test Team',
			)
		);
		$this->createTeam($this->uid);
	}

	protected function tearDown() {
		$this->testingFramework->cleanUp();
		unset($this->testingFramework);
		unset($this->fixture);
	}

	/**
	 * Creates an instance of a team model in $this->fixture.
	 *
	 * @param integer the team's UID, must be >= 0
	 */
	private function createTeam($teamUid) {
		try {
			$this->fixture = tx_oelib_MapperRegistry::get('tx_bzdstaffdirectory_Mapper_Team')
					->find($teamUid);
		} catch (tx_oelib_Exception_NotFound $exception) {
			$this->fixture = null;
		}
	}




	public function testGetUid() {
		$this->assertEquals(
			$this->uid,
			$this->fixture->getUid()
		);
	}

	public function testGetTitleReturnsTitle() {
		$this->assertEquals(
			'Test Team',
			$this->fixture->getTitle()
		);
	}

	public function testHasInfopageRetrunsFalseIfNoPageSet() {
		$this->assertFalse(
			$this->fixture->hasInfopage()
		);
	}

	public function testHasInfopageReturnsTrueIfPageIsSet() {
		$teamUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array(
				'infopage' => 99
			)
		);
		$this->createTeam($teamUid);

		$this->assertTrue(
			$this->fixture->hasInfopage()
		);
	}

	public function testGetInfopagePidReturnsTheSetPid() {
		$teamUid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array(
				'infopage' => 99
			)
		);
		$this->createTeam($teamUid);

		$this->assertEquals(
			99,
			$this->fixture->getInfopagePid()
		);
	}
}

?>
