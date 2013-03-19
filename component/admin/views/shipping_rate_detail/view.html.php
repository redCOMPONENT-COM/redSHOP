<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'order.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'extra_field.php');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'category.php');

class shipping_rate_detailViewshipping_rate_detail extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		$context = 'shipping_rate';
		$shippinghelper = new shipping();
		$userhelper = new rsUserhelper();
		$uri =& JFactory::getURI();
		$model = $this->getModel();
		$db = & JFactory::getDBO();

		$id = $mainframe->getUserStateFromRequest($context . 'extension_id', 'extension_id', '0');
		$shipping = $shippinghelper->getShippingMethodById($id);

		$option = JRequest::getVar('option');

		$document = & JFactory::getDocument();
		$document->addScript('components/' . $option . '/assets/js/select_sort.js');
		$document->addStyleSheet('components/' . $option . '/assets/css/search.css');
		$document->addScript('components/' . $option . '/assets/js/search.js');
		$document->addScript('components/' . $option . '/assets/js/common.js');

		$shippingpath = JPATH_ROOT . DS . 'plugins' . DS . $shipping->folder . DS . $shipping->element . '.xml';
		$myparams = new JRegistry($shipping->params, $shippingpath);
		$is_shipper = $myparams->get('is_shipper');
		$shipper_location = $myparams->get('shipper_location');

		$jtitle = ($shipper_location) ? JText::_('COM_REDSHOP_SHIPPING_LOCATION') : JText::_('COM_REDSHOP_SHIPPING_RATE');

		$this->setLayout('default');
		$lists = array();
		$detail =& $this->get('data');
		$isNew = ($detail->shipping_rate_id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');
		JToolBarHelper::title($jtitle . ': <small><small>[ ' . $shipping->name . ' : ' . $text . ' ]</small></small>', 'redshop_shipping_rates48');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', 'Close');
		}

		$q = "SELECT  country_3_code as value,country_name as text from #__" . TABLE_PREFIX . "_country ORDER BY country_name ASC";
		$db->setQuery($q);
		$countries[] = JHTML::_('select.option', '0', '- ' . JText::_('COM_REDSHOP_SELECT_COUNTRY') . ' -', 'value', 'text');
		$countries = array_merge($countries, $db->loadObjectList());

		$shipping_rate_state = array();
		if ($detail->shipping_rate_country)
		{
			$shipping_rate_state = $model->GetStateList($detail->shipping_rate_country);
		}

		$detail->shipping_rate_state = explode(',', $detail->shipping_rate_state);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $detail->shipping_rate_state);

		$lists['shipping_rate_state'] = JHTML::_('select.genericlist', $shipping_rate_state, 'shipping_rate_state[]', 'class="inputbox" multiple="multiple"', 'value', 'text', $detail->shipping_rate_state);

		$detail->shipping_rate_country = explode(',', $detail->shipping_rate_country);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $detail->shipping_rate_country);
		$lists['shipping_rate_country'] = JHTML::_('select.genericlist', $countries, 'shipping_rate_country[]', 'class="inputbox" multiple="multiple" onchange="getStateList()" ', 'value', 'text', $detail->shipping_rate_country);

//		$categoryData = $model->GetCategoryList();
		$detail->shipping_rate_on_category = explode(',', $detail->shipping_rate_on_category);
//		$tmp = new stdClass;
//		$tmp = @array_merge($tmp,$detail->shipping_rate_on_category);
		$product_category = new product_category();
		$lists['shipping_rate_on_category'] = $product_category->list_all("shipping_rate_on_category[]", 0, $detail->shipping_rate_on_category, 10, false, true); //JHTML::_('select.genericlist',$categoryData,'shipping_rate_on_category[]','class="inputbox" multiple="multiple" ','value','text',$detail->shipping_rate_on_category);

		$shoppergroup = $userhelper->getShopperGroupList();
		$detail->shipping_rate_on_shopper_group = explode(',', $detail->shipping_rate_on_shopper_group);
		$lists['shipping_rate_on_shopper_group'] = JHTML::_('select.genericlist', $shoppergroup, 'shipping_rate_on_shopper_group[]', 'class="inputbox" multiple="multiple" ', 'value', 'text', $detail->shipping_rate_on_shopper_group);

		$lists['deliver_type'] = JHTML::_('select.booleanlist', 'deliver_type', 'class="inputbox"', $detail->deliver_type, 'COM_REDSHOP_HOME', 'COM_REDSHOP_POSTOFFICE');
		$productData = array();
		$result_container = array();
		if ($detail->shipping_rate_on_product)
		{
			$result_container = $model->GetProductListshippingrate($detail->shipping_rate_on_product);
		}
//		if(count($productData) == 0)
//		{
//			$productData [0]->text = JText::_('COM_REDSHOP_SELECT');
//	    	$productData [0]->value = 0;
//		}
		$lists['product_all'] = JHTML::_('select.genericlist', $productData, 'product_all[]', 'class="inputbox" multiple="multiple" ', 'value', 'text', $detail->shipping_rate_on_product);
		$lists['shipping_product'] = JHTML::_('select.genericlist', $result_container, 'container_product[]', 'class="inputbox" onmousewheel="mousewheel(this);" ondblclick="selectnone(this);" multiple="multiple"  size="15" style="width:200px;" ', 'value', 'text', 0);

		$field = new extra_field();
		// Extra field
		$list_field = $field->list_all_field(11, $detail->shipping_rate_id); /// field_section 11 :Shipping
		$lists['extra_field'] = $list_field;

		$shippingVatGroup = $model->getVatGroup();
		$temps = array();
		$temps[0]->value = "";
		$temps[0]->text = JText::_('COM_REDSHOP_SELECT');
		$shippingVatGroup = @array_merge($temps, $shippingVatGroup);

		$shippingfor = array();
		$shippingfor[0]->value = 0;
		$shippingfor[0]->text = JText::_('COM_REDSHOP_BOTH');
		$shippingfor[1]->value = 1;
		$shippingfor[1]->text = JText::_('COM_REDSHOP_COMPANY_ONLY');
		$shippingfor[2]->value = 2;
		$shippingfor[2]->text = JText::_('COM_REDSHOP_PRIVATE');

		$lists['company_only'] = JHTML::_('select.genericlist', $shippingfor, 'company_only', 'class="inputbox" size="1" ', 'value', 'text', $detail->company_only);
		$lists['shipping_tax_group_id'] = JHTML::_('select.genericlist', $shippingVatGroup, 'shipping_tax_group_id', 'class="inputbox" size="1" ', 'value', 'text', $detail->shipping_tax_group_id);

		$this->assignRef('is_shipper', $is_shipper);
		$this->assignRef('shipper_location', $shipper_location);
		$this->assignRef('lists', $lists);
		$this->assignRef('detail', $detail);
		$this->assignRef('shipping', $shipping);
		$this->assignRef('request_url', $uri->toString());

		parent::display($tpl);
	}
}
