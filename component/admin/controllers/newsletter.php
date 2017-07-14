<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerNewsletter extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function send_newsletter_preview()
	{
		$this->getView('newsletter', 'preview');
		parent::display();
	}

	public function send_newsletter()
	{
		$session = JFactory::getSession();

		$cid = $this->input->post->get('cid', array(0), 'array');
		$userid = $this->input->post->get('userid', array(0), 'array');
		$username = $this->input->post->get('username', array(0), 'array');

		$newsletter_id = $this->input->get('newsletter_id');

		$tmpcid = array_chunk($cid, Redshop::getConfig()->get('NEWSLETTER_MAIL_CHUNK'));
		$tmpuserid = array_chunk($userid, Redshop::getConfig()->get('NEWSLETTER_MAIL_CHUNK'));
		$tmpusername = array_chunk($username, Redshop::getConfig()->get('NEWSLETTER_MAIL_CHUNK'));

		$session->set('subscribers', $tmpcid);
		$session->set('subscribersuid', $tmpuserid);
		$session->set('subscribersuname', $tmpusername);
		$session->set('incNo', 1);

		$this->setRedirect('index.php?option=com_redshop&view=newsletter&layout=previewlog&newsletter_id=' . $newsletter_id);

		return;
	}

	public function sendRecursiveNewsletter()
	{
		$session = JFactory::getSession();
		$newsletter_id = $this->input->get('newsletter_id');

		$model = $this->getModel('newsletter');

		$subscribers = $session->get('subscribers');
		$subscribersuid = $session->get('subscribersuid');
		$subscribersuname = $session->get('subscribersuname');
		$incNo = $session->get('incNo');

		$cid = array();
		$user_id = array();
		$username = array();

		if (count($subscribers) > 0)
		{
			$cid = $subscribers[0];
			unset($subscribers[0]);
			$subscribers = array_merge(array(), $subscribers);
		}

		if (count($subscribersuid) > 0)
		{
			$user_id = $subscribersuid[0];
			unset($subscribersuid[0]);
			$subscribersuid = array_merge(array(), $subscribersuid);
		}

		if (count($subscribersuname) > 0)
		{
			$username = $subscribersuname[0];
			unset($subscribersuname[0]);
			$subscribersuname = array_merge(array(), $subscribersuname);
		}

		$retuser = $model->newsletterEntry($cid, $user_id, $username);

		$responcemsg = "";

		for ($i = 0, $in = count($cid); $i < $in; $i++)
		{
			$subscriber = $model->getNewsletterSubscriber($newsletter_id, $cid[$i]);
			$responcemsg .= "<div>" . $incNo . ": " . $subscriber->name . "( " . $subscriber->email . " ) -> ";

			if ($retuser[$i])
			{
				$responcemsg .= "<span style='color: #00ff00'>" . JText::_('COM_REDSHOP_NEWSLETTER_SENT_SUCCESSFULLY') . "</span>";
			}
			else
			{
				$responcemsg .= "<span style='color: #ff0000'>" . JText::_('COM_REDSHOP_NEWSLETTER_MAIL_NOT_SENT') . "</span>";
			}

			$responcemsg .= "</div>";
			$incNo++;
		}

		$session->set('subscribers', $subscribers);
		$session->set('subscribersuid', $subscribersuid);
		$session->set('subscribersuname', $subscribersuname);
		$session->set('incNo', $incNo);

		if (count($cid) == 0)
		{
			$session->clear('subscribers');
			$session->clear('subscribersuid');
			$session->clear('subscribersuname');
			$session->clear('incNo');
		}

		$responcemsg = "<div id='sentresponse'>" . $responcemsg . "</div>";
		echo $responcemsg;

		JFactory::getApplication()->close();
	}

	public function publish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('newsletter_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_DETAIL_PUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=newsletter', $msg);
	}

	public function unpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('newsletter_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_DETAIL_UNPUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=newsletter', $msg);
	}
}
