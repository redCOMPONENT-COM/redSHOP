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


$producthelper = productHelper::getInstance();
$objhelper     = redhelper::getInstance();
$extraField    = extraField::getInstance();
$redTemplate   = Redtemplate::getInstance();
$app           = JFactory::getApplication();

$model     = $this->getModel('giftcard');
$url       = JURI::base();
$itemid    = $app->input->getInt('Itemid');
$gid       = $app->input->getInt('gid', 0);
$session   = JFactory::getSession();
$cart      = $session->get('cart');
$pagetitle = $this->pageheadingtag;
$detail    = $this->detail;
$router    = $app->getRouter();

if (count($this->template) > 0)
{
	$template = $this->template[0]->template_desc;
}
else
{
	if ($gid != 0)
	{
		$template = "<div>{giftcard_image}</div><div>{giftcard_name}</div><div>{giftcard_desc}</div><div>{giftcard_price_lbl}{giftcard_price}</div><div>{giftcard_validity}</div><div>{giftcard_reciver_name_lbl}{giftcard_reciver_name}</div><div>{giftcard_reciver_email_lbl}{giftcard_reciver_email}</div><div>{form_addtocart:templet1}</div>";
	}
	else
	{
		$template = "<div>{giftcard_loop_start}<h3>{giftcard_name}</h3><div>{giftcard_price}</div><div>{giftcard_value}</div><div>{giftcard_desc}</div><div>{giftcard_validity}</div>{giftcard_loop_end}</div>";
	}
}

if ($this->params->get('show_page_heading', 1))
{
	if (!$gid)
	{
		if ($this->params->get('page_title') != $pagetitle)
		{
			?>
			<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
				<?php echo $this->escape($this->params->get('page_title')); ?>
			</h1>
		<?php
		}
		else
		{
		?>
			<h1 class="componentheading<?php echo $this->escape($this->params->get('pageclass_sfx')); ?>">
				<?php echo $pagetitle; ?>
			</h1>
		<?php
		}
	}
}

if ($gid != 0)
{
	$count_no_user_field = 0;
	$detail              = $detail[0];

	$template = str_replace("{giftcard_name}", $detail->giftcard_name, $template);
	$template = str_replace("{giftcard_desc}", $detail->giftcard_desc, $template);

	if (strstr($template, "{giftcard_image}"))
	{
		$product_img = RedshopHelperMedia::watermark('giftcard', $detail->giftcard_image, Redshop::getConfig()->get('GIFTCARD_THUMB_WIDTH'), Redshop::getConfig()->get('GIFTCARD_THUMB_HEIGHT'), Redshop::getConfig()->get('WATERMARK_GIFTCART_THUMB_IMAGE'), '0');
		$linkimage   = RedshopHelperMedia::watermark('giftcard', $detail->giftcard_image, '', '', Redshop::getConfig()->get('WATERMARK_GIFTCART_IMAGE'), '0');
		$thum_image = "<a class=\"modal\" href='" . $linkimage . "' title='" . $detail->giftcard_name . "' rel=\"{handler: 'image', size: {}}\">";
		$thum_image .= "<img src='" . $product_img . "' title='" . $detail->giftcard_name . "' alt='" . $detail->giftcard_name . "'>";
		$thum_image .= "</a>";

		$template = str_replace("{giftcard_image}", $thum_image, $template);
	}

	$template = str_replace("{giftcard_value}", $producthelper->getProductFormattedPrice($detail->giftcard_value), $template);
	$template = str_replace("{giftcard_value_lbl}", JText::_('LIB_REDSHOP_GIFTCARD_VALUE_LBL'), $template);

	if ($detail->customer_amount != 1)
	{
		$template = str_replace("{giftcard_price_lbl}", JText::_('LIB_REDSHOP_GIFTCARD_PRICE_LBL'), $template);
	}
	else
	{
		$template = str_replace("{giftcard_price_lbl}", '', $template);
	}

	if ($detail->customer_amount != 1)
	{
		$template = str_replace("{giftcard_price}", $producthelper->getProductFormattedPrice($detail->giftcard_price), $template);
	}
	else
	{
		$template = str_replace("{giftcard_price}", '', $template);
	}

	$reciver_email = '<input type="text" name="reciver_email" id="reciver_email" value="' . @$cart['reciver_email'] . '" onkeyup="var f_value = this.value;addtocart_prd_' . $gid . '.reciver_email.value = f_value;">';
	$reciver_name  = '<input type="text" name="reciver_name" id="reciver_name" value="' . @$cart['reciver_name'] . '" onkeyup="var f_value = this.value;addtocart_prd_' . $gid . '.reciver_name.value = f_value;">';

	$customer_amount   = '';
	$customer_quantity = '';

	if ($detail->customer_amount == 1 && $gid != '')
	{
		$customer_quantity = '<input type="text" name="quantity" id="quantity" value="" onkeyup="var f_value = this.value;addtocart_prd_' . $gid . '.quantity.value = f_value;">';
		$customer_amount   = '<input type="text" name="customer_amount" id="customer_amount" value="" onkeyup="var f_value = this.value;addtocart_prd_' . $gid . '.customer_amount.value = f_value;">';
	}

	if ($detail->customer_amount != 1 || $detail->customer_amount == 1)
	{
		$template = str_replace("{giftcard_reciver_name_lbl}", JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_NAME_LBL'), $template);
		$template = str_replace("{giftcard_reciver_email_lbl}", JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_EMAIL_LBL'), $template);
	}
	else
	{
		$template = str_replace("{giftcard_reciver_name_lbl}", '', $template);
		$template = str_replace("{giftcard_reciver_email_lbl}", '', $template);
	}

	$template = str_replace("{giftcard_reciver_email}", $reciver_email, $template);
	$template = str_replace("{giftcard_reciver_name}", $reciver_name, $template);
	$template = str_replace("{customer_quantity}", $customer_quantity, $template);
	$template = str_replace("{customer_amount}", $customer_amount, $template);

	if ($detail->customer_amount == 1)
	{
		$template = str_replace("{giftcard_reciver_name_lbl}", JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_NAME_LBL'), $template);
		$template = str_replace("{giftcard_reciver_email_lbl}", JText::_('LIB_REDSHOP_GIFTCARD_RECIVER_EMAIL_LBL'), $template);
		$template = str_replace("{customer_quantity_lbl}", JText::_('COM_REDSHOP_CUSTOMER_QUANTITY_LBL'), $template);
		$template = str_replace("{customer_amount_lbl}", JText::_('COM_REDSHOP_CUSTOMER_AMOUNT_LBL'), $template);
	}
	else
	{
		$template = str_replace("{giftcard_reciver_name_lbl}", '', $template);
		$template = str_replace("{giftcard_reciver_email_lbl}", '', $template);
		$template = str_replace("{customer_quantity_lbl}", '', $template);
		$template = str_replace("{customer_amount_lbl}", '', $template);
	}

	if ($detail->customer_amount != 1)
	{
		$template = str_replace("{giftcard_reciver_email}", $reciver_email, $template);
		$template = str_replace("{giftcard_reciver_name}", $reciver_name, $template);
	}
	else
	{
		$template = str_replace("{giftcard_reciver_email}", '', $template);
		$template = str_replace("{giftcard_reciver_name}", '', $template);
	}

	if ($detail->customer_amount != 1)
	{
		$template = str_replace("{giftcard_validity}", $detail->giftcard_validity, $template);
	}
	else
	{
		$template = str_replace("{giftcard_validity}", '', $template);
	}

	$template = $producthelper->getValidityDate($detail->giftcard_validity, $template);

	// Product User Field Start
	$count_no_user_field = 0;
	$returnArr           = $producthelper->getProductUserfieldFromTemplate($template, 1);

	$template_userfield = $returnArr[0];
	$userfieldArr       = $returnArr[1];

	if (strstr($template, "{if giftcard_userfield}") && strstr($template, "{giftcard_userfield end if}") && $template_userfield != "")
	{
		$ufield = "";
		$cart   = $session->get('cart');

		if (isset($cart['idx']))
		{
			$idx = (int) ($cart['idx']);
		}

		$idx     = 0;
		$cart_id = '';

		for ($j = 0; $j < $idx; $j++)
		{
			if ($cart[$j]['giftcard_id'] == $gid)
			{
				$cart_id = $j;
			}
		}

		for ($ui = 0; $ui < count($userfieldArr); $ui++)
		{
			if (!$idx)
			{
				$cart_id = "";
			}

			$productUserFields = $extraField->list_all_user_fields($userfieldArr[$ui], 13, '', $cart_id, 0, $gid);

			$ufield .= $productUserFields[1];

			if ($productUserFields[1] != "")
			{
				$count_no_user_field++;
			}

			$template = str_replace('{' . $userfieldArr[$ui] . '_lbl}', $productUserFields[0], $template);
			$template = str_replace('{' . $userfieldArr[$ui] . '}', $productUserFields[1], $template);
		}

		$productUserFieldsForm = "<form method='post' action='' id='user_fields_form' name='user_fields_form'>";

		if ($ufield != "")
		{
			$template = str_replace("{if giftcard_userfield}", $productUserFieldsForm, $template);
			$template = str_replace("{giftcard_userfield end if}", "</form>", $template);
		}
		else
		{
			$template = str_replace("{if giftcard_userfield}", "", $template);
			$template = str_replace("{giftcard_userfield end if}", "", $template);
		}
	}

	// Product User Field End

	// Cart
	$template = $producthelper->replaceCartTemplate($gid, 0, 0, 0, $template, false, $userfieldArr, 0, 0, $count_no_user_field, 0, $gid);

	$template = $redTemplate->parseredSHOPplugin($template);
	echo eval("?>" . $template . "<?php ");
}
else
{
	if (strstr($template, "{giftcard_loop_start}") && strstr($template, "{giftcard_loop_end}"))
	{
		$template_d1   = explode("{giftcard_loop_start}", $template);
		$template_d2   = explode("{giftcard_loop_end}", $template_d1 [1]);
		$template_desc = $template_d2 [0];

		$data_add = "";

		for ($i = 0, $in = count($detail); $i < $in; $i++)
		{
			$data_add .= $template_desc;
			$gid  = $detail[$i]->giftcard_id;
			$link = JRoute::_('index.php?option=com_redshop&view=giftcard&gid=' . $gid . '&Itemid=' . $itemid);

			if (strstr($data_add, "{giftcard_image}"))
			{
				$product_img = RedshopHelperMedia::watermark('giftcard', $detail[$i]->giftcard_image, Redshop::getConfig()->get('GIFTCARD_LIST_THUMB_WIDTH'), Redshop::getConfig()->get('GIFTCARD_LIST_THUMB_HEIGHT'), Redshop::getConfig()->get('WATERMARK_GIFTCART_THUMB_IMAGE'), '0');
				$linkimage   = RedshopHelperMedia::watermark('giftcard', $detail[$i]->giftcard_image, '', '', Redshop::getConfig()->get('WATERMARK_GIFTCART_IMAGE'), '0');

				if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . "giftcard/" . $detail[$i]->giftcard_image))
				{
					$thum_image = "<a href='" . $link . "'><img src='" . $product_img . "' title='" . $detail[$i]->giftcard_name . "' alt='" . $detail[$i]->giftcard_name . "'></a>";
					$data_add   = str_replace("{giftcard_image}", $thum_image, $data_add);
				}
				else
				{
					$data_add = str_replace("{giftcard_image}", "", $data_add);
				}
			}

			$giftcard_name     = "<a href='" . $link . "'>" . $detail[$i]->giftcard_name . "</a>";
			$giftcard_readmore = "<a href='" . $link . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>";
			$data_add          = str_replace("{giftcard_name}", $giftcard_name, $data_add);
			$data_add          = str_replace("{giftcard_readmore}", $giftcard_readmore, $data_add);
			$data_add          = str_replace("{giftcard_desc}", $detail[$i]->giftcard_desc, $data_add);

			$data_add = $producthelper->getValidityDate($detail[$i]->giftcard_validity, $data_add);

			if ($detail[$i]->customer_amount != 1)
			{
				$data_add = str_replace("{giftcard_validity}", $detail[$i]->giftcard_validity, $data_add);
			}
			else
			{
				$data_add = str_replace("{giftcard_validity}", '', $data_add);
			}

			$data_add = str_replace("{giftcard_value_lbl}", JText::_('LIB_REDSHOP_GIFTCARD_VALUE_LBL'), $data_add);
			$data_add = str_replace("{giftcard_value}", $producthelper->getProductFormattedPrice($detail[$i]->giftcard_value), $data_add);

			$data_add = str_replace("{giftcard_price_lbl}", JText::_('LIB_REDSHOP_GIFTCARD_PRICE_LBL'), $data_add);
			$data_add = str_replace("{giftcard_price}", $producthelper->getProductFormattedPrice($detail[$i]->giftcard_price), $data_add);
		}

		$template = str_replace("{giftcard_loop_start}" . $template_desc . "{giftcard_loop_end}", $data_add, $template);
	}

	echo eval("?>" . $template . "<?php ");
}
?>

<script type="text/javascript">
	function validateEmail() {
		var reciver_email = document.getElementById('reciver_email').value;

		if (document.getElementById('reciver_name').value == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_NAME')?>");
			return false;
		}

		if (reciver_email == '') {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_EMAIL_ADDRESS')?>");
			return false;
		}
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

		if (reg.test(reciver_email) == false) {
			alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_VALID_EMAIL_ADDRESS')?>");
			return false;
		}

		if (document.getElementById('customer_amount')) {
			var customer_amount = document.getElementById('customer_amount').value;

			if (customer_amount == '') {
				alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_AMOUNT')?>");
				return false;
			} else if (isNaN(customer_amount)) {
				alert("<?php echo JText::_('COM_REDSHOP_PLEASE_ENTER_VALID_AMOUNT')?>");
				return false;
			}
		}
		return true;
	}
</script>

