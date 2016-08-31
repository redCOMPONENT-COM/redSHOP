<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewProduct_category extends RedshopViewAdmin
{
	public $_product = array();

	public function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_REDSHOP_PRODUCT'));
		$task = JRequest::getVar('task');
		JToolBarHelper::title(JText::_('COM_REDSHOP_PRODUCT_MANAGEMENT'), 'redshop_products48');

		if ($task == 'assignCategory')
		{
			JToolBarHelper::custom('saveProduct_Category', 'save.png', 'save_f2.png', JText::_('COM_REDSHOP_ASSIGN_CATEGORY'), false);
		}

		else
		{
			JToolBarHelper::custom('removeProduct_Category', 'delete.png', 'delete.png', JText::_('COM_REDSHOP_REMOVE_CATEGORY'), false);
		}

		JToolBarHelper::back();

		$model = $this->getModel("product_category");
		$products = $model->getProductlist();

		$product_category = new product_category;
		$categories = $product_category->getCategoryListArray();

		$lists['category'] = JHTML::_('select.genericlist', $categories, 'category_id[]',
			'class="inputbox" multiple="multiple" size="10"', 'category_id', 'category_name'
		);

		$this->products = $products;
		$this->lists = $lists;

		parent::display($tpl);
	}
}
