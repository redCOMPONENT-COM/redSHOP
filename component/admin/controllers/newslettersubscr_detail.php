<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
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

		$model = $this->getModel('newslettersubscr_detail');

		$userlist = $model->getuserlist();

		// Merging select option in the select box
		$temps = array();
		$temps[0] = new stdClass;
		$temps[0]->value = 0;
		$temps[0]->text = JText::_('COM_REDSHOP_SELECT');
		$userlist = array_merge($temps, $userlist);

		$this->input->set('userlist', $userlist);

		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post = $this->input->post->getArray();
		$body = $this->input->post->get('body', '', 'raw');
		$post["body"] = $body;

		$cid = $this->input->post->get('cid', array(0), 'array');
		$post ['subscription_id'] = $cid [0];
		$model = $this->getModel('newslettersubscr_detail');
		$userinfo = $model->getUserFromEmail($post['email']);

		if (!empty($userinfo))
		{
			$post['email'] = $userinfo->user_email;
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

	public function export_data()
	{
		$model = $this->getModel('newslettersubscr_detail');

		while (@ob_end_clean());

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=NewsletterSbsc.csv');

		echo "Subscriber Full Name,Newsletter,Email Id\n";
		$data = $model->getnewslettersbsc();

		for ($i = 0, $in = count($data); $i < $in; $i++)
		{
			$subname = $model->getuserfullname($data[$i]->user_id);

			if ($data[$i]->user_id != 0)
			{
				echo utf8_decode($subname->firstname) . " " . utf8_decode($subname->lastname);
			}
			else
			{
				echo utf8_decode($data[$i]->subscribername);
			}

			echo ",";
			echo $data[$i]->name . ",";

			if ($data[$i]->user_id != 0)
			{
				echo $subname->email . ",";
			}
			else
			{
				echo $data[$i]->email . ",";
			}

			echo "\n";
		}

		JFactory::getApplication()->close();
	}

	public function export_acy_data()
	{
		ob_clean();
		$model = $this->getModel('newslettersubscr_detail');
		$cid = $this->input->post->get('cid', array(), 'array');
		$order_function = order_functions::getInstance();
		$data = $model->getnewslettersbsc($cid);

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=import_to_acyba.csv');

		echo '"email","name","enabled"';
		echo "\n";

		for ($i = 0, $in = count($data); $i < $in; $i++)
		{
			echo '"' . $data[$i]->email . '","';

			if ($data[$i]->user_id != 0)
			{
				$subname = $order_function->getUserFullname($data[$i]->user_id);
				echo $subname;
			}
			else
			{
				echo $data[$i]->subscribername;
			}

			echo '","';
			echo $data[$i]->published . '"';
			echo "\n";
		}

		JFactory::getApplication()->close();
	}
}
