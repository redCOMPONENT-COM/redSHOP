<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/mail.php';

class answer_detailModelanswer_detail extends JModel
{
	public $_id = null;

	public $_parent_id = null;

	public $_data = null;

	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
		$this->_parent_id = JRequest::getVar('parent_id');
		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int) $array[0]);
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function &getData()
	{
		if ($this->_loadData())
		{
		}
		else
		{
			$this->_initData();
		}

		return $this->_data;
	}

	public function _loadData()
	{
		$query = "SELECT q.* FROM " . $this->_table_prefix . "customer_question AS q "
			. "WHERE q.question_id=" . $this->_id;
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadObject();

		return (boolean) $this->_data;
	}

	public function getProduct()
	{
		$query = "SELECT * FROM " . $this->_table_prefix . "product ";
		$list = $this->_getList($query);

		return $list;
	}

	public function _initData()
	{
		$user = JFactory::getUser();

		if (empty($this->_data))
		{
			$detail = new stdClass;
			$detail->question_id = 0;
			$detail->product_id = null;
			$detail->parent_id = $this->_parent_id;
			$detail->user_id = $user->id;
			$detail->user_name = $user->name;
			$detail->user_email = $user->email;
			$detail->question = null;
			$detail->published = 1;
			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	/**
	 * Method to store the information
	 *
	 * @access public
	 * @return boolean
	 */
	public function store($data)
	{
		$row =& $this->getTable('question_detail');

		if (!$data['question_id'])
		{
			$data['ordering'] = $this->MaxOrdering();
		}

		if (!$row->bind($data))
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		if (!$row->store())
		{
			$this->setError($this->_db->getErrorMsg());

			return false;
		}

		return $row;
	}

	/**
	 * Method to get max ordering
	 *
	 * @access public
	 * @return boolean
	 */
	public function MaxOrdering()
	{
		$query = "SELECT (MAX(ordering)+1) FROM " . $this->_table_prefix . "customer_question "
			. "WHERE parent_id='" . $this->_parent_id . "' ";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	/**
	 * Method to delete the records
	 *
	 * @access public
	 * @return boolean
	 */
	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'DELETE FROM ' . $this->_table_prefix . 'customer_question '
				. 'WHERE question_id IN (' . $cids . ')';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	/**
	 * Method to publish the records
	 *
	 * @access public
	 * @return boolean
	 */
	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE ' . $this->_table_prefix . 'customer_question '
				. ' SET published = ' . intval($publish)
				. ' WHERE question_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->query())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	/**
	 * Method to save order
	 *
	 * @access public
	 * @return boolean
	 */
	public function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable('question_detail');
		$order = JRequest::getVar('order', array(0), 'post', 'array');
		$groupings = array();
		$conditions = array();

		// Update ordering values
		for ($i = 0; $i < count($cid); $i++)
		{
			$row->load((int) $cid[$i]);

			// Track categories
			$groupings[] = $row->question_id;

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];

				if (!$row->store())
				{
					$this->setError($this->_db->getErrorMsg());

					return false;
				}

				// Remember to updateOrder this group
				$condition = 'parent_id = ' . (int) $row->parent_id;
				$found = false;

				foreach ($conditions as $cond)
				{
					if ($cond[1] == $condition)
					{
						$found = true;
						break;
					}
				}

				if (!$found)
				{
					$conditions[] = array($row->question_id, $condition);
				}
			}
		}

		foreach ($conditions as $cond)
		{
			$row->load($cond[0]);
			$row->reorder($cond[1]);
		}

		return true;
	}

	/**
	 * Method to up order
	 *
	 * @access public
	 * @return boolean
	 */
	public function orderup()
	{
		$row =& $this->getTable('question_detail');
		$row->load($this->_id);
		$row->move(-1, 'parent_id= ' . (int) $row->parent_id);
		$row->store();

		return true;
	}

	/**
	 * Method to down the order
	 *
	 * @access public
	 * @return boolean
	 */
	public function orderdown()
	{
		$row =& $this->getTable('question_detail');
		$row->load($this->_id);
		$row->move(1, 'parent_id = ' . (int) $row->parent_id);
		$row->store();

		return true;
	}

	public function sendMailForAskQuestion($ansid)
	{
		$redshopMail = new redshopMail;
		$rs = $redshopMail->sendAskQuestionMail($ansid);

		return $rs;
	}
}
