<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerCategory extends RedshopController
{
	public function cancel()
	{
		$this->setRedirect('index.php');
	}

	/**
	 * assign template to multiple categories
	 *
	 */
	public function assignTemplate()
	{
		$post = $this->input->post->getArray();

		$model = $this->getModel('category');

		if ($model->assignTemplate($post))
		{
			$msg = JText::_('COM_REDSHOP_TEMPLATE_ASSIGN_SUCESS');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_ASSIGNING_TEMPLATE');
		}

		$this->setRedirect('index.php?option=com_redshop&view=category', $msg);
	}

	public function saveorder()
	{
		$cid = $this->input->post->get('cid', array(), 'array');
		$order = $this->input->post->get('order', array(), 'array');
		JArrayHelper::toInteger($cid);
		JArrayHelper::toInteger($order);

		$model = $this->getModel('category');
		$model->saveorder($cid, $order);

		$msg = JText::_('COM_REDSHOP_NEW_ORDERING_SAVED');
		$this->setRedirect('index.php?option=com_redshop&view=category', $msg);
	}

	public function autofillcityname()
	{
		$db = JFactory::getDbo();
		ob_clean();
		$mainzipcode = $this->input->getString('q', '');
		$sel_zipcode = "select city_name from #__redshop_zipcode where zipcode='" . $mainzipcode . "'";
		$db->setQuery($sel_zipcode);

		echo $db->loadResult();
		exit;
	}
}

