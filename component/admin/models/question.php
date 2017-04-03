<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Model Country
 *
 * @package     RedSHOP.Backend
 * @subpackage  Model
 * @since       2.0.3
 */

class RedshopModelQuestion extends RedshopModelForm
{
	/**
	 * Method for get all answers of specific question
	 *
	 * @param   int  $id  Question ID
	 *
	 * @return  array
	 */
	public function getAnswers($id = 0)
	{
		if (!$id)
		{
			return array();
		}

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

		return $db->loadObjectList();
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
		if (!parent::save($data))
		{
			return false;
		}

		if (empty($data['answer']))
		{
			return true;
		}

		if ($data['parent_id'])
		{
			return true;
		}

		$user = JFactory::getUser();

		// Store Answer
		$answerData = $data;
		$answerData['id'] = 0;
		$answerData['parent_id'] = $data['id']? $data['id']: $this->_db->insertid();
		$answerData['question']  = '';
		$answerData['cdate']     = time();
		$answerData['question']    = $data['answer'];
		$answerData['user_email'] = $user->email;
		$answerData['user_name'] = $user->name;

		return $this->save($answerData);
	}
}
