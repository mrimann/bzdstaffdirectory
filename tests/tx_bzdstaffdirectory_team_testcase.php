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
/**
 * Testcase for the team class in the 'bzd_staff_directory' extension.
 *
 * @package		TYPO3
 * @subpackage	tx_bzdstaffdirectory
 * @author		Mario Rimann <mario@screenteam.com>
 */

require_once(t3lib_extMgm::extPath('oelib').'class.tx_oelib_testingFramework.php');
require_once(t3lib_extMgm::extPath('bzd_staff_directory').'class.tx_bzdstaffdirectory_team.php');


class tx_bzdstaffdirectory_team_testcase extends tx_phpunit_testcase {
	private $fixture;
	private $uid;

	protected function setUp() {
		$this->testingFramework = new tx_oelib_testingFramework('tx_bzdstaffdirectory');

		$this->uid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_groups',
			array(
				'group_name' => 'Test project FOOBAR 42',
				'infopage' => '42'
			)
		);
		$this->fixture = new tx_bzdstaffdirectory_team($this->uid);
	}

	protected function tearDown() {
		$this->testingFramework->cleanUp();
		unset($this->testingFramework);
		unset($this->fixture);
	}


	public function testGetTitle() {
		$this->assertEquals(
			'Test project FOOBAR 42',
			$this->fixture->getTitle()
		);
	}

	public function testGetInfoPageUid() {
		$this->assertEquals(
			'42',
			$this->fixture->getInfoPageUid()
		);
	}


}

?>
