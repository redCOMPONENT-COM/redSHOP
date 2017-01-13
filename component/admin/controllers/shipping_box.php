<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerShipping_box extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	/**
	 * [remove description]
	 *
	 * @return  [void]
	 */
	public function remove()
	{
		$showall = JRequest::getVar('showall', '0');
		$tmpl = '';

		if ($showall)
		{
			$tmpl = '&tmpl=component';
		}

		$cid = JRequest::getVar('cid', array(0), 'post', 'array');

		if (!is_array($cid) || count($cid) < 1)
		{
			throw new Exception(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
		}

		$model = $this->getModel('shipping_box_detail');

		if (!$model->delete($cid))
		{
			echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
		}

		$msg = JText::_('COM_REDSHOP_SHIPPING_BOXES_DELETE_SUCCESSFULLY');
		$this->setRedirect('index.php?option=com_redshop&view=shipping_box', $msg);
	}
}
