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

		$config     = Redconfiguration::getInstance();
		$detail     = $this->get('data');
		$products   = RedshopHelperOrder::getOrderItemDetail($detail->order_id);
		$template   = RedshopHelperTemplate::getTemplate('stock_note');
		$cartHelper = rsCarthelper::getInstance();

		if (!empty($template) && !empty($template[0]->template_desc))
		{
			$pdfTemplate = $template[0]->template_desc;
		}
		else
		{
			$pdfTemplate = '<table border="0" cellspacing="2" cellpadding="2" width="100%"><tr><td>{order_id_lbl} : {order_id}</td><td> {order_date_lbl} : {order_date}</td></tr></table>
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

		if (strpos($pdfTemplate, "{product_loop_start}") !== false && strpos($pdfTemplate, "{product_loop_end}") !== false)
		{
			$template_sdata = explode('{product_loop_start}', $pdfTemplate);
			$template_start = $template_sdata[0];
			$template_edata = explode('{product_loop_end}', $template_sdata[1]);
			$template_end = $template_edata[1];
			$template_middle = $template_edata[0];

			$middle_data = '';

			for ($p = 0, $pn = count($products); $p < $pn; $p++)
			{
				$middle_data .= $template_middle;

				$product_detail = Redshop::product((int) $products[$p]->product_id);
				$middle_data = str_replace("{product_number}", $product_detail->product_number, $middle_data);
				$middle_data = str_replace("{product_name}", $products[$p]->order_item_name, $middle_data);

				$middle_data = RedshopTagsReplacer::_(
						'attribute',
						$middle_data,
						array(
							'product_attribute' 	=> $products[$p]->product_attribute,
						)
					);

				$middle_data = str_replace("{product_quantity}", $products[$p]->product_quantity, $middle_data);
			}

			$pdfTemplate = $template_start . $middle_data . $template_end;
		}

		$pdfTemplate = str_replace("{order_id_lbl}", JText::_('COM_REDSHOP_ORDER_ID'), $pdfTemplate);
		$pdfTemplate = str_replace("{order_id}", $detail->order_id, $pdfTemplate);
		$pdfTemplate = str_replace("{order_date_lbl}", JText::_('COM_REDSHOP_ORDER_DATE'), $pdfTemplate);
		$pdfTemplate = str_replace("{order_date}", $config->convertDateFormat($detail->cdate), $pdfTemplate);
		$pdfTemplate = str_replace("{product_name_lbl}", JText::_('COM_REDSHOP_PRODUCT_NAME'), $pdfTemplate);
		$pdfTemplate = str_replace("{product_number_lbl}", JText::_('COM_REDSHOP_PRODUCT_NUMBER'), $pdfTemplate);
		$pdfTemplate = str_replace("{product_quantity_lbl}", JText::_('COM_REDSHOP_QUANTITY'), $pdfTemplate);
		$billing     = RedshopHelperOrder::getOrderBillingUserInfo($detail->order_id);
		$pdfTemplate = RedshopHelperBillingTag::replaceBillingAddress($pdfTemplate, $billing);
		$shipping    = RedshopHelperOrder::getOrderShippingUserInfo($detail->order_id);
		$pdfTemplate = $cartHelper->replaceShippingAddress($pdfTemplate, $shipping);
		$pdfTemplate = str_replace("{requisition_number}", $detail->requisition_number, $pdfTemplate);
		$pdfTemplate = str_replace("{requisition_number_lbl}", JText::_('COM_REDSHOP_REQUISITION_NUMBER'), $pdfTemplate);

		JPluginHelper::importPlugin('redshop_pdf');
		RedshopHelperUtility::getDispatcher()->trigger('onRedshopOrderGenerateStockNotePdf', array($detail, $pdfTemplate));

		JFactory::getApplication()->close();
	}
}
