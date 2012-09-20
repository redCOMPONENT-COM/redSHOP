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

//JHTML::_ ( 'behavior.tooltip' );
JHTMLBehavior::modal ();

$option = JRequest::getVar ( 'option' );
jimport ( 'joomla.html.pane' );

$uri = & JURI::getInstance ();
$url = $uri->root ();
?>
<table class="admintable" width="100%">
<tr><td class="config_param"><?php echo JText::_( 'MODULES_AND_FEATURES' ); ?></td></tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'NEWSLETTER_ENABLE_TEXT' ); ?>::<?php echo JText::_( 'TOOLTIP_NEWSLETTER_ENABLE' ); ?>">
		<label
			for="newsletter_enable"><?php  echo JText::_ ( 'NEWSLETTER_ENABLE_TEXT' );?>
		</label>
		</span>
		</td>
		<td><?php echo $this->lists ['newsletter_enable'];?></td>
	</tr>
	<tr><td colspan="2"><hr /></td></tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'MY_WISHLIST_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_MY_WISHLIST' ); ?>">
		<label for="name">
		<?php echo JText::_ ( 'MY_WISHLIST_LBL' );?>
		</label>
		</td>
		<td><?php echo $this->lists ['my_wishlist'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'WISHLIST_LOGIN_REQUIRED_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_WISHLIST_LOGIN_REQUIRED' ); ?>">
		<label for="invoice_mail_send_option"><?php echo JText::_ ( 'WISHLIST_LOGIN_REQUIRED_LBL' );?></label></span>
		</td>
		<td><?php echo $this->lists ['wishlist_login_required'];?></td>
	</tr>
	<tr><td colspan="2"><hr /></td></tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'MY_TAGS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_MY_TAGS' ); ?>">
		<label for="name"><?php echo JText::_ ( 'MY_TAGS_LBL' );?></label></span>
		</td>
		<td><?php echo $this->lists ['my_tags'];?></td>
	</tr>
	<tr><td colspan="2"><hr /></td></tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'COMPARE_PRODUCTS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_COMPARE_PRODUCTS' ); ?>">
		<label for="name">
		<?php echo JText::_ ( 'COMPARE_PRODUCTS_LBL' );?>
       </label>
       </span>
       </td>
		<td><?php echo $this->lists ['compare_products'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'COUPON_INFO_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_COUPON_INFO_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'COUPON_INFO_LBL' );?>
        </label>
        </span>
        </td>
		<td><?php echo $this->lists ['couponinfo'];?></td>
	</tr>
	<tr><td colspan="2"><hr /></td></tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'DISCOUNT_ENABLE_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_DISCOUNT_ENABLE_LBL' ); ?>">
		<label for="name"><?php echo JText::_ ( 'DISCOUNT_ENABLE_LBL' );?></label></span>
		</td>
		<td><?php echo $this->lists ['discount_enable'];?></td>
	</tr>
	<tr><td colspan="2"><hr /></td></tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'USE_CONTAINER_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_USE_CONTAINER_LBL' ); ?>">
			<label for="container"><?php echo JText::_ ( 'USE_CONTAINER_LBL' );?></label></span>
		</td>
		<td><?php echo $this->lists ['use_container'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'USE_STOCKROOM_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_USE_STOCKROOM_LBL' ); ?>">
			<label for="container"><?php echo JText::_ ( 'USE_STOCKROOM_LBL' );?></label></span>
		</td>
		<td><?php echo $this->lists ['use_stockroom']; ?></td>
	</tr>
</table>