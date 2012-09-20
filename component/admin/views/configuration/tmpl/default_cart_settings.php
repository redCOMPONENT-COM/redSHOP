<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
$uri = & JURI::getInstance ();
$url = $uri->root ();
$addtocart_path="/components/com_redshop/assets/images/";
?>
<table width="100%" cellpadding="0" cellspacing="0">
<tr valign="top">
	<td width="50%">
		<fieldset class="adminform">
		<table class="admintable">
		<tr><td class="config_param"><?php echo JText::_( 'ADD_TO_CART_SETTINGS' ); ?></td></tr>
			<tr>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_INDIVIDUAL_ADD_TO_CART_ENABLE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_INDIVIDUAL_ADD_TO_CART_ENABLE' ); ?>">
					<label><?php echo JText::_ ( 'INDIVIDUAL_ADD_TO_CART_ENABLE_LBL' );?></label></span>
				</td>
				<td><?php echo $this->lists ['individual_add_to_cart_enable'];?></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ALLOW_PRE_ORDER_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_ALLOW_PRE_ORDER' ); ?>">
					<label for="allow_pre_order"><?php echo JText::_ ( 'ALLOW_PRE_ORDER_LBL' );?></label></span>
				</td>
				<td><?php echo $this->lists ['allow_pre_order'];?></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_QUOTATION_MODE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_QUOTATION_MODE' ); ?>">
					<?php echo JText::_ ( 'QUOTATION_MODE_LBL' );?></span>
				</td>
				<td><?php echo $this->lists ['quotation_mode'];?></td>
			</tr>
			<tr id="quotationprice" style="display: none;">
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_SHOW_QUOTATION_PRICE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_SHOW_QUOTATION_PRICE' ); ?>">
					<?php echo JText::_ ( 'SHOW_QUOTATION_PRICE_LBL' );?></span>
				</td>
				<td><?php echo $this->lists ['show_quotation_price'];?></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_AJAX_CART_BOX_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_AJAX_CART_BOX' ); ?>">
				<label for="ajax_cart_box"><?php echo JText::_ ( 'AJAX_CART_BOX_LBL' );?></label></span>
				</td>
				<td><?php echo $this->lists ['ajax_cart_box'];?></td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_AJAX_CARTDISPLAY_TIME_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_AJAX_CARTDISPLAY_TIME' ); ?>">
					<?php echo JText::_ ( 'AJAX_CART_DISPLAY_TIME_LBL' );?></span>
				</td>
				<td>
					<input type="text" name="ajax_cart_display_time" id="ajax_cart_display_time" value="<?php echo AJAX_CART_DISPLAY_TIME;?>">
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_CART_TIMEOUT_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CART_TIMEOUT' ); ?>">
				<label for="cart_timeout"><?php echo JText::_ ( 'CART_TIMEOUT_LBL' );?></label></span>
				</td>
				<td>
				<input type="text" name="cart_timeout" id="cart_timeout" value="<?php echo CART_TIMEOUT;?>">
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'DEFAULT_CART_CHECKOUT_ITEMID_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_DEFAULT_CART_CHECKOUT_ITEMID' ); ?>">
					<label for="name"><?php echo JText::_ ( 'DEFAULT_CART_CHECKOUT_ITEMID_LBL' );?></label></span>
				</td>
				<td>
					<input type="text" name="default_cart_checkout_itemid" id="default_cart_checkout_itemid" value="<?php if (DEFAULT_CART_CHECKOUT_ITEMID) echo DEFAULT_CART_CHECKOUT_ITEMID; else echo 1;?>">
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'ALLOW_PRE_ORDER_MESSAGE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_ALLOW_PRE_ORDER_MESSAGE' ); ?>">
					<label for="allow_pre_order_message"><?php echo JText::_ ( 'ALLOW_PRE_ORDER_MESSAGE_LBL' );?></label></span>
				</td>
				<td>
					<input type="text" name="allow_pre_order_message" id="allow_pre_order_message" value="<?php echo ALLOW_PRE_ORDER_MESSAGE;?>">
				</td>
			</tr>
			<tr>
				<td width="100" align="right" class="key">
					<span class="addtocartbutton hasTip" title="<?php echo JText::_( 'ADDTOCART_BUTTON_BEHAVE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_ADDTOCART_BUTTON_BEHAVE' ); ?>">
					<?php echo JText::_ ( 'ADDTOCART_BUTTON_BEHAVE_LBL' );?></span>
				</td>
				<td><?php echo $this->lists ['addtocart_behaviour'];?></td>
			</tr>

		</table>
		</fieldset>

		<fieldset class="adminform">
		<table class="admintable" width="100%">
		<tr><td class="config_param"><?php echo JText::_( 'CART_SETTINGS' ); ?></td></tr>
				<tr>
					<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'SHOW_SHIPPING_IN_CART_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_SHOW_SHIPPING_IN_CART_LBL' ); ?>">
					<label for="show_shipping_in_cart"><?php echo JText::_ ( 'SHOW_SHIPPING_IN_CART_LBL' );?></label></span>
					</td>
					<td><?php echo $this->lists ['show_shipping_in_cart'];?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART_LBL' ); ?>">
						<label for="name"><?php echo JText::_ ( 'WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART_LBL' );?></label></span>
					</td>
					<td><?php echo $this->lists ['wanttoshowattributeimage']; ?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_ACCESSORY_AS_PRODUCT_IN_CART_ENABLE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_ACCESSORY_AS_PRODUCT_IN_CART_ENABLE_LBL' ); ?>">
						<label><?php echo JText::_ ( 'ACCESSORY_AS_PRODUCT_IN_CART_ENABLE_LBL' );?></label></span>
					</td>
					<td><?php echo $this->lists ['accessory_as_product_in_cart_enable'];?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'QUANTITY_TEXT_DISPLAY_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_QUANTITY_TEXT_DISPLAY_LBL' ); ?>">
						<label for="name"><?php echo JText::_ ( 'QUANTITY_TEXT_DISPLAY_LBL' );?></label></span>
					</td>
					<td><?php echo $this->lists ['quantity_text_display']; ?></td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'DEFAULT_QUANTITY_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_DEFAULT_QUANTITY_LBL' ); ?>">
						<?php echo JText::_ ( 'DEFAULT_QUANTITY_LBL' );?></span>
					</td>
					<td>
						<input type="text" name="default_quantity" id="default_quantity" value="<?php echo DEFAULT_QUANTITY;?>">
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_( 'DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL' ); ?>">
						<?php echo JText::_ ( 'DEFAULT_QUANTITY_SELECTBOX_VALUE_LBL' );?></span>
					</td>
					<td>
						<input type="text" name="default_quantity_selectbox_value" id="default_quantity_selectbox_value" value="<?php echo DEFAULT_QUANTITY_SELECTBOX_VALUE;?>">
					</td>
				</tr>
				<tr><td colspan="2"><hr /></td></tr>
				<tr>
					<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'CONTINUE_REDIRECT_LINK_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_CONTINUE_REDIRECT_LINK' ); ?>">
					<label for="continue_redirect_link"><?php echo JText::_ ( 'CONTINUE_REDIRECT_LINK_LBL' );?></label></span>
					</td>
					<td>
					<input type="text" name="continue_redirect_link" id="continue_redirect_link" size="50" value="<?php echo CONTINUE_REDIRECT_LINK;?>">
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'MINIMUM_ORDER_TOTAL_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_MINIMUM_ORDER_TOTAL' ); ?>">
					<label for="minimum_order_total"><?php echo JText::_ ( 'MINIMUM_ORDER_TOTAL_LBL' );?></label></span>
					</td>
					<td>
					<input type="text" name="minimum_order_total" id="minimum_order_total" size="20" value="<?php echo MINIMUM_ORDER_TOTAL;?>">
					</td>
				</tr>
		</table>
		</fieldset>
	</td>
</tr>
</table>
<script language="javascript" type="text/javascript">
	function quote_price(val)
	{

		if(val=="0")
		{
			document.getElementById('quotationprice').style.display="none";
		} else {
			document.getElementById('quotationprice').style.display="";
		}
	}
	quote_price(<?php echo DEFAULT_QUOTATION_MODE_PRE?>);
</script>