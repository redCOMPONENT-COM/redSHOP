<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Country
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       [version> [<description>]
 */

class RedshopModelQuestion extends RedshopModelForm
{
	/**
	 * [getanswers description]
	 * 
	 * @return object
	 */
	public function getanswers()
	{
		if ($this->id > 0)
		{
			$query = "SELECT q.* FROM #__redshop_customer_question AS q "
				. "WHERE q.parent_id=" . $this->_id;
			$this->_db->setQuery($query);
			$this->_answers = $this->_db->loadObjectList();
		}
		else
		{
			$this->answers = array();
		}

		return $this->answers;
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
			$answerData['id'] = 0;
			$answerData['parent_id']   = $data['id'];
			$answerData['question']    = $data['answer'];

			parent::save($answerData);
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
		$row->load($this->id);
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
		$row->load($this->id);
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
	public function getTable($name = 'Question', $prefix = 'RedshopTable', $config = array())
	{
		return parent::getTable($name, $prefix, $config);
	}
}
