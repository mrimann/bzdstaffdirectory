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
 * The objectFromDB class in the 'bzd_staff_directory' extension.
 *
 * This class represents an object that is created from a DB record.
 *
 * This is an abstract class; don't instantiate it.
 *
 * Big parts of this class is copied / read / adapted from the Seminar Manager
 * codebase (see tx_seminars). Thanks to Oliver Klee who is the original
 * author of this class.
 *
 * @package		TYPO3
 * @subpackage	tx_bzdstaffdirectory
 * @author		Mario Rimann <typo3-coding@rimann.org>
 * @author		Oliver Klee <typo3-coding@oliverklee.de>
 */

require_once(t3lib_extMgm::extPath('bzd_staff_directory').'class.tx_bzdstaffdirectory_objectFromDb.php');


class tx_bzdstaffdirectory_objectFromDb {
	/** string with the name of the SQL table this class corresponds to */
	protected $tableName = '';
	/** associative array with the values from/for the DB */
	protected $recordData = array();
	/** whether this record already is stored in the DB */
	protected $isInDb = false;

	/**
	 * The constructor. Creates a test instance from a DB record.
	 *
	 * @param	integer		The UID of the record to retrieve from the DB. This
	 * 						parameter will be ignored if $dbResult is provided.
	 * @param	pointer		MySQL result pointer (of SELECT query)/DBAL object.
	 * 						If this parameter is provided, $uid will be
	 * 						ignored.
	 *
	 * @author	Oliver Klee <typo3-coding@oliverklee.de>
	 */
	public function __construct($uid, $dbResult = null) {
		$this->retrieveRecordAndGetData($uid, $dbResult);
	}

	/**
	 * Retrieves this record's data from the DB (if it has not been retrieved
	 * yet) and gets the record data from the DB result.
	 *
	 * @param	integer		The UID of the record to retrieve from the DB. This
	 * 						parameter will be ignored if $dbResult is provided.
	 * @param	pointer		MySQL result pointer (of SELECT query)/DBAL object.
	 * 						If this parameter is provided, $uid will be ignored.
	 * @param	boolean		whether it is possible to create an object from a
	 * 						hidden record
	 *
	 * @author	Oliver Klee <typo3-coding@oliverklee.de>
	 */
	protected function retrieveRecordAndGetData(
		$uid, $dbResult = null, $allowHiddenRecords = false
	) {
		if (!$dbResult) {
			$dbResult = $this->retrieveRecord($uid, $allowHiddenRecords);
		}

		if (!$dbResult) {
			throw new tx_bzdstaffdirectory_Exception('No valid data to build up a record from it.');
		}

		$data = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbResult);
		if ($data) {
			$this->getDataFromDbResult($data);
		}
	}

	/**
	 * Reads the record data from an DB query result represented as an
	 * associative array and stores it in $this->recordData.
	 * The column names will be used as array keys.
	 * The column names must *not* be prefixed with the table name.
	 *
	 * Before this function may be called, $this->tableName must be set
	 * to the correspondonding DB table name.
	 *
	 * If at least one element is taken, this function sets $this->isInDb to true.
	 *
	 * Example:
	 * $dbResultRow['name'] => $this->recordData['name']
	 *
	 * @param	array		associative array of a DB query result
	 *
	 * @author	Oliver Klee <typo3-coding@oliverklee.de>
	 */
	protected function getDataFromDbResult(array $dbResultRow) {
		if (!empty($this->tableName) && !empty($dbResultRow)) {
			$this->recordData = $dbResultRow;
			$this->isInDb = true;
		}
	}

	/**
	 * Retrieves a record from the database.
	 *
	 * The record is retrieved from $this->tableName. Therefore $this->tableName
	 * has to be set before calling this method.
	 *
	 * @param	integer		The UID of the record to retrieve from the DB.
	 * @param	boolean		whether to allow hidden records
	 *
	 * @return	pointer		MySQL result pointer (of SELECT query)/DBAL object, null if the UID is invalid
	 *
	 * @author	Oliver Klee <typo3-coding@oliverklee.de>
	 */
	protected function retrieveRecord($uid, $allowHiddenRecords = false) {
		if ($this->recordExists($uid, $this->tableName, $allowHiddenRecords)) {
		 	$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'*',
				$this->tableName,
				'uid='.intval($uid),
				'',
				'',
				'1'
			);
	 	} else {
	 		$result = null;
	 	}

		return $result;
	}

	/**
	 * Checks whether a non-deleted and non-hidden record with a given UID exists
	 * in the DB. If the parameter $allowHiddenRecords is set to true, hidden
	 * records will be selected, too.
	 *
	 * This method may be called statically.
	 *
	 * @param	string		string with a UID (need not necessarily be escaped, will be intval'ed)
	 * @param	string		string with the tablename where the UID should be searched for
	 * @param	boolean		whether hidden records should be accepted
	 *
	 * @return	boolean		true if a visible record with that UID exists; false otherwise.
	 *
	 * @author	Oliver Klee <typo3-coding@oliverklee.de>
	 */
	public function recordExists($uid, $tableName, $allowHiddenRecords = false) {
		$result = is_numeric($uid) && ($uid);

		if ($result && !empty($tableName)) {
			$dbResult = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
				'COUNT(*) AS num',
				$tableName,
				'uid='.intval($uid)
			);

			if ($dbResult) {
				$dbResultAssoc = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($dbResult);
				$result = ($dbResultAssoc['num'] == 1);
			} else {
				$result = false;
			}
		} else {
			$result = false;
		}

		return (boolean) $result;
	}

	/**
	 * Checks whether this object has been properly initialized,
	 * has a non-empty table name set and thus is basically usable.
	 *
	 * @return	boolean		true if the object has been initialized, false otherwise.
	 *
	 * @author	Oliver Klee <typo3-coding@oliverklee.de>
	 */
	public function isOk() {
		return (!empty($this->recordData) && !empty($this->tableName));
	}

	/**
	 * Checks whether $this->recordData is initialized at all and
	 * whether a given key exists.
	 *
	 * @param	string		the array key to search for
	 *
	 * @return	boolean		true if $this->recordData has been initialized
	 * 						and the array key exists, false otherwise
	 *
	 * @author	Oliver Klee <typo3-coding@oliverklee.de>
	 */
	private function hasKey($key) {
		return ($this->isOk() && !empty($key) && isset($this->recordData[$key]));
	}


///////////////////////////////////////////////////////////////////////////////
//	Getter methods and helpers for string values
///////////////////////////////////////////////////////////////////////////////

	/**
	 * Gets a trimmed string element of the record data array.
	 * If the array has not been initialized properly, an empty string is returned instead.
	 *
	 * @param	string		key of the element to return
	 *
	 * @return	string		the corresponding element from the record data array
	 *
	 *  @author	Oliver Klee <typo3-coding@oliverklee.de>
	 */
	protected function getRecordPropertyString($key) {
		$result = $this->hasKey($key)
			? trim($this->recordData[$key]) : '';

		return $result;
	}

	/**
	 * Checks a string element of the record data array for existence and
	 * non-emptiness.
	 *
	 * @param	string		key of the element to check
	 *
	 * @return	boolean		true if the corresponding string exists and is non-empty
	 *
	 * @author	Oliver Klee <typo3-coding@oliverklee.de>
	 */
	protected function hasRecordPropertyString($key) {
		return ($this->getRecordPropertyString($key) != '');
	}



///////////////////////////////////////////////////////////////////////////////
//	Getter methods and helpers for integer values
///////////////////////////////////////////////////////////////////////////////


	/**
	 * Gets an (intval'ed) integer element of the record data array.
	 * If the array has not been initialized properly, 0 is returned instead.
	 *
	 * @param	string		key of the element to return
	 *
	 * @return	integer		the corresponding element from the record data array
	 *
	 * @author	Oliver Klee <typo3-coding@oliverklee.de>
	 */
	protected function getRecordPropertyInteger($key) {
		$result = $this->hasKey($key)
			? intval($this->recordData[$key]) : 0;

		return $result;
	}
}

?>
