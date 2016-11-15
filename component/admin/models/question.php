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
	 * @param   int  $id  Question ID
	 * 
	 * @return objectList
	 */
	public function getAnswers($id = 0)
	{
		if ($id > 0)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query ->select(
				$db->qn(
					[
						'id', 'parent_id', 'question', 'user_id',
						'user_name', 'user_email', 'published', 'question_date',
						'ordering', 'telephone', 'address'
					]
				)
			)
			->from($db->qn('#__redshop_customer_question'))
			->where($db->qn('parent_id') . ' = ' . $id);

			$db->setQuery($query);
			$this->answers = $db->loadObjectList();
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
	 * @since   2.0.0.4
	 */
	public function save($data)
	{
		$table = $this->getTable();

		// Store Answer
		if ($table->save($data)
			&& (isset($data['answer']) && trim($data['answer']) != ''))
		{
			// Prepare array for answer
			$answerData                = $data;
			$answerData['id'] = 0;
			$answerData['parent_id']   = $data['id']? $data['id']: $table->id;
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
