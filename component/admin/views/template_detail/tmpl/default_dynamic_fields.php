<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
$model            = $this->getModel('template_detail');
$redtemplate      = Redtemplate::getInstance();
$extra_field      = extra_field::getInstance();
$default_template = JText::_('COM_REDSHOP_DEFAULT_TEMPLATE_DETAIL');
$newbillingtag    = '{billing_address_start}
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

echo JHtml::_('tabs.start', 'template-dynamic-field');

$title = JText::_('COM_REDSHOP_AVAILABLE_TEMPLATE_TAGS');

// Category Template Start
if ($this->detail->template_section == "category")
{
	echo JHtml::_('tabs.panel', $title, 'category-fields'); ?>
    <table class="adminlist table table-striped">
		<?php
		$tags_front = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_CATEGORY);
		$tags_admin = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_CATEGORY, 0);
		$tags       = array_merge((array) $tags_admin, (array) $tags_front);
		?>
		<?php if (!empty($tags)): ?>
            <tr>
                <td>
                    <h3><?php echo JText::_("COM_REDSHOP_FIELDS") ?></h3>
					<?php foreach ($tags as $tag): ?>
                        <div style="margin-left:10px;">{<?php echo $tag->name ?>} -- <?php echo $tag->title ?></div>
					<?php endforeach; ?>
                </td>
            </tr>
		<?php endif; ?>
		<?php
		$tags_front = RedshopHelperExtrafields::getSectionFieldList(1, 1);
		$tags_admin = RedshopHelperExtrafields::getSectionFieldList(1, 0);
		$tags       = array_merge((array) $tags_admin, (array) $tags_front);
		?>
		<?php if (!empty($tags)): ?>
            <tr>
                <td>
                    <h3><?php echo JText::_("COM_REDSHOP_TEMPLATE_PRODUCT_FIELDS_TITLE") ?></h3>
					<?php foreach ($tags as $tag): ?>
                        <div style="margin-left:10px;">{producttag:<?php echo $tag->name ?>} -- <?php echo $tag->title ?></div>
					<?php endforeach; ?>
                </td>
            </tr>
		<?php endif; ?>
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('category') ?></td>
        </tr>
        <tr>
            <td>
				<?php $availableAddtocart = $model->availableaddtocart('add_to_cart'); ?>
				<?php if (count($availableAddtocart) == 0): ?>
                    <strong><?php echo JText::_("COM_REDSHOP_NO_ADD_TO_CART_AVAILABLE"); ?></strong>
				<?php else: ?>
					<?php foreach ($availableAddtocart as $tag): ?>
                        <div style="margin-left:10px;">{form_addtocart:<?php echo $tag->template_name ?>}
                            -- <?php echo JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT') ?></div>
					<?php endforeach; ?>
				<?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php
				$related_product = $redtemplate->getTemplate('related_product');
				if (count($related_product) == 0) echo JText::_("COM_REDSHOP_NO_RELATED_PRODUCT_LIGHTBOX_TEMPLATE_AVAILABLE");
				else echo JText::_("COM_REDSHOP_RELATED_PRODUCT_LIGHTBOX_TEMPLATE_AVAILABLE_HINT") . "<br />";
				for ($i = 0, $in = count($related_product); $i < $in; $i++)
				{
					echo '<br /><div style="margin-left:10px;">{related_product_lightbox:' . $related_product[$i]->template_name . '[:lightboxwidth][:lightboxheight]}</div><br />';

					if ($i == count($related_product) - 1)
					{
						echo JText::_("COM_REDSHOP_EXAMPLE_TEMPLATE");
						echo '<br /><div style="margin-left:10px;">{related_product_lightbox:' . $related_product[0]->template_name . ':600:300}</div>';
					}
				}
				?>
            </td>
        </tr>
    </table>
	<?php
	$cat_desc = $redtemplate->getInstallSectionTemplate("category", $setflag = true);

	if ($cat_desc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'category-desc'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $cat_desc; ?>
                </td>
            </tr>
        </table>
		<?php
	}
}

// Giftcard Template Start
if ($this->detail->template_section == "giftcard")
{
	echo JHtml::_('tabs.panel', $title, 'giftcard-fields'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td>
				<?php
				$tags_front = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_GIFT_CARD_USER_FIELD, 1);
				$tags_admin = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_GIFT_CARD_USER_FIELD, 0);
				$tags       = array_merge((array) $tags_admin, (array) $tags_front);

				if (count($tags) == 0):
					echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				endif;

				for ($i = 0, $in = count($tags); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php echo Redtemplate::getTemplateValues('giftcard'); ?>
            </td>
        </tr>
    </table>
	<?php
	$gift_desc = $redtemplate->getInstallSectionTemplate("giftcard", $setflag = true);
	if ($gift_desc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'giftcard-desc'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $gift_desc; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Product Template Start
if ($this->detail->template_section == "product")
{
	echo JHtml::_('tabs.panel', $title, 'events'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td>
				<?php
				$tags_front   = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_PRODUCT, 1);
				$tags_admin   = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_PRODUCT, 0);
				$tags         = array_merge((array) $tags_admin, (array) $tags_front);
				$numberOfTags = count($tags);

				if ($numberOfTags)
				{
					echo '<b>' . JText::_("COM_REDSHOP_PRODUCT_FIELDS") . '</b>';
				}

				for ($i = 0; $i < $numberOfTags; $i++)
				{
					echo '<div style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php
				$displayedTags = array();
				$siteTags      = (array) RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::TYPE_DATE_PICKER, 1);
				$adminTags     = (array) RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::TYPE_DATE_PICKER, 0);
				$tags          = array_merge($adminTags, $siteTags);
				$numberOfTags  = count($tags);
				?>
				<?php if (!empty($tags)): ?>
                    <h4><?php echo JText::_("COM_REDSHOP_PRODUCT_USERFIELD") ?></h4>
                    <ul class="nav nav-list">
						<?php foreach ($tags as $tag): ?>
							<?php if (!in_array($tag->name, $displayedTags)): ?>
                                <li role="presentation">{<?php echo $tag->name ?>} -- <?php echo $tag->title ?></li>
								<?php $displayedTags[] = $tag->name; ?>
							<?php endif; ?>
						<?php endforeach; ?>
                    </ul>
				<?php endif; ?>
            </td>
        </tr>
        <tr>
            <td><?php echo RedshopHelperTemplate::getTemplateValues('product') ?></td>
        </tr>
        <tr>
            <td>
				<?php $availableaddtocart = $model->availableaddtocart('add_to_cart');
				if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_NO_ADD_TO_CART_AVAILABLE");

				for ($i = 0, $in = count($availableaddtocart); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{form_addtocart:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT') . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php $availableAttTemp = $model->availableaddtocart('attribute_template');
				if (count($availableAttTemp) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");

				for ($i = 0, $in = count($availableAttTemp); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{attribute_template:' . $availableAttTemp[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ATTRIBUTE_TEMPLATE') . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php $availableAttcartTemp = $model->availableaddtocart('attributewithcart_template');
				if (count($availableAttcartTemp) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");

				for ($i = 0, $in = count($availableAttcartTemp); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{attributewithcart_template:' . $availableAttcartTemp[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ATTRIBUTE_WITH_CART_TEMPLATE') . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php $availableaddtocart = $model->availableaddtocart('related_product');
				if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_RELATED_PRODUCT_TEMPLATE");

				for ($i = 0, $in = count($availableaddtocart); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{related_product:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_RELATED_PRODUCT_TEMPLATE') . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php $wrappertemplate = $model->availableaddtocart('wrapper_template');
				if (count($wrappertemplate) == 0) echo JText::_("COM_REDSHOP_NO_WRAPPER_TEMPLATE_AVAILABLE");
				for ($i = 0, $in = count($wrappertemplate); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{wrapper_template:' . $wrappertemplate[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_WRAPPER_TEMPLATE') . '</div>';
				} ?>
            </td>
        </tr>
    </table>
	<?php
	$prd_desc = $redtemplate->getInstallSectionTemplate("product", $setflag = true);
	if ($prd_desc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $prd_desc; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Product Sample Field Template Start
if ($this->detail->template_section == "product_sample")
{
	echo JHtml::_('tabs.panel', $title, 'events'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td>
				<?php
				$tags_front = RedshopHelperExtrafields::getSectionFieldList(9, 1);
				$tags_admin = RedshopHelperExtrafields::getSectionFieldList(9, 0);
				$tags       = array_merge((array) $tags_admin, (array) $tags_front);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				for ($i = 0, $in = count($tags); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('product_sample'); ?></td>
        </tr>
    </table>
	<?php
	$prdsamp_desc = $redtemplate->getInstallSectionTemplate("catalog_sample", $setflag = true);
	if ($prdsamp_desc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $prdsamp_desc; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Manufacturer Template Start
if ($this->detail->template_section == "manufacturer")
{
	echo JHtml::_('tabs.panel', $title, 'events'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td>
				<?php
				$tags_front = RedshopHelperExtrafields::getSectionFieldList(10, 1);
				$tags_admin = RedshopHelperExtrafields::getSectionFieldList(10, 0);
				$tags       = array_merge((array) $tags_admin, (array) $tags_front);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				for ($i = 0, $in = count($tags); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('manufacturer') ?></td>
        </tr>
    </table>
	<?php
	$manu_desc = $redtemplate->getInstallSectionTemplate("manufacturer_listings", $setflag = true);
	if ($manu_desc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $manu_desc; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Manufacturer Products Template Start
if ($this->detail->template_section == "manufacturer_products")
{
	echo JHtml::_('tabs.panel', $title, 'events'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('manufacturer_products'); ?></td>
        </tr>
    </table>
	<?php
	$manuprd_desc = $redtemplate->getInstallSectionTemplate("manufacturer_products", $setflag = true);
	if ($manuprd_desc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $manuprd_desc; ?>
                </td>
            </tr>
            <tr>
                <td>
					<?php $availableaddtocart = $model->availableaddtocart('add_to_cart');
					if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_NO_ADD_TO_CART_AVAILABLE");

					for ($i = 0, $in = count($availableaddtocart); $i < $in; $i++)
					{
						echo '<div style="margin-left:10px;">{form_addtocart:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT') . '</div>';
					} ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Cart Template Start
if ($this->detail->template_section == "cart")
{
	echo JHtml::_('tabs.panel', $title, 'cart'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('cart'); ?></td>
        </tr>
    </table>
	<?php
	$cart_desc = $redtemplate->getInstallSectionTemplate("cart", $setflag = true);
	if ($cart_desc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $cart_desc; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Checkout Template Start
if ($this->detail->template_section == "checkout")
{
	echo JHtml::_('tabs.panel', $title, 'checkout'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('checkout'); ?></td>
        </tr>
    </table>
	<?php
	$checkout_desc = $redtemplate->getInstallSectionTemplate("checkout", $setflag = true);
	if ($checkout_desc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $checkout_desc; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Catalog Cart Template Start
if ($this->detail->template_section == "catalogue_cart")
{
	echo JHtml::_('tabs.panel', $title, 'catalog_cart'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('catalogue_cart'); ?></td>
        </tr>
    </table>
	<?php
	$catcart_desc = $redtemplate->getInstallSectionTemplate("catalogue_cart", $setflag = true);
	if ($catcart_desc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $catcart_desc; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Catalog Order Detail Template Start
if ($this->detail->template_section == "catalogue_order_detail")
{
	echo JHtml::_('tabs.panel', $title, 'catalog_order_detail'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('catalogue_order_detail'); ?></td>
        </tr>
    </table>
	<?php
	$catordetail_desc = $redtemplate->getInstallSectionTemplate("catalogue_order_detail", $setflag = true);
	if ($catordetail_desc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $catordetail_desc;; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Catalog Order Receipt Template Start
if ($this->detail->template_section == "catalogue_order_receipt")
{
	echo JHtml::_('tabs.panel', $title, 'catalog_order_receipt'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('catalogue_order_receipt'); ?></td>
        </tr>
    </table>
	<?php
	$catorrcp_desc = $redtemplate->getInstallSectionTemplate("catalogue_order_receipt", $setflag = true);
	if ($catorrcp_desc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $catorrcp_desc; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Category Product Template Start
if ($this->detail->template_section == "categoryproduct")
{
	echo JHtml::_('tabs.panel', $title, 'category_product_template'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('category_product'); ?></td>
        </tr>
    </table>
	<?php
	$catprd = $redtemplate->getInstallSectionTemplate("category_product_template", $setflag = true);
	if ($catprd != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $catprd; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Clicktell Message Template Start
if ($this->detail->template_section == "clicktell_sms_message")
{
	echo JHtml::_('tabs.panel', $title, 'clicktell_sms_template'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('clicktell_sms_message'); ?></td>
        </tr>
    </table>
	<?php
	$click_desc = $redtemplate->getInstallSectionTemplate("clicktell_sms_message", $setflag = true);
	if ($click_desc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $click_desc; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Empty Cart Template Start
if ($this->detail->template_section == "empty_cart")
{
	$emp_cart = $redtemplate->getInstallSectionTemplate("empty_cart", $setflag = true);
	if ($emp_cart != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $emp_cart; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Frontpage Category Template Start
if ($this->detail->template_section == "frontpage_category")
{
	echo JHtml::_('tabs.panel', $title, 'frontpage_category_detail'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('frontpage_category'); ?></td>
        </tr>
    </table>
	<?php
	$fr_cat = $redtemplate->getInstallSectionTemplate("frontpage_category", $setflag = true);
	if ($fr_cat != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $fr_cat; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Giftcard List Template Start
if ($this->detail->template_section == "giftcard_list")
{
	echo JHtml::_('tabs.panel', $title, 'manufacturer detail'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('giftcard_list'); ?></td>
        </tr>
    </table>
	<?php
	$gift_list = $redtemplate->getInstallSectionTemplate("giftcard_listing", $setflag = true);
	if ($gift_list != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $gift_list; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Manufacturer Detail Template Start
if ($this->detail->template_section == "manufacturer_detail")
{
	echo JHtml::_('tabs.panel', $title, 'manufacturer detail'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('manufacturer_detail'); ?></td>
        </tr>
    </table>
	<?php
	$manu_detail = $redtemplate->getInstallSectionTemplate($this->detail->template_name, $setflag = true);
	if ($manu_detail != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>

        <tr>
            <td>
				<?php echo $manu_detail; ?>
            </td>
        </tr>
        </table>
		<?php

	}
}

// Catalog Template Start
if ($this->detail->template_section == "catalog")
{
	echo JHtml::_('tabs.panel', $title, 'catalog'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('catalogue'); ?></td>
        </tr>
    </table>
	<?php
	$catlog = $redtemplate->getInstallSectionTemplate("catalog", $setflag = true);
	if ($catlog != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $catlog; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Order Detail Template Start
if ($this->detail->template_section == "order_detail")
{
	echo JHtml::_('tabs.panel', $title, 'order_template'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('order_detail'); ?></td>
        </tr>
        <tr>
            <td>
				<?php
				$tags_front = RedshopHelperExtrafields::getSectionFieldList(14, 1);
				$tags_admin = RedshopHelperExtrafields::getSectionFieldList(14, 0);
				$tags       = array_merge((array) $tags_admin, (array) $tags_front);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				else echo JText::_("COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS");
				for ($i = 0, $in = count($tags); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php
				$tags_front = RedshopHelperExtrafields::getSectionFieldList(15, 1);
				$tags_admin = RedshopHelperExtrafields::getSectionFieldList(15, 0);
				$tags       = array_merge((array) $tags_admin, (array) $tags_front);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				else echo JText::_("COM_REDSHOP_COMPANY_SHIPPING_ADDRESS");
				for ($i = 0, $in = count($tags); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td><?php echo htmlentities($newbillingtag) . "<br><br>" . htmlentities($newshippingtag); ?></td>
        </tr>
    </table>
	<?php
	$ord_detail = $redtemplate->getInstallSectionTemplate("order_detail", $setflag = true);
	if ($ord_detail != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $ord_detail; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Order Receipt Template Start
if ($this->detail->template_section == "order_receipt")
{
	echo JHtml::_('tabs.panel', $title, 'order_template'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('order_receipt'); ?></td>
        </tr>
        <tr>
            <td>
				<?php
				$tags_front = RedshopHelperExtrafields::getSectionFieldList(14, 1);
				$tags_admin = RedshopHelperExtrafields::getSectionFieldList(14, 0);
				$tags       = array_merge((array) $tags_admin, (array) $tags_front);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				else echo JText::_("COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS");
				for ($i = 0, $in = count($tags); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php
				$tags_front = RedshopHelperExtrafields::getSectionFieldList(15, 1);
				$tags_admin = RedshopHelperExtrafields::getSectionFieldList(15, 0);
				$tags       = array_merge((array) $tags_admin, (array) $tags_front);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				else echo JText::_("COM_REDSHOP_COMPANY_SHIPPING_ADDRESS");
				for ($i = 0, $in = count($tags); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td><?php echo htmlentities($newbillingtag) . "<br><br>" . htmlentities($newshippingtag); ?></td>
        </tr>
    </table>
	<?php
	$ord_receipt = $redtemplate->getInstallSectionTemplate("order_receipt", $setflag = true);
	if ($ord_receipt != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $ord_receipt; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Quotation Detail Template Start
if ($this->detail->template_section == "quotation_detail")
{
	echo JHtml::_('tabs.panel', $title, 'Quotation_detail_template'); ?>
    <table class="adminlist table table-striped">
    <tr>
        <td><?php echo Redtemplate::getTemplateValues('quotation_detail'); ?></td>
    </tr>
    </table><?php

	$quo_detail = $redtemplate->getInstallSectionTemplate("quotation_detail", $setflag = true);
	if ($quo_detail != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $quo_detail; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Quotation Request Template Start
if ($this->detail->template_section == "quotation_request")
{
	echo JHtml::_('tabs.panel', $title, 'Quotation_template'); ?>
    <table class="adminlist table table-striped">
    <tr>
        <td><?php echo Redtemplate::getTemplateValues('quotation_request'); ?></td>
    </tr>
    </table><?php

	$quo_req = $redtemplate->getInstallSectionTemplate("quotation_request_template", $setflag = true);
	if ($quo_req != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $quo_req; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Order Print Template Start
if ($this->detail->template_section == "order_print")
{
	echo JHtml::_('tabs.panel', $title, 'order_template'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td>
				<?php
				$tags_front = RedshopHelperExtrafields::getSectionFieldList(14, 1);
				$tags_admin = RedshopHelperExtrafields::getSectionFieldList(14, 0);
				$tags       = array_merge((array) $tags_admin, (array) $tags_front);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				else echo JText::_("COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS");
				for ($i = 0, $in = count($tags); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php
				$tags_front = RedshopHelperExtrafields::getSectionFieldList(15, 1);
				$tags_admin = RedshopHelperExtrafields::getSectionFieldList(15, 0);
				$tags       = array_merge((array) $tags_admin, (array) $tags_front);

				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
				else echo JText::_("COM_REDSHOP_COMPANY_SHIPPING_ADDRESS");
				for ($i = 0, $in = count($tags); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('order_print'); ?></td>
        </tr>
        <tr>
            <td><?php echo htmlentities($newbillingtag) . "<br><br>" . htmlentities($newshippingtag); ?></td>
        </tr>
    </table>
	<?php
	$ord_print = $redtemplate->getInstallSectionTemplate("order_print", $setflag = true);
	if ($ord_print != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $ord_print; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Order Template Start
if ($this->detail->template_section == "order_list")
{
	echo JHtml::_('tabs.panel', $title, 'orderlist_template'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('orderlist'); ?></td>
        </tr>
    </table>
	<?php
	$ord_list = $redtemplate->getInstallSectionTemplate("order_list", $setflag = true);
	if ($ord_list != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $ord_list; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Newsletter Template Start
if ($this->detail->template_section == "newsletter")
{
	echo JHtml::_('tabs.panel', $title, 'catalog'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('newsletter'); ?></td>
        </tr>
    </table>
	<?php
	$newsletter = $redtemplate->getInstallSectionTemplate("newsletter1", $setflag = true);
	if ($newsletter != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $newsletter; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Newsletter Product Template Start
if ($this->detail->template_section == "newsletter_product")
{
	echo JHtml::_('tabs.panel', $title, 'catalog'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('newsletter_product'); ?></td>
        </tr>
    </table>
	<?php
	$newsletter_prd = $redtemplate->getInstallSectionTemplate("newsletter_products", $setflag = true);
	if ($newsletter_prd != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $newsletter_prd; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Related Product Template Start
if ($this->detail->template_section == "related_product")
{
	echo JHtml::_('tabs.panel', $title, 'catalog'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td>
				<?php
				$tags_front    = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_PRODUCT, 1);
				$tags_admin    = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_PRODUCT, 0);
				$tags          = array_merge((array) $tags_admin, (array) $tags_front);
				$numberOfTags  = count($tags);
				$displayedTags = array();
				?>

				<?php if ($numberOfTags): ?>
                    <b><?php echo JText::_("COM_REDSHOP_PRODUCT_FIELDS") ?></b>
                    <ul class="nav nav-list">
						<?php foreach ($tags as $tag): ?>
							<?php if (!in_array($tag->name, $displayedTags)): ?>
                                <li role="presentation">{<?php echo $tag->name ?>} -- <?php echo $tag->title ?></li>
								<?php $displayedTags[] = $tag->name; ?>
							<?php endif; ?>
						<?php endforeach; ?>
                    </ul>
				<?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php
				$displayedTags = array();
				$tags_front    = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, 1);
				$tags_admin    = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, 0);
				$tags          = array_merge($tags_front, $tags_admin);
				?>

				<?php if (!empty($tags)): ?>
                    <b><?php echo JText::_("COM_REDSHOP_PRODUCT_USERFIELD") ?></b>
                    <ul class="nav nav-list">
						<?php foreach ($tags as $tag): ?>
							<?php if (!in_array($tag->name, $displayedTags)): ?>
                                <li role="presentation">{<?php echo $tag->name ?>} -- <?php echo $tag->title ?></li>
								<?php $displayedTags[] = $tag->name; ?>
							<?php endif; ?>
						<?php endforeach; ?>
                    </ul>
				<?php endif; ?>
            </td>
        </tr>
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('related_product'); ?></td>
        </tr>
    </table>
	<?php
	$related_prd = $redtemplate->getInstallSectionTemplate("related_products", $setflag = true);
	if ($related_prd != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $related_prd; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Add To  Cart Start
if ($this->detail->template_section == "add_to_cart")
{
	echo JHtml::_('tabs.panel', $title, 'catalog'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('add_to_cart'); ?></td>
        </tr>
    </table>
	<?php
	$add_to_cart = $redtemplate->getInstallSectionTemplate("add_to_cart1", $setflag = true);
	if ($add_to_cart != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $add_to_cart; ?>
                </td>
            </tr>
        </table>
		<?php

	}


}

// Review Template Start
if ($this->detail->template_section == "review")
{
	echo JHtml::_('tabs.panel', $title, 'review'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('review'); ?></td>
        </tr>
    </table>
	<?php
	$review = $redtemplate->getInstallSectionTemplate("review", $setflag = true);
	if ($review != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $review; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Attribute Template Start
if ($this->detail->template_section == "attribute_template")
{
	echo JHtml::_('tabs.panel', $title, 'catalog'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('attribute'); ?></td>
        </tr>
    </table>
	<?php
	$attrib = $redtemplate->getInstallSectionTemplate("attributes", $setflag = true);
	if ($attrib != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $attrib; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Attribute With Cart Template Start
if ($this->detail->template_section == "attributewithcart_template")
{
	echo JHtml::_('tabs.panel', $title, 'attributewithcart'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('attribute_with_cart'); ?></td>
        </tr>
        <tr>
            <td>
				<?php $availableaddtocart = $model->availableaddtocart('add_to_cart');
				if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_NO_ADD_TO_CART_AVAILABLE");
				for ($i = 0, $in = count($availableaddtocart); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{form_addtocart:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT') . '</div>';
				} ?>
            </td>
        </tr>
    </table>
	<?php
	$attrib_cart = $redtemplate->getInstallSectionTemplate("attributewithcart_template", $setflag = true);
	if ($attrib_cart != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $attrib_cart; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Accessory Template Start
if ($this->detail->template_section == "accessory_template")
{
	echo JHtml::_('tabs.panel', $title, 'catalog'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('accessory'); ?></td>
        </tr>
    </table>
	<?php
	$acc = $redtemplate->getInstallSectionTemplate("accessory", $setflag = true);
	if ($acc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $acc; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Wrapper Template Start
if ($this->detail->template_section == "wrapper_template")
{
	echo JHtml::_('tabs.panel', $title, 'wrapper'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('wrapper'); ?></td>
        </tr>
    </table>
	<?php
	$wrapper = $redtemplate->getInstallSectionTemplate("wrapper", $setflag = true);
	if ($wrapper != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $wrapper; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Wishlist Template Start
if ($this->detail->template_section == "wishlist_template")
{
	echo JHtml::_('tabs.panel', $title, 'wishlist'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('wishlist'); ?></td>
        </tr>
    </table>
	<?php
	$wishlist = $redtemplate->getInstallSectionTemplate("wishlist_list", $setflag = true);
	if ($wishlist != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $wishlist; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Wishlist Mail Template Start
if ($this->detail->template_section == "wishlist_mail_template")
{
	echo JHtml::_('tabs.panel', $title, 'wishlist'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('wishlist_mail'); ?></td>
        </tr>
    </table>
	<?php
	$wishlist_mail = $redtemplate->getInstallSectionTemplate("wishlist_mail", $setflag = true);
	if ($wishlist_mail != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $wishlist_mail; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Shipping PDF Template Start
if ($this->detail->template_section == "shipping_pdf")
{
	echo JHtml::_('tabs.panel', $title, 'shipping'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('shipping_pdf'); ?></td>
        </tr>
    </table>
	<?php
	$ship_pdf = $redtemplate->getInstallSectionTemplate("shipping_pdf", $setflag = true);
	if ($ship_pdf != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $ship_pdf; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Ask Question Template Start
if ($this->detail->template_section == "ask_question_template")
{
	echo JHtml::_('tabs.panel', $title, 'askquestion'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('ask_question'); ?></td>
        </tr>
    </table>
	<?php
	$ask_ques = $redtemplate->getInstallSectionTemplate("ask_question", $setflag = true);
	if ($ask_ques != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $ask_ques; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Ajax Cart Box Template Start
if ($this->detail->template_section == "ajax_cart_box")
{
	echo JHtml::_('tabs.panel', $title, 'wrapper'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('ajax_cart_box'); ?></td>
        </tr>
    </table>
	<?php
	$ajax_cart = $redtemplate->getInstallSectionTemplate("ajax_cart_box", $setflag = true);
	if ($ajax_cart != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $ajax_cart; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Ajax Cart Box Detail Template Start
if ($this->detail->template_section == "ajax_cart_detail_box")
{
	echo JHtml::_('tabs.panel', $title, 'wrapper'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><b><?php echo JText::_('COM_REDSHOP_AJAX_CART_BOX_DETAIL_TEMPLATE_HINT'); ?></b></td>
        </tr>
        <tr>
            <td>
				<?php $availableaddtocart = $model->availableaddtocart('add_to_cart');
				if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_NO_ADD_TO_CART_AVAILABLE");

				for ($i = 0, $in = count($availableaddtocart); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{form_addtocart:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT') . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('ajax_product'); ?></td>
        </tr>
        <tr>
            <td>
				<?php
				$tags_front = RedshopHelperExtrafields::getSectionFieldList(6, 1);
				$tags_admin = RedshopHelperExtrafields::getSectionFieldList(6, 0);
				$tags       = array_merge((array) $tags_admin, (array) $tags_front);
				if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");

				for ($i = 0, $in = count($tags); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->desc . '</div>';
				} ?>
            </td>
        </tr>
    </table>
	<?php
	$ajax_cart_box_detail = $redtemplate->getInstallSectionTemplate("ajax_cart_detail_box", $setflag = true);
	if ($ajax_cart_box_detail != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $ajax_cart_box_detail; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Redproductfinder Template Start
if ($this->detail->template_section == "redproductfinder")
{
	echo JHtml::_('tabs.panel', $title, 'redPRODUCTFINDER'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('redproductfinder'); ?></td>
        </tr>
        <tr>
            <td>
				<?php $availableaddtocart = $model->availableaddtocart('add_to_cart');
				if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_NO_ADD_TO_CART_AVAILABLE");

				for ($i = 0, $in = count($availableaddtocart); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{form_addtocart:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT') . '</div>';
				} ?>
            </td>
        </tr>
    </table>
	<?php
	$redprdfinder = $redtemplate->getInstallSectionTemplate("redproductfinder", $setflag = true);
	if ($redprdfinder != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $redprdfinder; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Account Template Start
if ($this->detail->template_section == "account_template")
{
	echo JHtml::_('tabs.panel', $title, 'account_detail'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('account'); ?></td>
        </tr>
    </table>
	<?php
	$acc = $redtemplate->getInstallSectionTemplate("my_account_template", $setflag = true);
	if ($acc != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $acc; ?>
                </td>
            </tr>
        </table>
		<?php

	}

}

// Compare Product Template Start
if ($this->detail->template_section == "compare_product")
{
	echo JHtml::_('tabs.panel', $title, 'compare_product'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('compare_product'); ?></td>
        </tr>
    </table>
	<?php
	$cmp_prd = $redtemplate->getInstallSectionTemplate("compare_product", $setflag = true);
	if ($cmp_prd != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $cmp_prd; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Payment Method Template Start
if ($this->detail->template_section == "redshop_payment")
{
	echo JHtml::_('tabs.panel', $title, 'redshop_payment'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('redshop_payment'); ?></td>
        </tr>
    </table>
	<?php
	$payment = $redtemplate->getInstallSectionTemplate("payment_method", $setflag = true);
	if ($payment != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $payment; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Shipping Method Template Start
if ($this->detail->template_section == "redshop_shipping")
{
	echo JHtml::_('tabs.panel', $title, 'redshop_shipping'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('redshop_shipping'); ?></td>
        </tr>
    </table>
	<?php
	$shipping = $redtemplate->getInstallSectionTemplate("shipping_method", $setflag = true);
	if ($shipping != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $shipping; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Shipping Box Template Start
if ($this->detail->template_section == "shippingbox")
{
	echo JHtml::_('tabs.panel', $title, 'shippingbox'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('shipping_box'); ?></td>
        </tr>
    </table>
	<?php
	$ship_box = $redtemplate->getInstallSectionTemplate("shipping_box", $setflag = true);
	if ($ship_box != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $ship_box; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Onestep Checkout Template Start
if ($this->detail->template_section == "onestep_checkout")
{
	echo JHtml::_('tabs.panel', $title, '1stepcheckout'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('onestep_checkout'); ?></td>
        </tr>
        <tr>
            <td><?php echo htmlentities($newbillingtag); ?></td>
        </tr>
        <tr>
            <td>
				<?php $availablecheckout = $model->availableaddtocart('checkout');
				if (count($availablecheckout) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");
				for ($i = 0, $in = count($availablecheckout); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{checkout_template:' . $availablecheckout[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_CHECKOUT_TEMPLATE') . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php $availableshippingbox = $model->availableaddtocart('shippingbox');
				if (count($availableshippingbox) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");
				for ($i = 0, $in = count($availableshippingbox); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{shippingbox_template:' . $availableshippingbox[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_SHIPPING_BOX_TEMPLATE') . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php $availableshipping = $model->availableaddtocart('redshop_shipping');
				if (count($availableshipping) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");
				for ($i = 0, $in = count($availableshipping); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{shipping_template:' . $availableshipping[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_SHIPPING_METHOD_TEMPLATE') . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php $availablepayment = $model->availableaddtocart('redshop_payment');
				if (count($availablepayment) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");
				for ($i = 0, $in = count($availablepayment); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{payment_template:' . $availablepayment[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_PAYMENT_METHOD_TEMPLATE') . '</div>';
				} ?>
            </td>
        </tr>
    </table>
	<?php
	$one_step = $redtemplate->getInstallSectionTemplate("onestep_checkout", $setflag = true);
	if ($one_step != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $one_step; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Change Cart Attribute Temlate Start
if ($this->detail->template_section == "change_cart_attribute")
{
	echo JHtml::_('tabs.panel', $title, 'cartattribute'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('change_cart_attribute'); ?></td>
        </tr>
        <tr>
            <td>
				<?php $availableaddtocart = $model->availableaddtocart('attribute_template ');
				if (count($availableaddtocart) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");
				for ($i = 0, $in = count($availableaddtocart); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{attribute_template:' . $availableaddtocart[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ATTRIBUTE_TEMPLATE') . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php $availableAttcartTemp = $model->availableaddtocart('attributewithcart_template');
				if (count($availableAttcartTemp) == 0) echo JText::_("COM_REDSHOP_ATTRIBUTE_TAGS_AVAILABLE");
				for ($i = 0, $in = count($availableAttcartTemp); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{attributewithcart_template:' . $availableAttcartTemp[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_ATTRIBUTE_WITH_CART_TEMPLATE') . '</div>';
				} ?>
            </td>
        </tr>
    </table>
	<?php
	$change_cart_attrib = $redtemplate->getInstallSectionTemplate("change_cart_attribute_template", $setflag = true);
	if ($change_cart_attrib != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $change_cart_attrib; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Product Content Template Start
if ($this->detail->template_section == "product_content_template")
{
	echo JHtml::_('tabs.panel', $title, 'product_content'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('product_content'); ?></td>
        </tr>
    </table>
	<?php
	$product_content = $redtemplate->getInstallSectionTemplate("product_content", $setflag = true);
	if ($product_content != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $product_content; ?>
                </td>
            </tr>
        </table>
		<?php

	}
}

// Quotation Cart Template Start
if ($this->detail->template_section == "quotation_cart")
{
	echo JHtml::_('tabs.panel', $title, 'product_content'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('quotation_cart'); ?></td>
        </tr>
    </table>
	<?php
	$quotation_cart = $redtemplate->getInstallSectionTemplate("quotation_cart_template", $setflag = true);
	if ($quotation_cart != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $quotation_cart; ?>
                </td>
            </tr>
        </table>
		<?php
	}
}

// Billing Template Start
if ($this->detail->template_section == "billing_template")
{
	echo JHtml::_('tabs.panel', $title, 'billing_template'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('billing') ?></td>
        </tr>
        <tr>
            <td>
				<?php $available = $model->availableaddtocart('private_billing_template ');
				if (count($available) == 0) echo JTEXT::_("COM_REDSHOP_PRIVATE_BILLING_TEMPLATE");
				for ($i = 0, $in = count($available); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{private_billing_template:' . $available[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_PRIVATE_BILLING_TEMPLATE') . '</div>';
				} ?>
            </td>
        </tr>
        <tr>
            <td>
				<?php $available = $model->availableaddtocart('company_billing_template ');
				if (count($available) == 0) echo JTEXT::_("COM_REDSHOP_COMPANY_BILLING_TEMPLATE");
				for ($i = 0, $in = count($available); $i < $in; $i++)
				{
					echo '<div style="margin-left:10px;">{company_billing_template:' . $available[$i]->template_name . '} -- ' . JText::_('COM_REDSHOP_COMPANY_BILLING_TEMPLATE') . '</div>';
				} ?>
            </td>
        </tr>
    </table>
	<?php
	$billing_template = $redtemplate->getInstallSectionTemplate("billing_template", $setflag = true);
	if ($billing_template != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $billing_template; ?>
                </td>
            </tr>
        </table>
		<?php
	}
}

// Private Billing Template Start
if ($this->detail->template_section == "private_billing_template")
{
	echo JHtml::_('tabs.panel', $title, 'private_billing_template'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('private_billing'); ?></td>
        </tr>
    </table>
	<?php
	$billing_template = $redtemplate->getInstallSectionTemplate("private_billing_template", $setflag = true);
	if ($billing_template != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td><?php echo $billing_template; ?></td>
            </tr>
        </table>
		<?php
	}
}

// Billing Template Start
if ($this->detail->template_section == "company_billing_template")
{
	$tags_front = RedshopHelperExtrafields::getSectionFieldList(8, 1);
	$tags_admin = RedshopHelperExtrafields::getSectionFieldList(8, 0);
	$tags       = array_merge((array) $tags_admin, (array) $tags_front);
	echo JHtml::_('tabs.panel', $title, 'company_billing_template'); ?>
	<?php if (!empty($tags)): ?>
    <tr>
        <td>
            <h3><?php echo JText::_("COM_REDSHOP_FIELDS") ?></h3>
			<?php foreach ($tags as $tag): ?>
                <div style="margin-left:10px;">{<?php echo $tag->name ?>} -- <?php echo $tag->title ?></div>
			<?php endforeach; ?>
        </td>
    </tr>
<?php endif; ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('company_billing'); ?></td>
        </tr>
    </table>
	<?php
	$billing_template = $redtemplate->getInstallSectionTemplate("company_billing_template", $setflag = true);
	if ($billing_template != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td><?php echo $billing_template; ?></td>
            </tr>
        </table>
		<?php
	}
}

// Shipping Template Start
if ($this->detail->template_section == "shipping_template")
{
	echo JHtml::_('tabs.panel', $title, 'shipping_template'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('shipping') ?></td>
        </tr>
    </table>
	<?php
	$shipping_template = $redtemplate->getInstallSectionTemplate("shipping_template", $setflag = true);
	if ($shipping_template != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $shipping_template; ?>
                </td>
            </tr>
        </table>
		<?php
	}
}

// Stock Note Template Start
if ($this->detail->template_section == "stock_note")
{
	echo JHtml::_('tabs.panel', $title, 'stock_note'); ?>
    <table class="adminlist table table-striped">
        <tr>
            <td><?php echo Redtemplate::getTemplateValues('stock_note'); ?></td>
        </tr>
        <tr>
            <td><?php echo htmlentities($newbillingtag) . "<br><br>" . htmlentities($newshippingtag); ?></td>
        </tr>
    </table>
	<?php
	$stock_note = $redtemplate->getInstallSectionTemplate("stock_note", $setflag = true);
	if ($stock_note != "")
	{
		echo JHtml::_('tabs.panel', $default_template, 'events'); ?>
        <table class="adminlist table table-striped">
            <tr>
                <td>
					<?php echo $stock_note; ?>
                </td>
            </tr>
        </table>
		<?php
	}
}

echo JHtml::_('tabs.end'); ?>
