<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

class newslettersubscr_detailController extends JController
{
	public function __construct($default = array())
	{
		parent::__construct($default);
		$this->registerTask('add', 'edit');
	}

	public function edit()
	{
		JRequest::setVar('view', 'newslettersubscr_detail');
		JRequest::setVar('layout', 'default');
		JRequest::setVar('hidemainmenu', 1);

		$model = $this->getModel('newslettersubscr_detail');

		$userlist = $model->getuserlist();

		// Merging select option in the select box
		$temps = array();
		$temps[0] = new stdClass;
		$temps[0]->value = 0;
		$temps[0]->text = JText::_('COM_REDSHOP_SELECT');
		$userlist = array_merge($temps, $userlist);

		JRequest::setVar('userlist', $userlist);

		parent::display();
	}

	public function apply()
	{
		$this->save(1);
	}

	public function save($apply = 0)
	{
		$post = JRequest::get('post');
		$body = JRequest::getVar('body', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$post["body"] = $body;

		$option = JRequest::getVar('option');
		$cid = JRequest::getVar('cid', array(0), 'post', 'array');
		$post ['subscription_id'] = $cid [0];
		$model = $this->getModel('newslettersubscr_detail');
		$userinfo = $model->getUserFromEmail($post['email']);

		if (count($userinfo) > 0)
		{
			$post['email'] = $userinfo->user_email;
			$post['user_id'] = $userinfo->user_id;
		}

		$post ['name'] = $post['username'];

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
			$this->setRedirect('index.php?option=' . $option . '&view=newslettersubscr_detail&task=edit&cid[]=' . $row->subscription_id, $msg);
		}
		else
		{
			$this->setRedirect('index.php?option=' . $option . '&view=newslettersubscr', $msg);
		}
	}

	public function remove()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('newslettersubscr_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DETAIL_DELETED_SUCCESSFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=newslettersubscr', $msg);
	}

	public function publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('newslettersubscr_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DETAIL_PUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=newslettersubscr', $msg);
	}

	public function unpublish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('newslettersubscr_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DETAIL_UNPUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=newslettersubscr', $msg);
	}

	public function cancel()
	{
		$option = JRequest::getVar('option');
		$msg = JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DETAIL_EDITING_CANCELLED');
		$this->setRedirect('index.php?option=' . $option . '&view=newslettersubscr', $msg);
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

		for ($i = 0; $i < count($data); $i++)
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

		exit;
	}

	public function export_acy_data()
	{
		ob_clean();
		$model = $this->getModel('newslettersubscr_detail');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		$order_function = new order_functions;
		$data = $model->getnewslettersbsc($cid);

		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-type: text/x-csv");
		header("Content-type: text/csv");
		header("Content-type: application/csv");
		header('Content-Disposition: attachment; filename=import_to_acyba.csv');

		echo '"email","name","enabled"';
		echo "\n";

		for ($i = 0; $i < count($data); $i++)
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

		exit;
	}
}
