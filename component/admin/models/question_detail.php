<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelQuestion_detail extends RedshopModelForm
{
	public $_id = null;

	public $_data = null;

	public $_answers = null;

	public function __construct()
	{
		parent::__construct();

		$this->setId(JFactory::getApplication()->input->getInt('question_id', 0));
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

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success, False on error.
	 *
	 * @since   12.2
	 */
	public function save($data)
	{
		// Store Answer
		if (parent::save($data)
			&& (isset($data['answer']) && trim($data['answer']) != ''))
		{
			// Prepare array for answer
			$answerData                = $data;
			$answerData['question_id'] = 0;
			$answerData['parent_id']   = (int) $this->state->get($this->getName() . '.id');
			$answerData['question']    = $data['answer'];

			parent::save($answerData);
		}

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
		$query = "SELECT (MAX(ordering)+1) FROM #__redshop_customer_question "
			. "WHERE parent_id=0 ";
		$this->_db->setQuery($query);

		return $this->_db->loadResult();
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   array  &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   12.2
	 */
	public function delete(&$pks)
	{
		// Remove answer of the question after removing questions.
		if (parent::delete($pks) && !empty($pks))
		{
			// Initialiase variables.
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true)
						->delete()
						->from($db->qn('#__redshop_customer_question'))
						->where($db->qn('parent_id') . ' IN(' . implode(',', $pks) . ')');

			// Set the query and execute the delete.
			$db->setQuery($query);

			try
			{
				$db->execute();
			}
			catch (RuntimeException $e)
			{
				$this->setError($e->getMessage());

				return false;
			}
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

	/**
	 * Transparent proxy to get table
	 *
	 * @param   string  $name    Table name
	 * @param   string  $prefix  Class prefix
	 * @param   array   $config  Config
	 *
	 * @return  JTable
	 *
	 * @since   2.0.0.4
	 */
	public function getTable($name = 'question_detail', $prefix = 'Table', $config = array())
	{
		return parent::getTable($name, $prefix, $config);
	}
}
