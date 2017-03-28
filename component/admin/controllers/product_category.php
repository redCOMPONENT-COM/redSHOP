<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopControllerProduct_category extends RedshopController
{
	public function assignCategory()
	{
		$this->input->set('hidemainmenu', 1);
		parent::display();
	}

	public function saveProduct_Category()
	{
		$app = JFactory::getApplication();
		$model = $this->getModel("product_category");

		if ($model->saveProduct_Category())
		{
			$msg = JText::_('COM_REDSHOP_CATEGORY_ASSIGNED_TO_PRODUCT_SUCCESSFULLY');
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_WHILE_ASSIGNING_CATEGORY_TO_PRODUCT');
		}

		$app->redirect("index.php?option=com_redshop&view=product", $msg);
	}

	public function removeProduct_Category()
	{
		$app = JFactory::getApplication();
		$model = $this->getModel("product_category");

		if ($model->removeProduct_Category())
		{
			$msg = JText::_('COM_REDSHOP_CATEGORY_REMOVED_FROM_PRODUCT_SUCCESSFULLY');
		}

		else
		{
			$msg = JText::_('COM_REDSHOP_ERROR_WHILE_REMOVING_CATEGORY_FROM_PRODUCT');
		}

		$app->redirect("index.php?option=com_redshop&view=product", $msg);
	}
}
