<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Redshop Order Detail View Stock Note Pdf
 *
 * @package     Redshop.Backend
 * @subpackage  View.OrderDetail
 * @since       1.0
 */
class RedshopViewOrder_Detail extends RedshopView
{
	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{
		if (!RedshopHelperPdf::isAvailablePdfPlugins())
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_ERROR_MISSING_PDF_PLUGIN'), 'error');
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_redshop', false));
		}

		$config   = Redconfiguration::getInstance();
		$detail   = $this->get('data');
		$billing  = RedshopHelperOrder::getBillingAddress($detail->user_id);
		$shipping = RedshopHelperOrder::getOrderShippingUserInfo($detail->order_id);

		if (!$shipping)
		{
			$shipping = $billing;
		}

		$template    = RedshopHelperTemplate::getTemplate('shipping_pdf');
		$pdfTemplate = $template[0]->template_desc;

		ob_start();

		$order_status = RedshopHelperOrder::getOrderStatusTitle($detail->order_status);
		$pdfTemplate = str_replace("{order_information_lbl}", JText::_('COM_REDSHOP_ORDER_INFORMATION'), $pdfTemplate);
		$pdfTemplate = str_replace("{order_id_lbl}", JText::_('COM_REDSHOP_ORDER_ID'), $pdfTemplate);
		$pdfTemplate = str_replace("{order_number_lbl}", JText::_('COM_REDSHOP_ORDER_NUMBER'), $pdfTemplate);
		$pdfTemplate = str_replace("{order_date_lbl}", JText::_('COM_REDSHOP_ORDER_DATE'), $pdfTemplate);
		$pdfTemplate = str_replace("{order_status_lbl}", JText::_('COM_REDSHOP_ORDER_STATUS'), $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_address_info_lbl}", JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFORMATION'), $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_firstname_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_lastname_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_address_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_zip_lbl}", JText::_('COM_REDSHOP_ZIP'), $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_city_lbl}", JText::_('COM_REDSHOP_CITY'), $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_country_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_state_lbl}", JText::_('COM_REDSHOP_STATE'), $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_phone_lbl}", JText::_('COM_REDSHOP_PHONE'), $pdfTemplate);

		$pdfTemplate = str_replace("{order_id}", $detail->order_id, $pdfTemplate);
		$pdfTemplate = str_replace("{order_number}", $detail->order_number, $pdfTemplate);
		$pdfTemplate = str_replace("{order_date}", $config->convertDateFormat($detail->cdate), $pdfTemplate);
		$pdfTemplate = str_replace("{order_status}", $order_status, $pdfTemplate);

		$pdfTemplate = str_replace("{shipping_firstname}", $shipping->firstname, $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_lastname}", $shipping->lastname, $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_address}", $shipping->address, $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_zip}", $shipping->zipcode, $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_city}", $shipping->city, $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_country}", JTEXT::_(RedshopHelperOrder::getCountryName($shipping->country_code)), $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_state}", RedshopHelperOrder::getStateName($shipping->state_code, $shipping->country_code), $pdfTemplate);
		$pdfTemplate = str_replace("{shipping_phone}", $shipping->zipcode, $pdfTemplate);

		// If user is company than
		if ($billing->is_company && $billing->company_name != "")
		{
			$pdfTemplate = str_replace("{company_name}", $billing->company_name, $pdfTemplate);
			$pdfTemplate = str_replace("{company_name_lbl}", JText::_('COM_REDSHOP_COMPANY_NAME'), $pdfTemplate);
		}
		else
		{
			$pdfTemplate = str_replace("{company_name}", "", $pdfTemplate);
			$pdfTemplate = str_replace("{company_name_lbl}", "", $pdfTemplate);
		}

		JPluginHelper::importPlugin('redshop_pdf');
		RedshopHelperUtility::getDispatcher()->trigger('onRedshopOrderGenerateShippingPdf', array($detail, $pdfTemplate));

		JFactory::getApplication()->close();
	}
}
