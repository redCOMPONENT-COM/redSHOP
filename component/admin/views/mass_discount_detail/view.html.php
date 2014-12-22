<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


JLoader::load('RedshopHelperAdminCategory');

class RedshopViewMass_discount_detail extends RedshopView
{
	public function display($tpl = null)
	{
		JToolBarHelper::title(JText::_('COM_REDSHOP_DISCOUNT_MANAGEMENT_DETAIL'), 'redshop_discountmanagmenet48');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$product_category = new product_category;

		$option = JRequest::getVar('option');

		$isNew = ($detail->mass_discount_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_DISCOUNT') . ': <small><small>[ ' . $text . ' ]</small></small>', 'redshop_discountmanagmenet48');
		JToolBarHelper::apply();
		JToolBarHelper::save();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$model = $this->getModel('mass_discount_detail');

		$manufacturers = $model->getmanufacturers();

		$productData = $model->GetProductList();

		$category_id = explode(',', $detail->category_id);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $category_id);

		$discount_type = array(JHTML::_('select.option', 'no', JText::_('COM_REDSHOP_SELECT')),
			JHTML::_('select.option', 0, JText::_('COM_REDSHOP_TOTAL')),
			JHTML::_('select.option', 1, JText::_('COM_REDSHOP_PERCENTAGE'))
		);

		$lists['discount_type'] = JHTML::_('select.genericlist', $discount_type, 'discount_type',
			'class="inputbox" size="1"', 'value', 'text', $detail->discount_type
		);

		$categories = $product_category->list_all("category_id[]", 0, $category_id, 10, true, true);
		$lists['categories'] = $categories;

		$manufacturer_id = explode(',', $detail->manufacturer_id);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $manufacturer_id);

		$lists['manufacturers'] = JHTML::_('select.genericlist', $manufacturers, 'manufacturer_id[]',
			'class="inputbox" multiple="multiple" ', 'value', 'text', $manufacturer_id
		);

		$discountproductData = $model->GetProductListshippingrate($detail->discount_product);

		if (count($discountproductData) > 0)
		{
			$result_container = $discountproductData;
		}

		else
		{
			$result_container = array();
		}

		$lists['discount_product'] = JHTML::_('redshopselect.search', $result_container, 'container_product',
			array(
				'select2.options' => array('multiple' => true)
			)
		);

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
