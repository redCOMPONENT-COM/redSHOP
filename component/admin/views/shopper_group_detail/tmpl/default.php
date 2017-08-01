<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.modal', 'a.joom-box');
JHtml::_('behavior.tooltip');

$editor = JFactory::getEditor();
$uri    = JURI::getInstance();
$url    = $uri->root();
?>
<script language="javascript" type="text/javascript">
    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;
        if (pressbutton == 'cancel') {
            submitform(pressbutton);
            return;
        }

        if (form.shopper_group_name.value == "") {
            alert("<?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_ITEM_MUST_HAVE_A_NAME', true); ?>");
        } else {
            submitform(pressbutton);
        }
    }
</script>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
    <div class="col50">
        <fieldset class="adminform">
            <legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

            <table class="admintable table">
                <tr>
                    <td width="100" align="right" class="key"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_NAME'); ?>
                        :
                    </td>
                    <td><input class="text_area" type="text" name="shopper_group_name" id="shopper_group_name" size="32"
                               maxlength="250" value="<?php echo $this->detail->shopper_group_name; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_NAME'), JText::_('COM_REDSHOP_SHOPPER_GROUP_NAME'), 'tooltip.png', '', '', false, 'hasTip'); ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_TYPE'); ?>
                        :
                    </td>
                    <td><?php echo $this->lists['groups']; ?></td>
                </tr>
                <tr>
                    <td valign="top" align="right"
                        class="key"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_CUSTOMER_TYPE'); ?>:
                    </td>
                    <td><?php echo $this->lists['customertype']; ?></td>
                </tr>
                <tr>
                    <td valign="top" align="right"
                        class="key"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_PORTAL'); ?>:
                    </td>
                    <td><?php echo $this->lists['portal']; ?></td>
                </tr>
                <tr>
                    <td valign="top" align="right"
                        class="key"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_CATEGORY'); ?>:
                    </td>
                    <td><?php echo $this->lists['categories']; ?><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_CATEGORY'), JText::_('COM_REDSHOP_SHOPPER_GROUP_CATEGORY'), 'tooltip.png', '', '', false, 'hasTip'); ?>
                    </td>
                </tr>

                <tr>
                    <td valign="top" align="right"
                        class="key"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_MANUFACTURE'); ?>:
                    </td>
                    <td><?php echo $this->lists['manufacturers']; ?><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_MANUFACTURE'), JText::_('COM_REDSHOP_SHOPPER_GROUP_MANUFACTURE'), 'tooltip.png', '', '', false, 'hasTip'); ?>
                    </td>
                </tr>

                <tr>
                    <td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_URL'); ?>
                        :
                    </td>
                    <td>
                        <a href="<?php echo JURI::root() . "index.php?option=com_redshop&view=login&layout=portal&protalid=" . $this->detail->shopper_group_id . "Itemid=" . Redshop::getConfig()->get('PORTAL_LOGIN_ITEMID'); ?>"
                           target="_blank"><?php echo JURI::root() . "index.php?option=com_redshop&view=login&layout=portal&protalid=" . $this->detail->shopper_group_id . "Itemid=" . Redshop::getConfig()->get('PORTAL_LOGIN_ITEMID'); ?></a>
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right"
                        class="key"><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_CART_ITEMID_LBL'); ?>:
                    </td>
                    <td><input class="text_area" type="text" name="shopper_group_cart_itemid"
                               id="shopper_group_cart_itemid" size="32" maxlength="250"
                               value="<?php echo $this->detail->shopper_group_cart_itemid; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SHOPPER_GROUP_CART_ITEMID'), JText::_('COM_REDSHOP_SHOPPER_GROUP_CART_ITEMID_LBL'), 'tooltip.png', '', '', false, 'hasTip'); ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right"
                        class="key"><?php echo JText::_('COM_REDSHOP_USE_DEFAULT_SHIPPING'); ?>:
                    </td>
                    <td><?php echo $this->lists['default_shipping']; ?></td>
                </tr>
                <tr>
                    <td valign="top" align="right"
                        class="key"><?php echo JText::_('COM_REDSHOP_CHOOSE_DEFAULT_SHIPPING_RATE'); ?>:
                    </td>
                    <td>
                        <input class="text_area" type="text" name="default_shipping_rate" id="default_shipping_rate"
                               size="32" maxlength="250" value="<?php echo $this->detail->default_shipping_rate; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_CHOOSE_DEFAULT_SHIPPING_RATE'), JText::_('COM_REDSHOP_CHOOSE_DEFAULT_SHIPPING_RATE'), 'tooltip.png', '', '', false, 'hasTip'); ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right"
                        class="key"><?php echo JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_CART_CHECKOUT_ITEMID'); ?>:
                    </td>
                    <td><input class="text_area" type="text" name="shopper_group_cart_checkout_itemid"
                               id="shopper_group_cart_checkout_itemid" size="32" maxlength="250"
                               value="<?php echo $this->detail->shopper_group_cart_checkout_itemid; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_DEFAULT_SHOPPER_GROUP_CART_CHECKOUT_ITEMID'), JText::_('COM_REDSHOP_DEFAULT_SHOPPER_GROUP_CART_CHECKOUT_ITEMID'), 'tooltip.png', '', '', false, 'hasTip'); ?>
                    </td>
                </tr>
                <tr style="display: none;">
                    <td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USE_VAT_GROUP_LBL'); ?>
                        :
                    </td>
                    <td><?php echo $this->lists['tax_group_id'];
						echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_USE_VAT_GROUP'), JText::_('COM_REDSHOP_USE_VAT_GROUP_LBL'), 'tooltip.png', '', '', false, 'hasTip'); ?></td>
                </tr>
                <tr style="display: none;">
                    <td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_ADD_VAT'); ?>:</td>
                    <td><?php echo $this->lists['apply_vat'];
						echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_ADD_VAT'), JText::_('COM_REDSHOP_ADD_VAT'), 'tooltip.png', '', '', false, 'hasTip'); ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right"
                        class="key"><?php echo JText::_('COM_REDSHOP_SHOW_PRICE_WITHOUT_VAT'); ?>:
                    </td>
                    <td><?php echo $this->lists['show_price_without_vat'];
						echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_SHOW_PRICE_WITHOUT_VAT'), JText::_('COM_REDSHOP_SHOW_PRICE_WITH_OR_WITHOUT_VAT'), 'tooltip.png', '', '', false, 'hasTip'); ?>
                    </td>
                </tr>

                <tr>
                    <td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_SHOW_PRICE_LBL'); ?>:
                    </td>
                    <td><?php echo $this->lists['show_price']; ?></td>
                </tr>

                <tr>
                    <td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_USE_AS_CATALOG_LBL'); ?>
                        :
                    </td>
                    <td><?php echo $this->lists['use_as_catalog']; ?></td>
                </tr>

                <!-- <tr><td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_IS_LOGGED_IN'); ?>:</td>
		<td><?php echo $this->lists['is_logged_in']; ?></td></tr>-->

                <tr style="display: none;">
                    <td valign="top" align="right"
                        class="key"><?php echo JText::_('COM_REDSHOP_PRODUCT_PRICE_VAT_LBL'); ?>:
                    </td>
                    <td><?php echo $this->lists['apply_product_price_vat'];
						echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PRICE_VAT_LBL'), JText::_('COM_REDSHOP_PRODUCT_PRICE_VAT_LBL'), 'tooltip.png', '', '', false, 'hasTip'); ?>
                    </td>
                </tr>
                <!--	<tr>
		<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT'); ?>:</td>
		<td><?php echo $this->lists['tax_exempt'];
				echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_PRICE_VAT_LBL'), JText::_('COM_REDSHOP_PRODUCT_PRICE_VAT_LBL'), 'tooltip.png', '', '', false, 'hasTip'); ?>
		</td></tr>
	<tr>
		<td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_TAX_EXEMPT_SHIPPING'); ?>:</td>
		<td><?php echo $this->lists['tax_exempt_on_shipping'];
				echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_TAX_EXEMPT_SHIPPING'), JText::_('COM_REDSHOP_TAX_EXEMPT_SHIPPING'), 'tooltip.png', '', '', false, 'hasTip'); ?>
		</td>
	</tr>-->
                <tr>
                    <td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_QUOTATION_MODE_LBL'); ?>
                        :
                    </td>
                    <td><?php echo $this->lists['shopper_group_quotation_mode'];
						echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_QUOTATION_MODE_LBL'), JText::_('COM_REDSHOP_QUOTATION_MODE_LBL'), 'tooltip.png', '', '', false, 'hasTip'); ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right" class="key"><?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:</td>
                    <td><?php echo $this->lists['published']; ?></td>
                </tr>
            </table>
        </fieldset>
        <div class="col50">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_LOGO'); ?></legend>
                <table class="admintable table">
                    <tr>
                        <td><input type="file" name="shopper_group_logo" id="shopper_group_logo" size="77"/></td>
                        <td>
							<?php
							$ilink = JRoute::_('index.php?tmpl=component&option=com_redshop&view=product_detail&task=media_bank&e_name=text');
							?>
                            <div class="button2-left">
                                <div class="image"><a class="joom-box" title="Image" href="<?php echo $ilink; ?>"
                                                      rel="{handler: 'iframe', size: {x: 570, y: 400}}">Image</a></div>
                            </div>
                            <input type="hidden" name="shopper_group_logo_tmp" id="shopper_group_logo_tmp"/>
                            <input type="hidden" name="shopper_group_logo" id="shopper_group_logo"
                                   value="<?php echo $this->detail->shopper_group_logo; ?>"/></td>
                    </tr>
                </table>
				<?php if (null != $this->detail->shopper_group_logo) : ?>
                    <div>
						<?php
						$image_path      = REDSHOP_FRONT_IMAGES_ABSPATH . 'shopperlogo/' . $this->detail->shopper_group_logo;
						$imagethumb_path = RedShopHelperImages::getImagePath(
							$this->detail->shopper_group_logo,
							'',
							'thumb',
							'shopperlogo',
							Redshop::getConfig()->get('THUMB_WIDTH'),
							Redshop::getConfig()->get('THUMB_HEIGHT'),
							Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
						);
						?>
                        <a
                                href="<?php echo $image_path; ?>"
                                id="image_display_href" class="joom-box"
                                rel="{handler: 'image', size: {x: 570, y: 400}}">
                            <img
                                    src="<?php echo $imagethumb_path; ?>"
                                    id="image_display" border="0"
                                    width="200"/>
                        </a>
                    </div>
				<?php endif; ?>
            </fieldset>
        </div>
        <div class="col50">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_INTROTEXT'); ?></legend>
                <table class="admintable table">
                    <tr>
                        <td>
							<?php
							echo $editor->display(
								"shopper_group_introtext",
								$this->detail->shopper_group_introtext,
								'400',
								'600',
								'100',
								'40'
							);
							?>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </div>
        <div class="col50">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP_DESCRIPTION'); ?></legend>
                <table class="admintable table">
                    <tr>
                        <td><?php echo $editor->display("shopper_group_desc", $this->detail->shopper_group_desc, '$widthPx', '$heightPx', '100', '40'); ?></td>
                    </tr>
                </table>
            </fieldset>
        </div>
    </div>
    <div class="clr"></div>
    <input type="hidden" name="cid[]" value="<?php echo $this->detail->shopper_group_id; ?>"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="view" value="shopper_group_detail"/>
</form>
<script type="text/javascript">
    function jimage_insert(main_path) {
        var path_url = "<?php echo $url;?>";
        if (main_path) {
            document.getElementById("image_display").style.display = "block";
            document.getElementById("shopper_group_logo_tmp").value = main_path;
            document.getElementById("image_display").src = path_url + main_path;
            document.getElementById("image_display_href").href = path_url + main_path;

        }
        else {
            document.getElementById("shopper_group_logo_tmp").value = "";
            document.getElementById("image_display").src = "";
        }

    }
</script>
