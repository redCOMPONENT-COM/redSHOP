<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerNewslettersubscr_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'newslettersubscr_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);

		/** @var RedshopModelNewslettersubscr_detail $model */
		$model = $this->getModel('newslettersubscr_detail');

		$userlist = $model->getuserlist();

		// Merging select option in the select box
		$temps           = array();
		$temps[0]        = new stdClass;
		$temps[0]->value = 0;
		$temps[0]->text  = JText::_('COM_REDSHOP_SELECT');
		$userlist        = array_merge($temps, $userlist);

		$this->input->set('userlist', $userlist);

		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post         = $this->input->post->getArray();
		$body         = $this->input->post->get('body', '', 'raw');
		$post["body"] = $body;

		$cid                      = $this->input->post->get('cid', array(0), 'array');
		$post ['subscription_id'] = $cid [0];

		/** @var RedshopModelNewslettersubscr_detail $model */
		$model    = $this->getModel('newslettersubscr_detail');
		$userinfo = $model->getUserFromEmail($post['email']);

		if (!empty($userinfo))
		{
			$post['email']   = $userinfo->user_email;
			$post['user_id'] = $userinfo->user_id;
			$post['name']    = $userinfo->firstname . ' ' . $userinfo->lastname;
		}

		if (empty($post['name']))
		{
			$post['name'] = !empty($post['username']) ? $post['username'] : $post['email'];
		}

		if ($row = $model->store($post))
		{
			$msg = JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_NEWSLETTER_SUBSCR_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr_detail&task=edit&cid[]=' . $row->subscription_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		/** @var RedshopModelNewslettersubscr_detail $model */
		$model = $this->getModel('newslettersubscr_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr', $msg);
	}

	public function cancel()
	{
		$msg = JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr', $msg);
	}
}
