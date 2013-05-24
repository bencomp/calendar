<?php
/**
 * Copyright (c) 2013 Georg Ehrke <oc.list@georgehrke.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */
namespace OCA\Calendar\Db;

use \OCA\AppFramework\Core\API;
use \OCA\AppFramework\Db\Mapper;
use \OCA\AppFramework\Db\DoesNotExistException;

use \OCA\Calendar\Db\Calendar;

class CalendarCacheMapper extends Mapper {


	private $tableName;

	/**
	 * @param API $api: Instance of the API abstraction layer
	 */
	public function __construct($api, $tablename = 'clndr_calcache'){
		parent::__construct($api, $tablename);
		$this->tableName = '*PREFIX*' . $tablename;
	}


	/**
	 * Finds an item by id
	 * @throws DoesNotExistException: if the item does not exist
	 * @return the item
	 */
	public function find($id){
		$row = $this->findQuery($this->tableName, $id);
		return new Calendar($row);
	}


	/**
	 * Finds all Items
	 * @return array containing all items
	 */
	public function findAll($userid = null){
		if(is_null($userid)){
			return false;
		}
		$sql = 'SELECT * FROM `'. $this->tableName . '` WHERE `userid` = ?';
		$result = $this->execute($sql, array($userid));

		$entityList = array();
		while($row = $result->fetchRow()){
			unset($row['id']);
			$entity = new Calendar($row);
			array_push($entityList, $entity);
		}

		return $entityList;
	}


	/**
	 * Finds all Items where enabled is ?
	 * @return array containing all items where enabled is ?
	 */
	public function findWhereEnabledIs($isenabled){
		$sql = 'SELECT * FROM `'. $this->tableName . '` WHERE `enabled` = ?';
		$result = $this->execute($sql, array($isenabled));

		$entityList = array();
		while($row = $result->fetchRow()){
			$entity = new CalendarCalendar($row);
			array_push($entityList, $entity);
		}
		return $entityList;
	}


	/**
	 * Saves an item into the database
	 * @param Item $item: the item to be saved
	 * @return the item with the filled in id
	 */
	public function save($item){
		$sql = 'INSERT INTO `'. $this->tableName . '`(`backend`, `classname`, `arguments`, `enabled`)'.
				' VALUES(?, ?, ?, ?)';

		$params = array(
			$item->getBackend(),
			$item->getClassname(),
			$item->getArguments(),
			$item->getEnabled()
		);

		$this->execute($sql, $params);

		$item->setId($this->api->getInsertId($this->tableName));
	}

	/**
	 * Updates an item
	 * @param Item $item: the item to be updated
	 */
	/*public function update($item){
		$sql = 'UPDATE `'. $this->tableName . '` SET
				`backend` = ?,
				`classname` = ?,
				`arguments` = ?,
				`enabled` = ?
				WHERE `id` = ?';

		$params = array(
			$item->getBackend(),
			$item->getClassname(),
			$item->getArguments(),
			$item->getEnabled(),
			$item->getId()
		);

		$this->execute($sql, $params);
	}*/


	/**
	 * Deletes an item
	 * @param int $id: the id of the item
	 */
	/*public function delete($id){
		$this->deleteQuery($this->tableName, $id);
	}*/


}