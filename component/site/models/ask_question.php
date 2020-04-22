<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class ask question model
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopModelAsk_Question extends RedshopModelForm
{
	/**
	 * @var string
	 */
	protected $context = 'com_redshop.ask_question';

	/**
	 * Method to get the record form.
	 *
	 * @param   array   $data     Data for the form.
	 * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.5
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_redshop.ask_question', 'ask_question', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  array    The default data is an empty array.
	 * @throws  Exception
	 *
	 * @since   1.5
	 */
	protected function loadFormData()
	{
		$data = (array) JFactory::getApplication()->getUserState('com_redshop.ask_question.data', array());

		return $data;
	}

	/**
	 * Method to store the records
	 *
	 * @param   array $data array of data
	 *
	 * @return  boolean
	 * @throws  Exception
	 */
	public function store($data)
	{
		$user                  = JFactory::getUser();
		$data['user_id']       = $user->id;
		$data['user_name']     = $data['your_name'];
		$data['user_email']    = $data['your_email'];
		$data['question']      = $data['your_question'];
		$data['published']     = 1;
		$data['question_date'] = time();

		$row = $this->getTable('Question');

		$data['ordering'] = $this->maxOrdering();

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

		return true;
	}

	/**
	 * Method to get max ordering
	 *
	 * @access public
	 *
	 * @return boolean
	 */
	public function maxOrdering()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('MAX(ordering)+1')
			->from($db->qn('#__redshop_customer_question'))
			->where('parent_id = 0');

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Send Mail For Ask Question
	 *
	 * @param   array $data Question data
	 *
	 * @return  boolean
	 * @throws  Exception
	 */
	public function sendMailForAskQuestion($data)
	{
		if (!$this->store($data))
		{
			return false;
		}

		if (!Redshop\Mail\AskQuestion::sendAskQuestion($data))
		{
			$this->setError(JText::_('COM_REDSHOP_EMAIL_HAS_NOT_BEEN_SENT_SUCCESSFULLY'));

			return false;
		}

		return true;
	}
}
