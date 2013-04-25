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

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/extra_field.php';
//
require_once JPATH_COMPONENT_SITE . '/helpers/tcpdf/tcpdf.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/order.php';
class order_detailVIEWorder_detail extends JView
{
	function display($tpl = null)
	{

		$config = new Redconfiguration();
		$redTemplate = new Redtemplate();

		$order_functions = new order_functions();
		$model = $this->getModel();

		$detail = $this->get('data');

		$billing = $order_functions->getBillingAddress($detail->user_id);
		$shipping = $order_functions->getOrderShippingUserInfo($detail->order_id);
		if (!$shipping)
		{
			$shipping = $billing;
		}

		$template = $redTemplate->getTemplate("shipping_pdf");

		$html_template = $template[0]->template_desc;

		ob_start();
		$order_status = $order_functions->getOrderStatusTitle($detail->order_status);
		$html_template = str_replace("{order_information_lbl}", JText::_('COM_REDSHOP_ORDER_INFORMATION'), $html_template);
		$html_template = str_replace("{order_id_lbl}", JText::_('COM_REDSHOP_ORDER_ID'), $html_template);
		$html_template = str_replace("{order_number_lbl}", JText::_('COM_REDSHOP_ORDER_NUMBER'), $html_template);
		$html_template = str_replace("{order_date_lbl}", JText::_('COM_REDSHOP_ORDER_DATE'), $html_template);
		$html_template = str_replace("{order_status_lbl}", JText::_('COM_REDSHOP_ORDER_STATUS'), $html_template);
		$html_template = str_replace("{shipping_address_info_lbl}", JText::_('COM_REDSHOP_SHIPPING_ADDRESS_INFORMATION'), $html_template);
		$html_template = str_replace("{shipping_firstname_lbl}", JText::_('COM_REDSHOP_FIRSTNAME'), $html_template);
		$html_template = str_replace("{shipping_lastname_lbl}", JText::_('COM_REDSHOP_LASTNAME'), $html_template);
		$html_template = str_replace("{shipping_address_lbl}", JText::_('COM_REDSHOP_ADDRESS'), $html_template);
		$html_template = str_replace("{shipping_zip_lbl}", JText::_('COM_REDSHOP_ZIP'), $html_template);
		$html_template = str_replace("{shipping_city_lbl}", JText::_('COM_REDSHOP_CITY'), $html_template);
		$html_template = str_replace("{shipping_country_lbl}", JText::_('COM_REDSHOP_COUNTRY'), $html_template);
		$html_template = str_replace("{shipping_state_lbl}", JText::_('COM_REDSHOP_STATE'), $html_template);
		$html_template = str_replace("{shipping_phone_lbl}", JText::_('COM_REDSHOP_PHONE'), $html_template);

		$html_template = str_replace("{order_id}", $detail->order_id, $html_template);
		$html_template = str_replace("{order_number}", $detail->order_number, $html_template);
		$html_template = str_replace("{order_date}", $config->convertDateFormat($detail->cdate), $html_template);
		$html_template = str_replace("{order_status}", $order_status, $html_template);

		$html_template = str_replace("{shipping_firstname}", $shipping->firstname, $html_template);
		$html_template = str_replace("{shipping_lastname}", $shipping->lastname, $html_template);
		$html_template = str_replace("{shipping_address}", $shipping->address, $html_template);
		$html_template = str_replace("{shipping_zip}", $shipping->zipcode, $html_template);
		$html_template = str_replace("{shipping_city}", $shipping->city, $html_template);
		$html_template = str_replace("{shipping_country}", JTEXT::_($order_functions->getCountryName($shipping->country_code)), $html_template);
		$html_template = str_replace("{shipping_state}", $order_functions->getStateName($shipping->state_code, $shipping->country_code), $html_template);
		$html_template = str_replace("{shipping_phone}", $shipping->zipcode, $html_template);

		// if user is company than
		if ($billing->is_company && $billing->company_name != "")
		{
			$html_template = str_replace("{company_name}", $billing->company_name, $html_template);
			$html_template = str_replace("{company_name_lbl}", JText::_('COM_REDSHOP_COMPANY_NAME'), $html_template);
		}
		else
		{
			$html_template = str_replace("{company_name}", "", $html_template);
			$html_template = str_replace("{company_name_lbl}", "", $html_template);
		}

		$pdfObj = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A5', true, 'UTF-8', false);
		$pdfObj->SetTitle("Order :" . $detail->order_id);
		$pdfObj->SetAuthor('redSHOP');
		$pdfObj->SetCreator('redSHOP');
		$pdfObj->SetMargins(15, 15, 15);

		$font = 'times';

		$pdfObj->SetHeaderData('', '', '', "Order " . $detail->order_id);
		$pdfObj->setHeaderFont(array($font, '', 10));
		//$pdfObj->setFooterFont(array($font, '', 8));
		$pdfObj->SetFont($font, "", 12);


		//$pdfObj->AliasNbPages();
		$pdfObj->AddPage();


		$pdfObj->WriteHTML($html_template);

		$pdfObj->Output("Order_" . $detail->order_id . ".pdf", "D");
		exit;
	}

}

?>
