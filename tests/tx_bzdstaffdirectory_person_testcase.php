<?php
/***************************************************************
* Copyright notice
*
* (c) 2009 Mario Rimann (typo3-coding@rimann.org)
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
 * Testcase for the person class in the 'bzd_staff_directory' extension.
 *
 * @package		TYPO3
 * @subpackage	tx_bzdstaffdirectory
 * @author		Mario Rimann <typo3-coding@rimann.org>
 */

require_once(t3lib_extMgm::extPath('oelib').'class.tx_oelib_testingFramework.php');
require_once(t3lib_extMgm::extPath('bzd_staff_directory').'class.tx_bzdstaffdirectory_person.php');


class tx_bzdstaffdirectory_person_testcase extends tx_phpunit_testcase {
	private $fixture;
	private $uid;

	protected function setUp() {
		$this->testingFramework = new tx_oelib_testingFramework('tx_bzdstaffdirectory');

		$this->uid = $this->testingFramework->createRecord(
			'tx_bzdstaffdirectory_persons',
			array(
				'first_name' => 'Max',
				'last_name' => 'Muster'
			)
		);
		$this->fixture = new tx_bzdstaffdirectory_person($this->uid);
	}

	protected function tearDown() {
		$this->testingFramework->cleanUp();
		unset($this->testingFramework);
		unset($this->fixture);
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



}

?>
