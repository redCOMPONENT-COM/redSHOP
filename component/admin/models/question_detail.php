<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;



class RedshopModelQuestion_detail extends RedshopModel
{
	public $_id = null;

	public $_data = null;

	public $_answers = null;

	public function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->setId((int) $array[0]);
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function getanswers()
	{
		$this->_loadAnswer();

		return $this->_answers;

	}

	public function _loadAnswer()
	{
		if ($this->_id > 0)
		{
			$query = "SELECT q.* FROM #__redshop_customer_question AS q "
				. "WHERE q.parent_id=" . $this->_id;
			$this->_db->setQuery($query);
			$this->_answers = $this->_db->loadObjectList();
		}
		else
		{
			$this->_answers = array();
		}

		return $this->_answers;
	}

	public function getData()
	{
		if (!$this->_loadData())
		{
			$this->_initData();
		}

		return $this->_data;
	}

	public function _loadData()
	{
		$query = "SELECT q.* FROM #__redshop_customer_question AS q "
			. "WHERE q.question_id=" . $this->_id;
		$this->_db->setQuery($query);
		$this->_data = $this->_db->loadObject();

		return (boolean) $this->_data;
	}

	public function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	public function _buildQuery()
	{
		$query = "SELECT q.* FROM #__redshop_customer_question AS q "
			. "WHERE q.parent_id=" . $this->_id;

		return $query;
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}

	public function _initData()
	{
		$user = JFactory::getUser();

		if (empty($this->_data))
		{
			$detail              = new stdClass;
			$detail->question_id = 0;
			$detail->product_id  = null;
			$detail->parent_id   = null;
			$detail->user_id     = $user->id;
			$detail->user_name   = $user->name;
			$detail->user_email  = $user->email;
			$detail->question    = null;
			$detail->telephone   = null;
			$detail->address     = null;
			$detail->published   = 1;
			$this->_data         = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function store($data)
	{
		$user = JFactory::getUser();
		$db   = JFactory::getDbo();
		$row  = $this->getTable();

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

		$data['ordering'] = $this->MaxOrdering();

		// Store Answer
		if (isset($data['answer']) && trim($data['answer']) != '')
		{
			if (!(int) $data['question_id'])
			{
				$data['question_id'] = $db->insertid();
			}

			// Prepare Answer table
			$answers = $this->getTable();

			$answers->question_id   = 0;

			// Question Id for which we are adding answer
			$answers->parent_id     = $data['question_id'];
			$answers->product_id    = $data['product_id'];
			$answers->question      = $data['answer'];
			$answers->user_id       = $user->id;
			$answers->user_name     = $user->username;
			$answers->user_email    = $user->email;
			$answers->published     = 1;
			$answers->question_date = time();
			$answers->ordering      = $data['ordering'];

			if (!$answers->store())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
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
		$query = "SELECT (MAX(ordering)+1) FROM #__redshop_customer_question "
			. "WHERE parent_id=0 ";
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

			$query = 'DELETE FROM #__redshop_customer_question '
				. 'WHERE parent_id IN (' . $cids . ')';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}

			$query = 'DELETE FROM #__redshop_customer_question '
				. 'WHERE question_id IN (' . $cids . ')';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
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

			$query = 'UPDATE #__redshop_customer_question '
				. ' SET published = ' . intval($publish)
				. ' WHERE question_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
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
		$row = $this->getTable();
		$order = JRequest::getVar('order', array(0), 'post', 'array');
		$groupings = array();

		// Update ordering values
		for ($i = 0, $in = count($cid); $i < $in; $i++)
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
			}
		}

		// Execute updateOrder for each parent group
		$groupings = array_unique($groupings);

		foreach ($groupings as $group)
		{
			$row->reorder((int) $group);
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
		$row = $this->getTable();
		$row->load($this->_id);
		$row->move(-1);
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
		$row = $this->getTable();
		$row->load($this->_id);
		$row->move(1);
		$row->store();

		return true;
	}

	public function sendMailForAskQuestion($ansid)
	{
		$redshopMail = redshopMail::getInstance();
		$rs = $redshopMail->sendAskQuestionMail($ansid);

		return $rs;
	}
}
