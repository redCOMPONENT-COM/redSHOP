<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
/*
 * Include required files
 */

$producthelper = productHelper::getInstance();
$carthelper    = rsCarthelper::getInstance();
$redTemplate   = Redtemplate::getInstance();

$cart       = $this->cart;
$idx        = $cart ['idx'];
$Itemid     = JRequest::getInt('Itemid');
$cart_index = JRequest::getInt('cart_index');
$product_id = JRequest::getInt('pid');
$model      = $this->getModel('cart');

$session  = JFactory::getSession();
$user     = JFactory::getUser();
$document = JFactory::getDocument();

?>
	<script type="text/javascript">
		function cancelForm(frm) {
			frm.task.value = 'cancel';
			frm.submit();
		}
		function submitChangeAttribute() {
			calculateTotalPrice(<?php echo $product_id;?>, 0);
			var requiedAttribute = document.getElementById('requiedAttribute').value;
			var requiedProperty = document.getElementById('requiedProperty').value;

			if (requiedAttribute != 0 && requiedAttribute != "") {
				alert(requiedAttribute);
				return false;
			}
			else if (requiedProperty != 0 && requiedProperty != "")
			{
				alert(requiedProperty);
				return false;
			}
			else
			{
				document.frmchngAttribute.submit();
			}
		}
	</script>
<?php
$cart_attribute = $redTemplate->getTemplate("change_cart_attribute");

if (count($cart_attribute) > 0 && $cart_attribute[0]->template_desc)
{
	$template_desc = $cart_attribute[0]->template_desc;
}
else
{
	$template_desc = '<table border="0">\r\n<tbody>\r\n<tr>\r\n<th>Change Attribute</th>\r\n</tr>\r\n<tr>\r\n<td>{attribute_template:attributes}</td>\r\n</tr>\r\n<tr>\r\n<td>{apply_button} {cancel_button}</td>\r\n</tr>\r\n</tbody>\r\n</table>';
}

$product = $producthelper->getProductById($product_id);

// Checking for child products
$childproduct = $producthelper->getChildProduct($product_id);

if (count($childproduct) > 0)
{
	$isChilds = true;
}
else
{
	$isChilds = false;
}

// Product attribute  Start
if ($isChilds)
{
	$attributes = array();
	$selectAtt  = array(array(), array());
}
else
{
	$attributes_set = array();

	if ($product->attribute_set_id > 0)
	{
		$attributes_set = $producthelper->getProductAttribute(0, $product->attribute_set_id, 0, 1);
	}

	$bool                              = (Redshop::getConfig()->get('INDIVIDUAL_ADD_TO_CART_ENABLE')) ? false : true;
	$attribute_template                = $producthelper->getAttributeTemplate($template_desc, $bool);
	$attribute_template->template_desc = str_replace("{property_image_scroller}", "", $attribute_template->template_desc);
	$attribute_template->template_desc = str_replace("{subproperty_image_scroller}", "", $attribute_template->template_desc);
	$attributes                        = $producthelper->getProductAttribute($product_id);
	$attributes                        = array_merge($attributes, $attributes_set);

	$selectAtt = $carthelper->getSelectedCartAttributeArray($cart[$cart_index]['cart_attribute']);
}

$totalatt      = count($attributes);
$template_desc = $producthelper->replaceAttributeData($product_id, 0, 0, $attributes, $template_desc, $attribute_template, $isChilds, $selectAtt, 0);

// Product attribute  End

$stockaddtocart = "stockaddtocartprd_" . $product_id;
$pdaddtocart    = "pdaddtocartprd_" . $product_id;

$applybutton = "<input type='button' name='apply' class='btn btn-primary' value='" . JText::_('COM_REDSHOP_APPLY') . "' onclick='javascript:submitChangeAttribute();' />";
$applybutton .= "<input type='hidden' name='task' value='changeAttribute' />";
$applybutton .= "<input type='hidden' name='cart_index' value='" . $cart_index . "' />";
$applybutton .= "<input type='hidden' name='product_id' value='" . $product_id . "' />";
$applybutton .= "<input type='hidden' name='view' value='cart' />";
$applybutton .= "<input type='hidden' name='requiedAttribute' id='requiedAttribute' value='' reattribute=''>";
$applybutton .= "<input type='hidden' name='requiedProperty' id='requiedProperty' value='' reproperty=''>";
$cancelbutton = "<input type='button' name='cancel' class='btn btn' value='" . JText::_('COM_REDSHOP_CANCEL') . "' onclick='javascript:cancelForm(this.form);' />";

$template_desc = str_replace("{apply_button}", "<span id='" . $stockaddtocart . "'></span><span id='" . $pdaddtocart . "'>" . $applybutton . "</span>", $template_desc);
$template_desc = str_replace("{cancel_button}", $cancelbutton, $template_desc);

$template_desc = '<form name="frmchngAttribute" id="frmchngAttribute" method="post">' . $template_desc . '</form>';

$template_desc = str_replace("{change_attribute}", JText::_("COM_REDSHOP_CHANGE_ATTRIBUTE"), $template_desc);

if ($totalatt > 0)
{
	$template_desc = $redTemplate->parseredSHOPplugin($template_desc);
	echo eval ("?>" . $template_desc . "<?php ");
}
else
{
	echo JText::_("COM_REDSHOP_NO_ATTRIBUTE_TO_CHANGE");
}
