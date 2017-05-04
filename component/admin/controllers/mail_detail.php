<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerMail_detail extends RedshopController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		$this->input->set('view', 'mail_detail');
		$this->input->set('layout', 'default');
		$this->input->set('hidemainmenu', 1);

		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post = $this->input->post->getArray();

		$mail_body = $this->input->post->get('mail_body', '', 'raw');

		$post["mail_body"] = $mail_body;

		$cid = $this->input->post->get('cid', array(0), 'array');

		$post ['mail_id'] = $cid [0];

		if ($post['mail_section'] != 'order_status')
		{
			$post['mail_order_status'] = 0;
		}

		$model = $this->getModel('mail_detail');
		$row = $model->store($post);

		if ($row)
		{
			$msg = JText::_('COM_REDSHOP_MAIL_DETAIL_SAVED');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_SAVING_MAIL_DETAIL');
		}

		if ($apply == 1)
		{
			$this->setRedirect(
				'index.php?option=com_redshop&view=mail_detail&task=edit&cid[]=' . $row->mail_id . '&templateMode=' . $post['templateMode'],
				$msg
			);
		}
		else
		{
			$this->setRedirect('index.php?option=com_redshop&view=mail', $msg);
		}
	}

	public function remove()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('mail_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_MAIL_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=mail', $msg);
	}

	public function cancel()
	{

		$msg = JText::_('COM_REDSHOP_MAIL_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=com_redshop&view=mail', $msg);
	}

	public function mail_section()
	{
		$model = $this->getModel('mail_detail');

		$order_status = $model->mail_section();
		$order_statusHtml = $model->order_statusHtml($order_status);

		$json = array();

		$json['order_status'] = $order_status;
		$json['order_statusHtml'] = $order_statusHtml;

		$encoded = json_encode($json);
		die($encoded);
	}
}
