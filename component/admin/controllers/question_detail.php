<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopControllerQuestion_detail extends RedshopControllerForm
{
	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_item = 'question_detail';

	/**
	 * The URL view item variable.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $view_list = 'question';

	/**
	 * Save question
	 *
	 * @param   integer  $send    Send Question?
	 * @param   string   $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @todo    I know, I know this is not a proper way. But we needs to move to form way.
	 *
	 * @return  boolean  True if successful, false otherwise.
	 */
	public function save($send = 0, $urlVar = null)
	{
		$jinput = JFactory::getApplication()->input;

		$post = $jinput->post->getArray();
		$question = $jinput->post->get('question', '', 'RAW');
		$post["question"] = $question;

		$cid = $jinput->post->get('cid', array(0), 'ARRAY');

		$post['question_id'] = $cid [0];
		$model = $this->getModel('question_detail');

		if ($post['question_id'] == 0)
		{
			$post['question_date'] = time();
			$post['parent_id'] = 0;
		}

		$row = $model->save($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_QUESTION_DETAIL');
		}

		if ($send == 1)
		{
			redshopMail::getInstance()->sendAskQuestionMail($post['question_id']);
		}

		$this->setRedirect('index.php?option=com_redshop&view=question', $msg);
	}

	public function send()
	{
		$this->save(1);
	}

	public function remove()
	{
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('question_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=question', $msg);
	}

	public function removeanswer()
	{

		$cid = JRequest::getVar('aid', array(0), 'post', 'array');
		$qid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('question_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_QUESTION_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=question_detail&task=edit&cid[]=' . $qid[0], $msg);
	}

	public function sendanswer()
	{

		$cid = JRequest::getVar('aid', array(0), 'post', 'array');
		$qid = JRequest::getVar('cid', array(0), 'post', 'array');

		for ($i = 0, $in = count($cid); $i < $in; $i++)
		{
			$redshopMail = redshopMail::getInstance();
			$redshopMail->sendAskQuestionMail($cid[$i]);
		}

		$msg = JText::_('COM_REDSHOP_ANSWER_MAIL_SENT');
        $this->setRedirect('index.php?option=com_redshop&view=question_detail&layout=edit&question_id=' . $qid[0], $msg);
	}

	/**
	 * logic for save an order
	 *
	 * @access public
	 * @return void
	 */
	public function saveorder()
	{
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		$order = JRequest::getVar('order', array(), 'post', 'array');

		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);
		$model = $this->getModel('question_detail');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=question', $msg);
	}
}
