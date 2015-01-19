<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


JLoader::load('RedshopHelperAdminExtra_field');
JLoader::load('RedshopHelperAdminOrder');

class RedshopViewOrder_detail extends RedshopView
{
	function display($tpl = null)
	{

		$config = new Redconfiguration;
		$redTemplate = new Redtemplate;
		$order_functions = new order_functions;
		$producthelper = new producthelper;
		$model = $this->getModel();
		$redTemplate = new Redtemplate;
		$detail = $this->get('data');
		$carthelper = new rsCarthelper;
		$shippinghelper = new shipping;
		$products = $order_functions->getOrderItemDetail($detail->order_id);
		$template = $model->getStockNoteTemplate();
		if (count($template) > 0 && $template->template_desc != "")
		{
			$html_template = $template->template_desc;
		}
		else
		{
			$html_template = '<table border="0" cellspacing="2" cellpadding="2" width="100%"><tr><td>{order_id_lbl} : {order_id}</td><td> {order_date_lbl} : {order_date}</td></tr></table>
                       <table border="1" cellspacing="0" cellpadding="0" width="100%"><tbody><tr style="background-color: #d7d7d4"><th align="center">{product_name_lbl}</th> <th align="center">{product_number_lbl}</th> <th align="center">{product_quantity_lbl}</th></tr>
						{product_loop_start}
						<tr>
						<td  align="center">
							<table>
							<tr><td>{product_name}</td></tr>
							<tr><td>{product_attribute}</td></tr>
							</table>
						</td>
						<td  align="center">{product_number}</td>
						<td  align="center">{product_quantity}</td>
						</tr>
						{product_loop_end}
						</tbody>
						</table>';
		}
		ob_start();
		if (strstr($html_template, "{product_loop_start}") && strstr($html_template, "{product_loop_end}"))
		{

			$template_sdata = explode('{product_loop_start}', $html_template);
			$template_start = $template_sdata[0];
			$template_edata = explode('{product_loop_end}', $template_sdata[1]);
			$template_end = $template_edata[1];
			$template_middle = $template_edata[0];

			$middle_data = '';
			for ($p = 0; $p < count($products); $p++)
			{
				$middle_data .= $template_middle;

				$product_detail = $producthelper->getProductById($products[$p]->product_id);
				$middle_data = str_replace("{product_number}", $product_detail->product_number, $middle_data);
				$middle_data = str_replace("{product_name}", $products[$p]->order_item_name, $middle_data);
				$middle_data = str_replace("{product_attribute}", $products[$p]->product_attribute, $middle_data);
				$middle_data = str_replace("{product_quantity}", $products[$p]->product_quantity, $middle_data);
			}

			$html_template = $template_start . $middle_data . $template_end;
		}

		$html_template = str_replace("{order_id_lbl}", JText::_('COM_REDSHOP_ORDER_ID'), $html_template);
		$html_template = str_replace("{order_id}", $detail->order_id, $html_template);
		$html_template = str_replace("{order_date_lbl}", JText::_('COM_REDSHOP_ORDER_DATE'), $html_template);
		$html_template = str_replace("{order_date}", $config->convertDateFormat($detail->cdate), $html_template);
		$html_template = str_replace("{product_name_lbl}", JText::_('COM_REDSHOP_PRODUCT_NAME'), $html_template);
		$html_template = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER'), $html_template);
		$html_template = str_replace("{product_quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY'), $html_template);
		$billing = $order_functions->getOrderBillingUserInfo($detail->order_id);
		$html_template = $carthelper->replaceBillingAddress($html_template, $billing);
		$shipping = $order_functions->getOrderShippingUserInfo($detail->order_id);
		$html_template = $carthelper->replaceShippingAddress($html_template, $shipping);
		$html_template = str_replace("{requisition_number}", $detail->requisition_number, $html_template);
		$html_template = str_replace("{requisition_number_lbl}", JText::_('COM_REDSHOP_REQUISITION_NUMBER'), $html_template);


		// Start pdf code
		$pdfObj = RedshopHelperPdf::getInstance();
		$pdfObj->SetTitle("Order StockNote: " . $detail->order_id);
		$pdfObj->SetMargins(15, 15, 15);

		$font = 'times';

		$pdfObj->SetHeaderData('', '', '', "Order " . $detail->order_id);
		$pdfObj->setHeaderFont(array($font, '', 10));
		//$pdfObj->setFooterFont(array($font, '', 8));
		$pdfObj->SetFont($font, "", 10);


		//$pdfObj->AliasNbPages();
		$pdfObj->AddPage();


		$pdfObj->WriteHTML($html_template);

		$pdfObj->Output("StocNoteOrder_" . $detail->order_id . ".pdf", "D");
		exit;
	}

}

?>
