<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

class RedshopControllerNewslettersubscr extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	public function publish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		/** @var RedshopModelNewslettersubscr_detail $model */
		$model = $this->getModel('newslettersubscr_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . /** @scrutinizer ignore-deprecated */ $model->getError() . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DETAIL_PUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr', $msg);
	}

	public function unpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		/** @var RedshopModelNewslettersubscr_detail $model */
		$model = $this->getModel('newslettersubscr_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . /** @scrutinizer ignore-deprecated */ $model->getError() . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_NEWSLETTER_SUBSCR_DETAIL_UNPUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=newslettersubscr', $msg);
	}
}
