<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class RedshopViewShipping_rate_detail extends RedshopViewAdmin
{
	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$context = 'shipping_rate';
		$shippinghelper = shipping::getInstance();
		$userhelper = rsUserHelper::getInstance();
		$uri = JFactory::getURI();
		$model = $this->getModel();
		$db = JFactory::getDbo();

		$id = $app->getUserStateFromRequest($context . 'extension_id', 'extension_id', '0');
		$shipping = $shippinghelper->getShippingMethodById($id);



		$document = JFactory::getDocument();
		$document->addScript('components/com_redshop/assets/js/common.js');

		// Load language file of the shipping plugin
		JFactory::getLanguage()->load(
			'plg_redshop_shipping_' . strtolower($shipping->element),
			JPATH_ADMINISTRATOR
		);

		$plugin           = JPluginHelper::getPlugin($shipping->folder, $shipping->element);
		$pluginParams     = new JRegistry($plugin->params);

		$is_shipper       = $pluginParams->get('is_shipper');
		$shipper_location = $pluginParams->get('shipper_location');

		$jtitle = ($shipper_location) ? JText::_('COM_REDSHOP_SHIPPING_LOCATION') : JText::_('COM_REDSHOP_SHIPPING_RATE');

		$this->setLayout('default');
		$lists = array();
		$detail = $this->get('data');
		$isNew = ($detail->shipping_rate_id < 1);
		$text = $isNew ? JText::_('COM_REDSHOP_NEW') : JText::_('COM_REDSHOP_EDIT');
		JToolBarHelper::title($jtitle . ': <small><small>[ ' . JText::_($shipping->name) . ' : ' . $text . ' ]</small></small>', 'redshop_shipping_rates48');
		JToolBarHelper::save();
		JToolBarHelper::apply();

		if ($isNew)
		{
			JToolBarHelper::cancel();
		}
		else
		{
			JToolBarHelper::cancel('cancel', JText::_('JTOOLBAR_CLOSE'));
		}

		$q = "SELECT  country_3_code as value,country_name as text from #__redshop_country ORDER BY country_name ASC";
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

		$lists['shipping_rate_state'] = JHTML::_('select.genericlist', $shipping_rate_state, 'shipping_rate_state[]',
			'class="inputbox" multiple="multiple"', 'value', 'text', $detail->shipping_rate_state
		);

		$detail->shipping_rate_country = explode(',', $detail->shipping_rate_country);
		$tmp = new stdClass;
		$tmp = @array_merge($tmp, $detail->shipping_rate_country);
		$lists['shipping_rate_country'] = JHTML::_('select.genericlist', $countries, 'shipping_rate_country[]',
			'class="inputbox" multiple="multiple" onchange="getStateList()" ', 'value', 'text', $detail->shipping_rate_country
		);

		$detail->shipping_rate_on_category = explode(',', $detail->shipping_rate_on_category);

		$product_category = new product_category;
		$lists['shipping_rate_on_category'] = $product_category->list_all("shipping_rate_on_category[]", 0,
			$detail->shipping_rate_on_category, 10, false, true
		);

		$shoppergroup = Redshop\Helper\ShopperGroup::generateList();
		$detail->shipping_rate_on_shopper_group = explode(',', $detail->shipping_rate_on_shopper_group);
		$lists['shipping_rate_on_shopper_group'] = JHTML::_('select.genericlist', $shoppergroup, 'shipping_rate_on_shopper_group[]',
			'class="inputbox" multiple="multiple" ', 'value', 'text', $detail->shipping_rate_on_shopper_group
		);

		$lists['deliver_type'] = JHTML::_('select.booleanlist', 'deliver_type', 'class="inputbox"',
			$detail->deliver_type, 'COM_REDSHOP_HOME', 'COM_REDSHOP_POSTOFFICE'
		);

		$result_container = array();

		if ($detail->shipping_rate_on_product)
		{
			$result_container = $model->GetProductListshippingrate($detail->shipping_rate_on_product);
		}

		$lists['shipping_product'] = JHTML::_('redshopselect.search', $result_container, 'container_product',
			array(
				'select2.ajaxOptions' => array('typeField' => ', alert:"shipping"'),
				'select2.options' => array('multiple' => true)
			)
		);

		$field = extra_field::getInstance();

		// Extra field
		$list_field = $field->list_all_field(11, $detail->shipping_rate_id);
		$lists['extra_field'] = $list_field;

		$shippingVatGroup = $model->getVatGroup();

		$temps = array(
			(object) array(
				'value' => '',
				'text' => JText::_('COM_REDSHOP_SELECT')
			)
		);

		$shippingVatGroup = array_merge($temps, $shippingVatGroup);

		$shippingfor = array(
			(object) array(
				'value' => 0,
				'text' => JText::_('COM_REDSHOP_BOTH')
			),
			(object) array(
				'value' => 1,
				'text' => JText::_('COM_REDSHOP_COMPANY_ONLY')
			),
			(object) array(
				'value' => 2,
				'text' => JText::_('COM_REDSHOP_PRIVATE')
			),
		);

		$lists['company_only'] = JHTML::_('select.genericlist', $shippingfor, 'company_only',
			'class="inputbox" size="1" ', 'value', 'text', $detail->company_only
		);
		$lists['shipping_tax_group_id'] = JHTML::_('select.genericlist', $shippingVatGroup, 'shipping_tax_group_id',
			'class="inputbox" size="1" ', 'value', 'text', $detail->shipping_tax_group_id
		);

		$this->is_shipper = $is_shipper;
		$this->shipper_location = $shipper_location;
		$this->lists = $lists;
		$this->detail = $detail;
		$this->shipping = $shipping;
		$this->request_url = $uri->toString();

		parent::display($tpl);
	}
}
