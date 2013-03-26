<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

//JHTML::_ ( 'behavior.tooltip' );
JHTMLBehavior::modal();

$option = JRequest::getVar('option');
jimport('joomla.html.pane');

$uri = JURI::getInstance();
$url = $uri->root();
?>
<table class="admintable" width="100%">
	<tr>
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_MODULES_AND_FEATURES'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_NEWSLETTER_ENABLE_TEXT'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_NEWSLETTER_ENABLE'); ?>">
		<label
			for="newsletter_enable"><?php  echo JText::_('COM_REDSHOP_NEWSLETTER_ENABLE_TEXT');?>
		</label>
		</span>
		</td>
		<td><?php echo $this->lists ['newsletter_enable'];?></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_MY_WISHLIST_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MY_WISHLIST'); ?>">
		<label for="name">
			<?php echo JText::_('COM_REDSHOP_MY_WISHLIST_LBL');?>
		</label>
		</td>
		<td><?php echo $this->lists ['my_wishlist'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_WISHLIST_LOGIN_REQUIRED_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_WISHLIST_LOGIN_REQUIRED'); ?>">
		<label for="invoice_mail_send_option"><?php echo JText::_('COM_REDSHOP_WISHLIST_LOGIN_REQUIRED_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['wishlist_login_required'];?></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_MY_TAGS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_MY_TAGS'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_MY_TAGS_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['my_tags'];?></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_COMPARE_PRODUCTS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COMPARE_PRODUCTS'); ?>">
		<label for="name">
			<?php echo JText::_('COM_REDSHOP_COMPARE_PRODUCTS_LBL');?>
		</label>
       </span>
		</td>
		<td><?php echo $this->lists ['compare_products'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_COUPON_INFO_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_COUPON_INFO_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_COUPON_INFO_LBL');?>
		</label>
        </span>
		</td>
		<td><?php echo $this->lists ['couponinfo'];?></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_DISCOUNT_ENABLE_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_DISCOUNT_ENABLE_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_DISCOUNT_ENABLE_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['discount_enable'];?></td>
	</tr>
	<tr>
		<td colspan="2">
			<hr/>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_USE_CONTAINER_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_CONTAINER_LBL'); ?>">
			<label for="container"><?php echo JText::_('COM_REDSHOP_USE_CONTAINER_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['use_container'];?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_USE_STOCKROOM_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_USE_STOCKROOM_LBL'); ?>">
			<label for="container"><?php echo JText::_('COM_REDSHOP_USE_STOCKROOM_LBL');?></label></span>
		</td>
		<td><?php echo $this->lists ['use_stockroom']; ?></td>
	</tr>
</table>
