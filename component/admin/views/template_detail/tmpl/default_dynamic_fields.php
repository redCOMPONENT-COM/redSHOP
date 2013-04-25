<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
$model = $this->getModel('template_detail');
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/template.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/extra_field.php';
$redtemplate = new Redtemplate();
$extra_field = new extra_field();
$default_template = JText::_('COM_REDSHOP_DEFAULT_TEMPLATE_DETAIL');
$newbillingtag = '{billing_address_start}
			<table border="0"><tbody>
			<tr><td>{companyname_lbl}</td><td>{companyname}</td></tr>
			<tr><td>{firstname_lbl}</td><td>{firstname}</td></tr>
			<tr><td>{lastname_lbl}</td><td>{lastname}</td></tr>
			<tr><td>{address_lbl}</td><td>{address}</td></tr>
			<tr><td>{city_lbl}</td><td>{city}</td></tr>
			<tr><td>{zip_lbl}</td><td>{zip}</td></tr>
			<tr><td>{country_lbl}</td><td>{country}</td></tr>
			<tr><td>{state_lbl}</td><td>{state}</td></tr>
			<tr><td>{phone_lbl}</td><td>{phone}</td></tr>
			<tr><td>{email_lbl}</td><td>{email}</td></tr>
			<tr><td>{vatnumber_lbl}</td><td>{vatnumber}</td></tr>
			<tr><td>{taxexempt_lbl}</td><td>{taxexempt}</td></tr>
			<tr><td>{user_taxexempt_request_lbl}</td><td>{user_taxexempt_request}</td></tr>{billing_extrafield}
			</tbody></table> {billing_address_end}';

$newshippingtag = '{shipping_address_start}
			<table border="0"><tbody>
			<tr><td>{companyname_lbl}</td><td>{companyname}</td></tr>
			<tr><td>{firstname_lbl}</td><td>{firstname}</td></tr>
			<tr><td>{lastname_lbl}</td><td>{lastname}</td></tr>
			<tr><td>{address_lbl}</td><td>{address}</td></tr>
			<tr><td>{city_lbl}</td><td>{city}</td></tr>
			<tr><td>{zip_lbl}</td><td>{zip}</td></tr>
			<tr><td>{country_lbl}</td><td>{country}</td></tr>
			<tr><td>{state_lbl}</td><td>{state}</td></tr>
			<tr><td>{phone_lbl}</td><td>{phone}</td></tr>{shipping_extrafield}
			</tbody></table> {shipping_address_end}';
echo $this->pane->startPane('stat-pane');
//Category Template Start
if ($this->detail->template_section == "category")
{
	$title = JText::_('COM_REDSHOP_CATEGORY_FIELDS');
	echo $this->pane->startPanel($title, 'events');?>
	<table class="adminlist">
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(2, 1);
				$tags_admin = $extra_field->getSectionFieldList(2, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);
				//	$tags=$extra_field->getSectionFieldList(2,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(1, 1);
				$tags_admin = $extra_field->getSectionFieldList(1, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);
				//$tags=$extra_field->getSectionFieldList(1,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{producttag:' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_CATEGORY_TEMPLATE_HINT'); ?></td>
		</tr>
		<tr>
			<td>
				<?php    $availableaddtocart = $model->availableaddtocart('add_to_cart');
				if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_NO_ADD_TO_CART_AVAILABLE");
				for ($i = 0; $i < count($availableaddtocart); $i++)
				{
					echo '<span style="margin-left:10px;">{form_addtocart:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT') . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				$related_product = $redtemplate->getTemplate('related_product');
				if (count($related_product) == 0) echo JText::_("COM_REDSHOP_NO_RELATED_PRODUCT_LIGHTBOX_TEMPLATE_AVAILABLE");
				else echo JText::_("COM_REDSHOP_RELATED_PRODUCT_LIGHTBOX_TEMPLATE_AVAILABLE_HINT") . "<br />";
				for ($i = 0; $i < count($related_product); $i++)
				{
					echo '<br /><span style="margin-left:10px;">{related_product_lightbox:' . $related_product[$i]->template_name . '[:lightboxwidth][:lightboxheight]}</span><br />';

					if ($i == count($related_product) - 1)
					{
						echo JText::_("COM_REDSHOP_EXAMPLE_TEMPLATE");
						echo '<br /><span style="margin-left:10px;">{related_product_lightbox:' . $related_product[0]->template_name . ':600:300}</span>';
					}
				}
				?>
			</td>
		</tr>
	</table>
	<?php
	echo $this->pane->endPanel();
	$cat_desc = $redtemplate->getInstallSectionTemplate("category", $setflag = True);
	if ($cat_desc != "")
	{

		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $cat_desc;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}


}
//Category Template End

//Giftcard Template Start
if ($this->detail->template_section == "giftcard")
{
	$title = JText::_('COM_REDSHOP_GIFTCARD_LIST_HINT');
	echo $this->pane->startPanel($title, 'events');    ?>
	<table class="adminlist">
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(13, 1);
				$tags_admin = $extra_field->getSectionFieldList(13, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);
				//$tags=$extra_field->getSectionFieldList(13,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php    echo JText::_('COM_REDSHOP_GIFTCARD_LIST_TAGES');?>
			</td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$gift_desc = $redtemplate->getInstallSectionTemplate("giftcard", $setflag = True);
	if ($gift_desc != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $gift_desc;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Giftcard Template End

//Product Template Start
if ($this->detail->template_section == "product")
{
	$title = JText::_('COM_REDSHOP_PRODUCT_FIELDS');
	echo $this->pane->startPanel($title, 'events');    ?>
	<table class="adminlist">
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(1, 1);
				$tags_admin = $extra_field->getSectionFieldList(1, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);

				//$tags=$extra_field->getSectionFieldList(1,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(12, 1);
				$tags_admin = $extra_field->getSectionFieldList(12, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);
				//$tags=$extra_field->getSectionFieldList(12,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_PRODUCT_TEMPLATE_HINT'); ?></td>
		</tr>
		<tr>
			<td>
				<?php    $availableaddtocart = $model->availableaddtocart('add_to_cart');
				if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_NO_ADD_TO_CART_AVAILABLE");

				for ($i = 0; $i < count($availableaddtocart); $i++)
				{
					echo '<span style="margin-left:10px;">{form_addtocart:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT') . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php    $availableAttTemp = $model->availableaddtocart('attribute_template');
				if (count($availableAttTemp) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");

				for ($i = 0; $i < count($availableAttTemp); $i++)
				{
					echo '<span style="margin-left:10px;">{attribute_template:' . $availableAttTemp[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ATTRIBUTE_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php    $availableAttcartTemp = $model->availableaddtocart('attributewithcart_template');
				if (count($availableAttcartTemp) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");

				for ($i = 0; $i < count($availableAttcartTemp); $i++)
				{
					echo '<span style="margin-left:10px;">{attributewithcart_template:' . $availableAttcartTemp[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ATTRIBUTE_WITH_CART_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php    $availableaddtocart = $model->availableaddtocart('related_product');
				if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_RELATED_PRODUCT_TEMPLATE");

				for ($i = 0; $i < count($availableaddtocart); $i++)
				{
					echo '<span style="margin-left:10px;">{related_product:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_RELATED_PRODUCT_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php    $wrappertemplate = $model->availableaddtocart('wrapper_template');
				if (count($wrappertemplate) == 0) echo JText::_("COM_REDSHOP_NO_WRAPPER_TEMPLATE_AVAILABLE");
				for ($i = 0; $i < count($wrappertemplate); $i++)
				{
					echo '<span style="margin-left:10px;">{wrapper_template:' . $wrappertemplate[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_WRAPPER_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$prd_desc = $redtemplate->getInstallSectionTemplate("product", $setflag = True);
	if ($prd_desc != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $prd_desc;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Product Template End
//Product Sample Field Template Start
if ($this->detail->template_section == "product_sample")
{
	$title = JText::_('COM_REDSHOP_PRODUCT_SAMPLE_FIELDS');
	echo $this->pane->startPanel($title, 'events');    ?>
	<table class="adminlist">
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(9, 1);
				$tags_admin = $extra_field->getSectionFieldList(9, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);
				//	$tags=$extra_field->getSectionFieldList(9,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_PRODUCT_SAMPLE_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$prdsamp_desc = $redtemplate->getInstallSectionTemplate("catalog_sample", $setflag = True);
	if ($prdsamp_desc != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $prdsamp_desc;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Product Sample Field Template End

//Manufacturer Template Start
if ($this->detail->template_section == "manufacturer")
{
	$title = JText::_('COM_REDSHOP_MANUFACTURER_FIELDS');
	echo $this->pane->startPanel($title, 'events');    ?>
	<table class="adminlist">
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(10, 1);
				$tags_admin = $extra_field->getSectionFieldList(10, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);
				//$tags=$extra_field->getSectionFieldList(10,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_MANUFACTURER_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$manu_desc = $redtemplate->getInstallSectionTemplate("manufacturer_listings", $setflag = True);
	if ($manu_desc != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $manu_desc;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Manufacturer Template End
//Manufacturer Products Template Start
if ($this->detail->template_section == "manufacturer_products")
{
	$title = JText::_('COM_REDSHOP_MANUFACTURER_PRODUCTS_FIELDS');
	echo $this->pane->startPanel($title, 'events');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_MANUFACTURER_PRODUCTS_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$manuprd_desc = $redtemplate->getInstallSectionTemplate("manufacturer_products", $setflag = True);
	if ($manuprd_desc != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $manuprd_desc;?>
				</td>
			</tr>
			<tr>
				<td>
					<?php    $availableaddtocart = $model->availableaddtocart('add_to_cart');
					if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_NO_ADD_TO_CART_AVAILABLE");

					for ($i = 0; $i < count($availableaddtocart); $i++)
					{
						echo '<span style="margin-left:10px;">{form_addtocart:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT') . '</span>';
					}    ?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Manufacturer Products Template End

//Cart Template Start
if ($this->detail->template_section == "cart")
{
	$title = JText::_('COM_REDSHOP_CART_TEMPLATE');
	echo $this->pane->startPanel($title, 'cart');        ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_CART_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$cart_desc = $redtemplate->getInstallSectionTemplate("cart", $setflag = True);
	if ($cart_desc != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $cart_desc;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Cart Template End
//Checkout Template Start
if ($this->detail->template_section == "checkout")
{
	$title = JText::_('COM_REDSHOP_CHECKOUT_TEMPLATE');
	echo $this->pane->startPanel($title, 'checkout');        ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_CHECKOUT_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$checkout_desc = $redtemplate->getInstallSectionTemplate("checkout", $setflag = True);
	if ($checkout_desc != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $checkout_desc;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Checkout Template End

//Catalog Cart Template Start
if ($this->detail->template_section == "catalogue_cart")
{
	$title = JText::_('COM_REDSHOP_CATALOG_CART_TEMPLATE');
	echo $this->pane->startPanel($title, 'catalog_cart');        ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_CATALOG_CART_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$catcart_desc = $redtemplate->getInstallSectionTemplate("catalogue_cart", $setflag = True);
	if ($catcart_desc != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $catcart_desc;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Catalog Cart Template End
//Catalog Order Detail Template Start
if ($this->detail->template_section == "catalogue_order_detail")
{
	$title = JText::_('COM_REDSHOP_CATALOG_ORDER_DETAIL_TEMPLATE');
	echo $this->pane->startPanel($title, 'catalog_order_detail');        ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_CATALOG_ORDER_DETAIL_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$catordetail_desc = $redtemplate->getInstallSectionTemplate("catalogue_order_detail", $setflag = True);
	if ($catordetail_desc != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $catordetail_desc;;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Catalog Order Detail Template End

//Catalog Order Receipt Template Start
if ($this->detail->template_section == "catalogue_order_receipt")
{
	$title = JText::_('COM_REDSHOP_CATALOG_ORDER_RECEIPT_TEMPLATE');
	echo $this->pane->startPanel($title, 'catalog_order_receipt');        ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_CATALOG_ORDER_RECEIPT_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$catorrcp_desc = $redtemplate->getInstallSectionTemplate("catalogue_order_receipt", $setflag = True);
	if ($catorrcp_desc != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $catorrcp_desc;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Catalog Order Receipt Template End
//Category Product Template Start
if ($this->detail->template_section == "categoryproduct")
{
	$title = JText::_('COM_REDSHOP_CATEGORY_PRODUCT_TEMPLATE');
	echo $this->pane->startPanel($title, 'category_product_template');        ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_CATEGORY_PRODUCT_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$catprd = $redtemplate->getInstallSectionTemplate("category_product_template", $setflag = True);
	if ($catprd != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $catprd;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Category Product Template End


//Clicktell Message Template Start
if ($this->detail->template_section == "clicktell_sms_message")
{
	$title = JText::_('COM_REDSHOP_CLICKTELL_SMS_MESSAGE_TEMPLATE');
	echo $this->pane->startPanel($title, 'clicktell_sms_template');        ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_CLICKTELL_SMS_MESSAGE_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$click_desc = $redtemplate->getInstallSectionTemplate("clicktell_sms_message", $setflag = True);
	if ($click_desc != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $click_desc;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Clicktell Message Template End
//Empty Cart Template Start
if ($this->detail->template_section == "empty_cart")
{
	$emp_cart = $redtemplate->getInstallSectionTemplate("empty_cart", $setflag = True);
	if ($emp_cart != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $emp_cart;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Empty Cart Template End

//Frontpage Category Template Start
if ($this->detail->template_section == "frontpage_category")
{
	$title = JText::_('COM_REDSHOP_FRONTPAGE_CATEGORY_TEMPLATE');
	echo $this->pane->startPanel($title, 'frontpage_category_detail');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_FRONTPAGE_CATEGORY_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$fr_cat = $redtemplate->getInstallSectionTemplate("frontpage_category", $setflag = True);
	if ($fr_cat != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $fr_cat;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Frontpage Category Template End
//Giftcard List Template Start
if ($this->detail->template_section == "giftcard_list")
{
	$title = JText::_('COM_REDSHOP_GIFTCARD_LIST_TEMPLATE');
	echo $this->pane->startPanel($title, 'manufacturer detail');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_GIFTCARD_LIST_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$gift_list = $redtemplate->getInstallSectionTemplate("giftcard_listing", $setflag = True);
	if ($gift_list != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $gift_list;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Giftcard List Template End

//Manufacturer Detail Template Start
if ($this->detail->template_section == "manufacturer_detail")
{
	$title = JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_TEMPLATE');
	echo $this->pane->startPanel($title, 'manufacturer detail');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_MANUFACTURER_DETAIL_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$manu_detail = $redtemplate->getInstallSectionTemplate($this->detail->template_name, $setflag = True);
	if ($manu_detail != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>

		<tr>
			<td>
				<?php echo $manu_detail;?>
			</td>
		</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Manufacturer Detail Template End
//Catalog Template Start
if ($this->detail->template_section == "catalog")
{
	$title = JText::_('COM_REDSHOP_CATALOG_TEMPLATE');
	echo $this->pane->startPanel($title, 'catalog');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_CATALOG_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$catlog = $redtemplate->getInstallSectionTemplate("catalog", $setflag = True);
	if ($catlog != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $catlog;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Catalog Template End

//Order Detail Template Start
if ($this->detail->template_section == "order_detail")
{
	$title = JText::_('COM_REDSHOP_ORDER_TEMPLATE');
	echo $this->pane->startPanel($title, 'order_template');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_ORDER_DETAIL_TEMPLATE_HINT'); ?></td>
		</tr>
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(14, 1);
				$tags_admin = $extra_field->getSectionFieldList(14, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);
				//	$tags=$extra_field->getSectionFieldList(14,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				else echo JText::_("COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(15, 1);
				$tags_admin = $extra_field->getSectionFieldList(15, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);
				//$tags=$extra_field->getSectionFieldList(15,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				else echo JText::_("COM_REDSHOP_COMPANY_SHIPPING_ADDRESS");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td><?php echo htmlentities($newbillingtag) . "<br><br>" . htmlentities($newshippingtag); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$ord_detail = $redtemplate->getInstallSectionTemplate("order_detail", $setflag = True);
	if ($ord_detail != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $ord_detail;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Order Detail Template End
//Order Receipt Template Start
if ($this->detail->template_section == "order_receipt")
{
	$title = JText::_('COM_REDSHOP_ORDER_TEMPLATE');
	echo $this->pane->startPanel($title, 'order_template');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_ORDER_RECEIPT_TEMPLATE_HINT'); ?></td>
		</tr>
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(14, 1);
				$tags_admin = $extra_field->getSectionFieldList(14, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);
				//	$tags=$extra_field->getSectionFieldList(14,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				else echo JText::_("COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(15, 1);
				$tags_admin = $extra_field->getSectionFieldList(15, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);
				//	$tags=$extra_field->getSectionFieldList(15,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				else echo JText::_("COM_REDSHOP_COMPANY_SHIPPING_ADDRESS");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td><?php echo htmlentities($newbillingtag) . "<br><br>" . htmlentities($newshippingtag); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$ord_receipt = $redtemplate->getInstallSectionTemplate("order_receipt", $setflag = True);
	if ($ord_receipt != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $ord_receipt;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Order Receipt Template End

//Quotation Detail Template Start
if ($this->detail->template_section == "quotation_detail")
{
	$title = JText::_('COM_REDSHOP_QUOTATION_DETAIL_TEMPLATE');
	echo $this->pane->startPanel($title, 'Quotation_detail_template');    ?>
	<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_QUOTATION_DETAIL_TEMPLATE_HINTS'); ?></td>
	</tr>
	</table><?php
	echo $this->pane->endPanel();
	$quo_detail = $redtemplate->getInstallSectionTemplate("quotation_detail", $setflag = True);
	if ($quo_detail != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $quo_detail;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Quotation Detail Template End
//Quotation Request Template Start
if ($this->detail->template_section == "quotation_request")
{
	$title = JText::_('COM_REDSHOP_QUOTATION_TEMPLATE');
	echo $this->pane->startPanel($title, 'Quotation_template');    ?>
	<table class="adminlist">
	<tr>
		<td><?php echo JText::_('COM_REDSHOP_QUOTATION_TEMPLATE_HINTS'); ?></td>
	</tr>
	</table><?php
	echo $this->pane->endPanel();
	$quo_req = $redtemplate->getInstallSectionTemplate("quotation_request_template", $setflag = True);
	if ($quo_req != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $quo_req;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Quotation Request Template End


//Order Print Template Start
if ($this->detail->template_section == "order_print")
{
	$title = JText::_('COM_REDSHOP_ORDER_TEMPLATE');
	echo $this->pane->startPanel($title, 'order_template');    ?>
	<table class="adminlist">
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(14, 1);
				$tags_admin = $extra_field->getSectionFieldList(14, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);
				//$tags=$extra_field->getSectionFieldList(14,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				else echo JText::_("COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(15, 1);
				$tags_admin = $extra_field->getSectionFieldList(15, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);

				//	$tags=$extra_field->getSectionFieldList(15,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				else echo JText::_("COM_REDSHOP_COMPANY_SHIPPING_ADDRESS");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_ORDER_PRINT_TEMPLATE_HINT'); ?></td>
		</tr>
		<tr>
			<td><?php echo htmlentities($newbillingtag) . "<br><br>" . htmlentities($newshippingtag); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$ord_print = $redtemplate->getInstallSectionTemplate("order_print", $setflag = True);
	if ($ord_print != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $ord_print;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Order Print Template End
//Order Template Start
if ($this->detail->template_section == "order_list")
{
	$title = JText::_('COM_REDSHOP_ORDERLIST_TEMPLATE');
	echo $this->pane->startPanel($title, 'orderlist_template');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_ORDERLIST_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$ord_list = $redtemplate->getInstallSectionTemplate("order_list", $setflag = True);
	if ($ord_list != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $ord_list;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Order Template End

//Stockroom Template Start
/*  if($this->detail->template_section=="stockroom_list")
			 {
				$title = JText::_('COM_REDSHOP_STOCKROOM_TEMPLATE' );
				echo $this->pane->startPanel( $title, 'stockroom_template' );	?>
				<table class="adminlist">
				<tr><td><?php echo JText::_('COM_REDSHOP_STOCKROOM_TEMPLATE_HINT'); ?></td></tr>
				<tr><td><?php echo JText::_('COM_REDSHOP_STOCKROOM_PRODUCTS_TEMPLATE_HINT'); ?></td></tr>
				</table>
				<?php	echo $this->pane->endPanel();
				$str_list=$redtemplate->getInstallSectionTemplate($this->detail->template_name,$setflag=True);
					if($str_list!="")
   					{
   					echo $this->pane->startPanel( $default_template, 'events' );	?>
   					<table class="adminlist">
   					<tr><td>
						<?php echo $str_list;?>
					</td></tr>
					</table>
   					<?php
   					echo $this->pane->endPanel(); }
			 }*/
//Stockroom Template End
//Newsletter Template Start
if ($this->detail->template_section == "newsletter")
{
	$title = JText::_('COM_REDSHOP_NEWSLETTER_TEMPLATE');
	echo $this->pane->startPanel($title, 'catalog');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_NEWSLETTER_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$newsletter = $redtemplate->getInstallSectionTemplate("newsletter1", $setflag = True);
	if ($newsletter != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $newsletter;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Newsletter Template End

//Newsletter Product Template Start
if ($this->detail->template_section == "newsletter_product")
{
	$title = JText::_('COM_REDSHOP_NEWSLETTER_PRODUCTS_TEMPLATE');
	echo $this->pane->startPanel($title, 'catalog');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_NEWSLETTER_PRODUCTS_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$newsletter_prd = $redtemplate->getInstallSectionTemplate("newsletter_products", $setflag = True);
	if ($newsletter_prd != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $newsletter_prd;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Newsletter Product Template End

//Related Product Template Start
if ($this->detail->template_section == "related_product")
{
	$title = JText::_('COM_REDSHOP_RELATED_PRODUCT_TEMPLATE');
	echo $this->pane->startPanel($title, 'catalog');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_RELATED_PRODUCT_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$related_prd = $redtemplate->getInstallSectionTemplate("related_products", $setflag = True);
	if ($related_prd != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $related_prd;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Related Product Template End

//Add To  Cart Start
if ($this->detail->template_section == "add_to_cart")
{
	$title = JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE');
	echo $this->pane->startPanel($title, 'catalog');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$add_to_cart = $redtemplate->getInstallSectionTemplate("add_to_cart1", $setflag = True);
	if ($add_to_cart != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $add_to_cart;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}


}
//Add To  Cart End
//Review Template Start
if ($this->detail->template_section == "review")
{
	$title = JText::_('COM_REDSHOP_REVIEW_TEMPLATE');
	echo $this->pane->startPanel($title, 'review');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_REVIEW_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$review = $redtemplate->getInstallSectionTemplate("review", $setflag = True);
	if ($review != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $review;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Review Template End

//Attribute Template Start
if ($this->detail->template_section == "attribute_template")
{
	$title = JText::_('COM_REDSHOP_ATTRIBUTE_TEMPLATE');
	echo $this->pane->startPanel($title, 'catalog');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_ATTRIBUTE_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$attrib = $redtemplate->getInstallSectionTemplate("attributes", $setflag = True);
	if ($attrib != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $attrib;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Attribute Template End

//Attribute With Cart Template Start
if ($this->detail->template_section == "attributewithcart_template")
{
	$title = JText::_('COM_REDSHOP_ATTRIBUTE_WITH_CART_TEMPLATE');
	echo $this->pane->startPanel($title, 'attributewithcart');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_ATTRIBUTE_WITH_CART_TEMPLATE_HINT'); ?></td>
		</tr>
		<tr>
			<td>
				<?php    $availableaddtocart = $model->availableaddtocart('add_to_cart');
				if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_NO_ADD_TO_CART_AVAILABLE");
				for ($i = 0; $i < count($availableaddtocart); $i++)
				{
					echo '<span style="margin-left:10px;">{form_addtocart:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT') . '</span>';
				}    ?>
			</td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$attrib_cart = $redtemplate->getInstallSectionTemplate("attributewithcart_template", $setflag = True);
	if ($attrib_cart != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $attrib_cart;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Attribute With Cart Template End
//Accessory Template Start
if ($this->detail->template_section == "accessory_template")
{
	$title = JText::_('COM_REDSHOP_ACCESSORY_TEMPLATE');
	echo $this->pane->startPanel($title, 'catalog');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_ACCESSORY_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php        echo $this->pane->endPanel();
	$acc = $redtemplate->getInstallSectionTemplate("accessory", $setflag = True);
	if ($acc != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $acc;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Accessory Template End

//Wrapper Template Start
if ($this->detail->template_section == "wrapper_template")
{
	$title = JText::_('COM_REDSHOP_WRAPPER_TEMPLATE');
	echo $this->pane->startPanel($title, 'wrapper');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_WRAPPER_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$wrapper = $redtemplate->getInstallSectionTemplate("wrapper", $setflag = True);
	if ($wrapper != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $wrapper;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Wrapper Template End
//Wishlist Template Start
if ($this->detail->template_section == "wishlist_template")
{
	$title = JText::_('COM_REDSHOP_WISHLIST_TEMPLATE');
	echo $this->pane->startPanel($title, 'wishlist');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_WISHLIST_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$wishlist = $redtemplate->getInstallSectionTemplate("wishlist_list", $setflag = True);
	if ($wishlist != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $wishlist;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Wishlist Template End

//Wishlist Mail Template Start
if ($this->detail->template_section == "wishlist_mail_template")
{
	$title = JText::_('COM_REDSHOP_WISHLIST_MAIL_TEMPLATE');
	echo $this->pane->startPanel($title, 'wishlist');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_WISHLIST_MAIL_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$wishlist_mail = $redtemplate->getInstallSectionTemplate("wishlist_mail", $setflag = True);
	if ($wishlist_mail != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $wishlist_mail;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Wishlist Mail Template End
//Shipping PDF Template Start
if ($this->detail->template_section == "shipping_pdf")
{
	$title = JText::_('COM_REDSHOP_SHIPPING_PDF_TEMPLATE');
	echo $this->pane->startPanel($title, 'shipping');?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_SHIPPING_PDF_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$ship_pdf = $redtemplate->getInstallSectionTemplate("shipping_pdf", $setflag = True);
	if ($ship_pdf != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $ship_pdf;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Shipping PDF Template End

//Ask Question Template Start
if ($this->detail->template_section == "ask_question_template")
{
	$title = JText::_('COM_REDSHOP_ASK_QUESTION_TEMPLATE');
	echo $this->pane->startPanel($title, 'askquestion');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_ASK_QUESTION_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$ask_ques = $redtemplate->getInstallSectionTemplate("ask_question", $setflag = True);
	if ($ask_ques != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $ask_ques;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Ask Question Template End

//Ajax Cart Box Template Start
if ($this->detail->template_section == "ajax_cart_box")
{
	$title = JText::_('COM_REDSHOP_AJAX_CART_BOX_TEMPLATE');
	echo $this->pane->startPanel($title, 'wrapper');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_AJAX_CART_BOX_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$ajax_cart = $redtemplate->getInstallSectionTemplate("ajax_cart_box", $setflag = True);
	if ($ajax_cart != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $ajax_cart;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Ajax Cart Box Template End
//Ajax Cart Box Detail Template Start
if ($this->detail->template_section == "ajax_cart_detail_box")
{
	$title = JText::_('COM_REDSHOP_AJAX_CART_BOX_DETAIL_TEMPLATE');
	echo $this->pane->startPanel($title, 'wrapper');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_AJAX_CART_BOX_DETAIL_TEMPLATE_HINT'); ?></td>
		</tr>
		<tr>
			<td>
				<?php    $availableaddtocart = $model->availableaddtocart('add_to_cart');
				if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_NO_ADD_TO_CART_AVAILABLE");

				for ($i = 0; $i < count($availableaddtocart); $i++)
				{
					echo '<span style="margin-left:10px;">{form_addtocart:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT') . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_AJAX_PRODUCT_TEMPLATE_HINT'); ?></td>
		</tr>
		<tr>
			<td>
				<?php    //$availableAttTemp = $model->availableaddtocart('attribute_template ');
				if (count($availableAttTemp) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");

				for ($i = 0; $i < count($availableAttTemp); $i++)
				{
					echo '<span style="margin-left:10px;">{attribute_template:' . $availableAttTemp[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ATTRIBUTE_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php    //$availableAttcartTemp = $model->availableaddtocart('attributewithcart_template');
				if (count($availableAttcartTemp) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");

				for ($i = 0; $i < count($availableAttcartTemp); $i++)
				{
					echo '<span style="margin-left:10px;">{attributewithcart_template:' . $availableAttcartTemp[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ATTRIBUTE_WITH_CART_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(6, 1);
				$tags_admin = $extra_field->getSectionFieldList(6, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);
				//$tags=$extra_field->getSectionFieldList(6,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");

				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_desc . '</span>';
				}    ?>
			</td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$ajax_cart_box_detail = $redtemplate->getInstallSectionTemplate("ajax_cart_detail_box", $setflag = True);
	if ($ajax_cart_box_detail != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $ajax_cart_box_detail;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Ajax Cart Box Detail Template End

//Redproductfinder Template Start
if ($this->detail->template_section == "redproductfinder")
{
	$title = JText::_('COM_REDSHOP_REDPRODUCTFINDER_TEMPLATE');
	echo $this->pane->startPanel($title, 'redPRODUCTFINDER');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_REDPRODUCTFINDER_TEMPLATE_HINT'); ?></td>
		</tr>
		<tr>
			<td>
				<?php    $availableaddtocart = $model->availableaddtocart('add_to_cart');
				if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_NO_ADD_TO_CART_AVAILABLE");

				for ($i = 0; $i < count($availableaddtocart); $i++)
				{
					echo '<span style="margin-left:10px;">{form_addtocart:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT') . '</span>';
				}    ?>
			</td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$redprdfinder = $redtemplate->getInstallSectionTemplate("redproductfinder", $setflag = True);
	if ($redprdfinder != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $redprdfinder;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Redproductfinder Template End
//Account Template Start
if ($this->detail->template_section == "account_template")
{
	$title = JText::_('COM_REDSHOP_ACCOUNT_TEMPLATE');
	echo $this->pane->startPanel($title, 'account_detail');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_ACCOUNT_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$acc = $redtemplate->getInstallSectionTemplate("my_account_template", $setflag = True);
	if ($acc != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $acc;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}

}
//Account Template End
//Compare Product Template Start
if ($this->detail->template_section == "compare_product")
{
	$title = JText::_('COM_REDSHOP_COMPARE_PRODUCT_TEMPLATE');
	echo $this->pane->startPanel($title, 'compare_product');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_COMPARE_PRODUCT_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$cmp_prd = $redtemplate->getInstallSectionTemplate("compare_product", $setflag = True);
	if ($cmp_prd != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $cmp_prd;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Compare Product Template End
//Payment Method Template Start
if ($this->detail->template_section == "redshop_payment")
{
	$title = JText::_('COM_REDSHOP_PAYMENT_METHOD_TEMPLATE');
	echo $this->pane->startPanel($title, 'redshop_payment');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_PAYMENT_METHOD_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$payment = $redtemplate->getInstallSectionTemplate("payment_method", $setflag = True);
	if ($payment != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $payment;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Payment Method Template End

//Shipping Method Template Start
if ($this->detail->template_section == "redshop_shipping")
{
	$title = JText::_('COM_REDSHOP_SHIPPING_METHOD_TEMPLATE');
	echo $this->pane->startPanel($title, 'redshop_shipping');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_SHIPPING_METHOD_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$shipping = $redtemplate->getInstallSectionTemplate("shipping_method", $setflag = True);
	if ($shipping != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $shipping;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Shipping Method Template End

//Shipping Box Template Start
if ($this->detail->template_section == "shippingbox")
{
	$title = JText::_('COM_REDSHOP_SHIPPING_BOX_TEMPLATE');
	echo $this->pane->startPanel($title, 'shippingbox');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_SHIPPING_BOX_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$ship_box = $redtemplate->getInstallSectionTemplate("shipping_box", $setflag = True);
	if ($ship_box != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $ship_box;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Shipping Box Template End

//Onestep Checkout Template Start
if ($this->detail->template_section == "onestep_checkout")
{
	$title = JText::_('COM_REDSHOP_ONESTEP_CHECKOUT_TEMPLATE');
	echo $this->pane->startPanel($title, '1stepcheckout');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_ONESTEP_CHECKOUT_TEMPLATE_HINT'); ?></td>
		</tr>
		<tr>
			<td><?php echo htmlentities($newbillingtag);?></td>
		</tr>
		<tr>
			<td>
				<?php    $availablecheckout = $model->availableaddtocart('checkout');
				if (count($availablecheckout) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");
				for ($i = 0; $i < count($availablecheckout); $i++)
				{
					echo '<span style="margin-left:10px;">{checkout_template:' . $availablecheckout[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_CHECKOUT_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php    $availableshippingbox = $model->availableaddtocart('shippingbox');
				if (count($availableshippingbox) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");
				for ($i = 0; $i < count($availableshippingbox); $i++)
				{
					echo '<span style="margin-left:10px;">{shippingbox_template:' . $availableshippingbox[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_SHIPPING_BOX_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php    $availableshipping = $model->availableaddtocart('redshop_shipping');
				if (count($availableshipping) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");
				for ($i = 0; $i < count($availableshipping); $i++)
				{
					echo '<span style="margin-left:10px;">{shipping_template:' . $availableshipping[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_SHIPPING_METHOD_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php    $availablepayment = $model->availableaddtocart('redshop_payment');
				if (count($availablepayment) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");
				for ($i = 0; $i < count($availablepayment); $i++)
				{
					echo '<span style="margin-left:10px;">{payment_template:' . $availablepayment[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_PAYMENT_METHOD_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$one_step = $redtemplate->getInstallSectionTemplate("onestep_checkout", $setflag = True);
	if ($one_step != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $one_step;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//One Step Checkout End
//Change Cart Attribute Temlate Start
if ($this->detail->template_section == "change_cart_attribute")
{
	$title = JText::_('COM_REDSHOP_CHANGE_CART_ATTRIBUTE_TEMPLATE');
	echo $this->pane->startPanel($title, 'cartattribute');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_CHANGE_CART_ATTRIBUTE_TEMPLATE_HINT'); ?></td>
		</tr>
		<tr>
			<td>
				<?php    $availableaddtocart = $model->availableaddtocart('attribute_template ');
				if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");
				for ($i = 0; $i < count($availableaddtocart); $i++)
				{
					echo '<span style="margin-left:10px;">{attribute_template:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ATTRIBUTE_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php    $availableAttcartTemp = $model->availableaddtocart('attributewithcart_template');
				if (count($availableAttcartTemp) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");
				for ($i = 0; $i < count($availableAttcartTemp); $i++)
				{
					echo '<span style="margin-left:10px;">{attributewithcart_template:' . $availableAttcartTemp[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ATTRIBUTE_WITH_CART_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$change_cart_attrib = $redtemplate->getInstallSectionTemplate("change_cart_attribute_template", $setflag = True);
	if ($change_cart_attrib != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $change_cart_attrib;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Change Cart Attribute Temlate End

//Letter Search Template Start
if ($this->detail->template_section == "searchletter")
{
	$title = JText::_('COM_REDSHOP_LETTER_SEARCH_TEMPLATE');
	echo $this->pane->startPanel($title, 'searchletter');    ?>
	<table class="adminlist">
		<tr>
			<td>
				<?php
				$tags_front = $extra_field->getSectionFieldList(1, 1);
				$tags_admin = $extra_field->getSectionFieldList(1, 0);
				$tags = array_merge((array) $tags_admin, (array) $tags_front);

				//$tags=$extra_field->getSectionFieldList(1,1);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				for ($i = 0; $i < count($tags); $i++)
				{
					echo '<span style="margin-left:10px;">{' . $tags[$i]->field_name . '} -- ' . $tags[$i]->field_title . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_LETTER_SEARCH_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$letter = $redtemplate->getInstallSectionTemplate("letter_search_product", $setflag = True);
	if ($letter != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $letter;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Letter Search Template End

//Product Content Template Start
if ($this->detail->template_section == "product_content_template")
{
	$title = JText::_('COM_REDSHOP_PRODUCT_CONTENT_TEMPLATE');
	echo $this->pane->startPanel($title, 'product_content');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_PRODUCT_CONTENT_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$product_content = $redtemplate->getInstallSectionTemplate("product_content", $setflag = True);
	if ($product_content != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $product_content;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Product Content Template End

//Quotation Cart Template Start
if ($this->detail->template_section == "quotation_cart")
{
	$title = JText::_('COM_REDSHOP_QUOTATION_CART_TEMPLATE');
	echo $this->pane->startPanel($title, 'product_content');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_QUOTATION_CART_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$quotation_cart = $redtemplate->getInstallSectionTemplate("quotation_cart_template", $setflag = True);
	if ($quotation_cart != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $quotation_cart;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Quotation Cart Template End

//Billing Template Start
if ($this->detail->template_section == "billing_template")
{
	$title = JText::_('COM_REDSHOP_BILLING_TEMPLATE');
	echo $this->pane->startPanel($title, 'billing_template');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_BILLING_TEMPLATE_HINT'); ?></td>
		</tr>
		<tr>
			<td>
				<?php    $available = $model->availableaddtocart('private_billing_template ');
				if (count($available) == 0) echo JTEXT::_("COM_REDSHOP_PRIVATE_BILLING_TEMPLATE");
				for ($i = 0; $i < count($available); $i++)
				{
					echo '<span style="margin-left:10px;">{private_billing_template:' . $available[$i]->template_name . '} -- ' . JText::_('PRIVATE_BILLING_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php    $available = $model->availableaddtocart('company_billing_template ');
				if (count($available) == 0) echo JTEXT::_("COM_REDSHOP_COMPANY_BILLING_TEMPLATE");
				for ($i = 0; $i < count($available); $i++)
				{
					echo '<span style="margin-left:10px;">{company_billing_template:' . $available[$i]->template_name . '} -- ' . JText::_('COMPANY_BILLING_TEMPLATE') . '</span>';
				}    ?>
			</td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$billing_template = $redtemplate->getInstallSectionTemplate("billing_template", $setflag = True);
	if ($billing_template != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $billing_template;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Billing Template End

//Private Billing Template Start
if ($this->detail->template_section == "private_billing_template")
{
	$title = JText::_('COM_REDSHOP_PRIVATE_BILLING_TEMPLATE');
	echo $this->pane->startPanel($title, 'private_billing_template');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_PRIVATE_BILLING_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$billing_template = $redtemplate->getInstallSectionTemplate("private_billing_template", $setflag = True);
	if ($billing_template != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td><?php echo $billing_template;?></td>
			</tr>
		</table>
		<?php        echo $this->pane->endPanel();
	}
}
//Billing Template End

//Billing Template Start
if ($this->detail->template_section == "company_billing_template")
{
	$title = JText::_('COM_REDSHOP_COMPANY_BILLING_TEMPLATE');
	echo $this->pane->startPanel($title, 'company_billing_template');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_COMPANY_BILLING_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$billing_template = $redtemplate->getInstallSectionTemplate("company_billing_template", $setflag = True);
	if ($billing_template != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td><?php echo $billing_template;?></td>
			</tr>
		</table>
		<?php        echo $this->pane->endPanel();
	}
}
//Billing Template End

//Shipping Template Start
if ($this->detail->template_section == "shipping_template")
{
	$title = JText::_('COM_REDSHOP_SHIPPING_TEMPLATE');
	echo $this->pane->startPanel($title, 'shipping_template');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_SHIPPING_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$shipping_template = $redtemplate->getInstallSectionTemplate("shipping_template", $setflag = True);
	if ($shipping_template != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $shipping_template;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Shipping Template End
//Stock Note Template Start
if ($this->detail->template_section == "stock_note")
{
	$title = JText::_('COM_REDSHOP_STOCK_NOTE_TEMPLATE');
	echo $this->pane->startPanel($title, 'stock_note');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_STOCK_NOTE_TEMPLATE_HINT'); ?></td>
		</tr>
		<tr>
			<td><?php echo htmlentities($newbillingtag) . "<br><br>" . htmlentities($newshippingtag); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$stock_note = $redtemplate->getInstallSectionTemplate("stock_note", $setflag = True);
	if ($stock_note != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $stock_note;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Stock Note Template End
//Shipment invoice Template Start
if ($this->detail->template_section == "shippment_invoice_template")
{
	$title = JText::_('COM_REDSHOP_SHIPMENT_INVOICE_TEMPLATE');
	echo $this->pane->startPanel($title, 'shippment_invoice_template');    ?>
	<table class="adminlist">
		<tr>
			<td><?php echo JText::_('COM_REDSHOP_SHIPMENT_INVOICE_TEMPLATE_HINT'); ?></td>
		</tr>
	</table>
	<?php    echo $this->pane->endPanel();
	$shipment = $redtemplate->getInstallSectionTemplate("shippment_invoice_template", $setflag = True);
	if ($shipment != "")
	{
		echo $this->pane->startPanel($default_template, 'events');    ?>
		<table class="adminlist">
			<tr>
				<td>
					<?php echo $shipment;?>
				</td>
			</tr>
		</table>
		<?php
		echo $this->pane->endPanel();
	}
}
//Shipment invoice Template End

echo $this->pane->endPane();?>
