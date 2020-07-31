<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Wrappers controller
 *
 * @package     RedSHOP.backend
 * @subpackage  Controller
 * @since       __DEPLPOY_VERSION__
 */
class RedshopControllerWrappers extends RedshopControllerAdmin
{
	public function useToAllpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1) {
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
		}

		$model = $this->getModel('wrappers');

		if (!$model->useToAllpublish($cid, 1)) {
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_USE_TO_ALL_ENABLE_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=wrappers', $msg);
	}

	public function useToAllunpublish()
	{
		$cid = $this->input->post->get('cid', array(0), 'array');

		if (!is_array($cid) || count($cid) < 1) {
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
		}

		$model = $this->getModel('wrappers');

		if (!$model->useToAllpublish($cid, 0)) {
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_USE_TO_ALL_DISABLE_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=wrappers', $msg);
	}
}
