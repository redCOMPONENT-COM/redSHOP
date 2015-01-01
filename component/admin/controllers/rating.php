<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerRating extends RedshopController
{
	/**
	 * Method to cancel item edit
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	/**
	 * Method to publish a list of items
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public function publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('rating_detail');

		if (!$model->publish($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_RATING_DETAIL_PUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=rating', $msg);
	}

	/**
	 * Method to unpublish a list of items
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public function unpublish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('rating_detail');

		if (!$model->publish($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_RATING_DETAIL_UNPUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=rating', $msg);
	}

	/**
	 * Method to favour a list of rating items
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public function fv_publish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('rating_detail');

		if (!$model->favoured($cid, 1))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_RATING_DETAIL_PUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=rating', $msg);
	}

	/**
	 * Method to disfavour a list of rating items
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public function fv_unpublish()
	{
		$option = JRequest::getVar('option');

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			JError::raiseError(500, JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('rating_detail');

		if (!$model->favoured($cid, 0))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_RATING_DETAIL_UNPUBLISHED_SUCCESFULLY');
		$this->setRedirect('index.php?option=' . $option . '&view=rating', $msg);
	}
}
