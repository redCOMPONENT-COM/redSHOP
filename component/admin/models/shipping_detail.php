<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


jimport('joomla.installer.installer');
jimport('joomla.installer.helper');
jimport('joomla.filesystem.file');

class RedshopModelShipping_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_copydata = null;

	public $sectionCondition = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';

		$array = JRequest::getVar('cid', 0, '', 'array');

		$this->setId((int) $array[0]);
		$db = JFactory::getDbo();
		$this->sectionCondition = array(
			'folder = ' . $db->q('redshop_shipping'),
			'type = ' . $db->q('plugin')
		);
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function &getData()
	{
		$this->_loadData();

		return $this->_data;
	}

	public function _loadData()
	{
		$query = 'SELECT * FROM #__extensions WHERE folder="redshop_shipping" and extension_id ="' . $this->_id . '" ';
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadObject();

		return (boolean) $this->_data;
	}

	public function store($data)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->update($db->qn('#__extensions'))
			->set('enabled = ' . (int) $data['published'])
			->where('element = ' . $db->q($data['element']));

		if (!$db->setQuery($query)->execute())
		{
			$this->setError($db->getErrorMsg());

			return false;
		}

		JPluginHelper::importPlugin('redshop_shipping');
		$dispatcher = RedshopHelperUtility::getDispatcher();
		$dispatcher->trigger('onWriteconfig', array($data));

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'UPDATE #__extensions'
				. ' SET enabled = ' . intval($publish)
				. ' WHERE  extension_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function saveOrder($cid, $order)
	{
		$db = JFactory::getDbo();
		$row = $this->getTable();
		$total = count($cid);

		// Update ordering values
		for ($i = 0; $i < $total; $i++)
		{
			$row->load((int) $cid[$i]);

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store())
				{
					throw new Exception($db->getErrorMsg());
				}
			}
		}

		$row->reorder($this->sectionCondition);

		return true;
	}

	/**
	 * Method to get max ordering
	 *
	 * @access public
	 * @return boolean
	 */
	public function MaxOrdering()
	{
		$query = "SELECT (max(ordering)+1) FROM #__extensions where folder='redshop_shipping'";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	/**
	 * Method to move
	 *
	 * @param   string  $direction  Direction
	 *
	 * @return  boolean True on success
	 */
	public function move($direction)
	{
		$row = JTable::getInstance('shipping_detail', 'Table');

		if (!$row->load($this->_id))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->move($direction, $this->sectionCondition))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		$row->reorder($this->sectionCondition);

		return true;
	}
}
