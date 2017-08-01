<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewShopper_group_detail extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$shoppergroup = new shoppergroup;
		$redhelper = redhelper::getInstance();



		$document = JFactory::getDocument();
		$document->addScript('components/com_redshop/assets/js/json.js');
		$document->addScript('components/com_redshop/assets/js/validation.js');

		$uri = JFactory::getURI();

		$this->setLayout('default');

		$lists = array();

		$detail = $this->get('data');

		$isNew = ($detail->shopper_group_id < 1);

		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');

		JToolBarHelper::title(JText::_('COM_REDSHOP_SHOPPER_GROUP') . ': <small><small>[ ' . $text . ' ]</small></small>', 'users redshop_manufact48');

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

		$groups = $shoppergroup->list_all("parent_id", $detail->shopper_group_id);
		$lists['groups'] = $groups;
		$model = $this->getModel('shopper_group_detail');
		$optioncustomer = array();
		$optioncustomer[] = JHTML::_('select.option', '-1', JText::_('COM_REDSHOP_SELECT'));
		$optioncustomer[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_COMPANY'));
		$optioncustomer[] = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_PRIVATE'));
		$lists['customertype'] = JHTML::_('select.genericlist', $optioncustomer, 'shopper_group_customer_type',
			'class="inputbox" size="1" ', 'value', 'text', $detail->shopper_group_customer_type
		);

		$lists['portal'] = JHTML::_('redshopselect.booleanlist', 'shopper_group_portal', 'class="inputbox"', $detail->shopper_group_portal);
		$lists['default_shipping'] = JHTML::_('redshopselect.booleanlist', 'default_shipping', 'class="inputbox"', $detail->default_shipping);
		$lists['published'] = JHTML::_('redshopselect.booleanlist', 'published', 'class="inputbox"', $detail->published);
		$lists['show_price_without_vat'] = JHTML::_('redshopselect.booleanlist', 'show_price_without_vat', 'class="inputbox"', $detail->show_price_without_vat);
		$lists['shopper_group_quotation_mode'] = JHTML::_('redshopselect.booleanlist', 'shopper_group_quotation_mode',
			'class="inputbox"', $detail->shopper_group_quotation_mode
		);

		// For individual show_price and catalog
		$show_price_data = RedshopHelperUtility::getPreOrderByList();
		$lists['show_price'] = JHTML::_('select.genericlist', $show_price_data, 'show_price',
			'class="inputbox" size="1" ', 'value', 'text', $detail->show_price
		);
		$lists['use_as_catalog'] = JHTML::_('select.genericlist', $show_price_data, 'use_as_catalog',
			'class="inputbox" size="1" ', 'value', 'text', $detail->use_as_catalog
		);

		$shopper_group_categories = $detail->shopper_group_categories;
		$shopper_group_categories = explode(",", $shopper_group_categories);
		$shoppergroup_category = new product_category;

		$categories = $shoppergroup_category->list_all("shopper_group_categories[]", 0, $shopper_group_categories, 20, true, true, array(), 250);
		$lists['categories'] = $categories;

		$shopper_group_manufactures = '';

		if (isset($detail->shopper_group_manufactures))
		{
			$shopper_group_manufactures = $detail->shopper_group_manufactures;
		}

		$shopper_group_manufactures = explode(",", $shopper_group_manufactures);
		$manufacturers = $model->getmanufacturers();
		$lists['manufacturers'] = JHTML::_('select.genericlist', $manufacturers, 'shopper_group_manufactures[]',
			'class="inputbox"  multiple="multiple"  size="10" style="width: 250px;"> ', 'value', 'text',
			$shopper_group_manufactures
		);

		$vatgroup = $model->getVatGroup();
		$tmp = array();
		$tmp[] = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$vatgroup = @array_merge($tmp, $vatgroup);
		$lists['tax_group_id'] = JHTML::_('select.genericlist', $vatgroup, 'tax_group_id',
			'class="inputbox" size="1"', 'value', 'text', $detail->tax_group_id
		);

		if(!isset($lists['apply_vat']))
		{
			$lists['apply_vat'] = "";
		}

		if(!isset($lists['is_logged_in']))
		{
			$lists['is_logged_in'] = "";
		}

		if(!isset($lists['apply_product_price_vat']))
		{
			$lists['apply_product_price_vat'] = "";
		}

		if(!isset($lists['tax_exempt']))
		{
			$lists['tax_exempt'] = "";
		}

		if(!isset($lists['tax_exempt_on_shipping']))
		{
			$lists['tax_exempt_on_shipping'] = "";
		}

		$this->lists = $lists;
		$this->detail = $detail;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
