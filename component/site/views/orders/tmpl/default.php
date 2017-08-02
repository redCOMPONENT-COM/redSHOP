<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
JHTMLBehavior::modal();
$url            = JURI::base();
$app            = JFactory::getApplication();
$order_function = order_functions::getInstance();
$redconfig      = Redconfiguration::getInstance();
$producthelper  = productHelper::getInstance();
$carthelper     = rsCarthelper::getInstance();

$Itemid      = $app->input->getInt('Itemid');
$print       = $app->input->getInt('print');
$document    = JFactory::getDocument();
$redTemplate = Redtemplate::getInstance();

$template_id         = $this->params->get('template_id');
$orderslist_template = $redTemplate->getTemplate("order_list", $template_id);

if (count($orderslist_template) > 0 && $orderslist_template[0]->template_desc != "")
{
	$template_desc = $orderslist_template[0]->template_desc;
}
else
{
	$template_desc = "<table border=\"0\" cellspacing=\"5\" cellpadding=\"5\" width=\"100%\">\r\n<tbody>\r\n<tr>\r\n<th>{order_id_lbl}</th> <th>{product_name_lbl}</th> <th>{total_price_lbl}</th> <th>{order_date_lbl}</th> <th>{order_date_lbl}</th> <th>{order_detail_lbl}</th>\r\n</tr>\r\n{product_loop_start}       \r\n<tr>\r\n<td>{order_id}</td>\r\n<td>{order_products}</td>\r\n<td>{order_total}</td>\r\n<td>{order_date}</td>\r\n<td>{order_status}</td>\r\n<td>{order_detail_link}</td>\r\n</tr>\r\n{product_loop_end}\r\n</tbody>\r\n</table>\r\n<div>{pagination}</div>";
}

if ($this->params->get('show_page_heading', 1))
{
	?>
	<h1 class="componentheading<?php echo $this->params->get('pageclass_sfx') ?>">
		<?php echo $this->escape(JText::_('COM_REDSHOP_ORDER_LIST'));?>
	</h1>
<?php
}

if ($print)
{
	$onclick = "onclick='window.print();'";
}
else
{
	$print_url = $url . "index.php?option=com_redshop&view=orders&print=1&tmpl=component&Itemid=" . $Itemid;
	$onclick   = "onclick='window.open(\"$print_url\",\"mywindow\",\"scrollbars=1\",\"location=1\")'";
}

$print_tag = "<a " . $onclick . " title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "'>";
$print_tag .= "<img src='" . JSYSTEM_IMAGES_PATH . "printButton.png' alt='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' title='" . JText::_('COM_REDSHOP_PRINT_LBL') . "' />";
$print_tag .= "</a>";

$template_desc = str_replace("{print}", $print_tag, $template_desc);

if (strstr($template_desc, "{product_loop_start}") && strstr($template_desc, "{product_loop_end}"))
{
	$template_sdata  = explode('{product_loop_start}', $template_desc);
	$template_start  = $template_sdata[0];
	$template_edata  = explode('{product_loop_end}', $template_sdata[1]);
	$template_end    = $template_edata[1];
	$template_middle = $template_edata[0];

	$cart_mdata = "";

	for ($i = 0; $i < count($this->detail); $i++)
	{
		$prolist    = $order_function->getOrderItemDetail($this->detail[$i]->order_id);
		$statusname = $order_function->getOrderStatusTitle($this->detail[$i]->order_status);

		$order_item_name = array();

		for ($j = 0, $jn = count($prolist); $j < $jn; $j++)
		{
			$order_item_name[$j] = $prolist[$j]->order_item_name;
		}

		$orderpayment  = $order_function->getOrderPaymentDetail($this->detail[$i]->order_id);
		$paymentmethod = $orderpayment[0];

		$orderTransFee = '';

		if ($paymentmethod->order_transfee > 0)
		{
			$orderTransFee = $producthelper->getProductFormattedPrice(
				$paymentmethod->order_transfee
			);
		}

		$orderTotalInclTraferfee = $producthelper->getProductFormattedPrice(
				$paymentmethod->order_transfee + $this->detail[$i]->order_total
			);

		$orderdetailurl = JRoute::_('index.php?option=com_redshop&view=order_detail&oid=' . $this->detail[$i]->order_id);

		$reorderurl = JURI::root() . 'index.php?option=com_redshop&view=order_detail&order_id=' . $this->detail[$i]->order_id . '&task=reorder&tmpl=component';

		$order_number = "<div class='order_number'>" . $this->detail[$i]->order_number . "</div>";

		$order_id = "<div class='order_id'>" . $this->detail[$i]->order_id . "</div>";

		$order_products = "<div class='order_products'>" . implode(',<br/>', $order_item_name) . "</div>";

		$order_total = "<div class='order_total'>" . $producthelper->getProductFormattedPrice($this->detail[$i]->order_total) . "</div>";

		$order_date = "<div class='order_date'>" . $redconfig->convertDateFormat($this->detail[$i]->cdate) . "</div>";

		$cart_mdata = str_replace("{order_transfee}", $orderTransFee, $cart_mdata);

		$cart_mdata = str_replace("{order_total_incl_transfee}", $orderTotalInclTraferfee, $cart_mdata);

		$order_status = "<div class='order_status'>" . $statusname . "</div>";

		$order_detail_link = "<div class='order_detail_link'><a href='" . $orderdetailurl . "'>" . JText::_('COM_REDSHOP_ORDER_DETAIL') . "</a></div>";

		$reorder_link = "<div class='reorder_link'><a href='javascript:if(confirm(\"" . JText::_('COM_REDSHOP_CONFIRM_CART_EMPTY') . "\")){window.location=\"" . $reorderurl . "\";}'>" . JText::_('COM_REDSHOP_REORDER') . "</a></div>";

		$cart_mdata .= $template_middle;

		$cart_mdata = str_replace("{order_number}", $order_number, $cart_mdata);

		$cart_mdata = str_replace("{order_id}", $order_id, $cart_mdata);

		$cart_mdata = str_replace("{order_products}", $order_products, $cart_mdata);

		$cart_mdata = str_replace("{order_total}", $order_total, $cart_mdata);

		$cart_mdata = str_replace("{order_date}", $order_date, $cart_mdata);

		$cart_mdata = str_replace("{order_status}", $order_status, $cart_mdata);

		$cart_mdata = str_replace("{order_detail_link}", $order_detail_link, $cart_mdata);

		$cart_mdata = str_replace("{reorder_link}", $reorder_link, $cart_mdata);

		$cart_mdata = str_replace("{requisition_number}", $this->detail[$i]->requisition_number, $cart_mdata);
	}

	$template_desc = str_replace("{product_loop_start}", "", $template_desc);
	$template_desc = str_replace($template_middle, $cart_mdata, $template_desc);
	$template_desc = str_replace("{product_loop_end}", "", $template_desc);
}

$template_desc = $carthelper->replaceLabel($template_desc);

if (strstr($template_desc, "{pagination}"))
{
	$template_desc = str_replace("{pagination}", $this->pagination->getPagesLinks(), $template_desc);
}

if (strstr($template_desc, "{pagination_limit}"))
{
	$limitBox = "<form name='adminForm' method='POST' >";
	$limitBox .= $this->pagination->getLimitBox();
	$limitBox .= "<input type='hidden' name='option' value='com_redshop' />";
	$limitBox .= "<input type='hidden' name='view' value='orders' />";
	$limitBox .= "<input type='hidden' name='Itemid' value='" . $Itemid . "' />";
	$limitBox .= "</form>";
	$template_desc = str_replace("{pagination_limit}", $limitBox, $template_desc);
}

$template_desc = $redTemplate->parseredSHOPplugin($template_desc);
echo eval("?>" . $template_desc . "<?php ");
