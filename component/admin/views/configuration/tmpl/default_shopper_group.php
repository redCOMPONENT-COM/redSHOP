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
$shopperlogo_path="components/com_redshop/assets/images/shopperlogo";
?>
<table class="admintable">
<tr><td class="config_param" colspan="2"><?php echo JText::_( 'SHOPPER_GROUP_TAB' ); ?></td></tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_PORTAL_SHOP' ); ?>::<?php echo JText::_( 'TOOLTIP_PORTAL_SHOP_LBL' ); ?>">
		<?php
		echo JText::_ ( 'PORTAL_SHOP_LBL' );
		?></span>
		</td>
		<td><?php
		echo $this->lists ['portalshop'];
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
	<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_URL_AFTER_PORTAL_LOGIN_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_URL_AFTER_PORTAL_LOGIN' ); ?>">
		<?php
		echo JText::_ ( 'URL_AFTER_PORTAL_LOGIN' );
		?></span></td>
		<td><?php
		echo $this->lists ['url_after_portal_login'];
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
	<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_URL_AFTER_PORTAL_LOGOUT_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_URL_AFTER_PORTAL_LOGOUT' ); ?>">
		<?php
		echo JText::_ ( 'URL_AFTER_PORTAL_LOGOUT' );
		?></span></td>
		<td><?php
		echo $this->lists ['url_after_portal_logout'];
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_DEFAULT_PORTAL_NAME_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_DEFAULT_PORTAL_NAME' ); ?>">
		<?php
		echo JText::_ ( 'DEFAULT_PORTAL_NAME_LBL' );
		?></span></td>
		<td><input type="text" name="default_portal_name"
			id="default_portal_name"
			value="<?php
			echo DEFAULT_PORTAL_NAME;
			?>" />
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_DEFAULT_PORTAL_LOGO' ); ?>::<?php echo JText::_( 'TOOLTIP_DEFAULT_PORTAL_LOGO_LBL' ); ?>">
		<?php
		echo JText::_ ( 'DEFAULT_PORTAL_LOGO_LBL' );
		?></td>
		<td>
		<div><input type="file" name="default_portal_logo"
			id="default_portal_logo" size="57" /> <input type="hidden"
			name="default_portal_logo_tmp"
			value="<?php
			echo DEFAULT_PORTAL_LOGO;
			?>" /><a href="#123"   onclick="delimg('<?php echo DEFAULT_PORTAL_LOGO?>','usrdiv','<?php echo $shopperlogo_path?>');">Remove File</a></div>
			<div id="usrdiv">
		<?php
		if (is_file ( JPATH_ROOT . '/components/com_redshop/assets/images/shopperlogo/' . DEFAULT_PORTAL_LOGO)) {
			?>
		<div><a class="modal"
			href="<?php
			echo $url . '/components/com_redshop/assets/images/shopperlogo/' . DEFAULT_PORTAL_LOGO;
			?>"
			title="<?php
			echo DEFAULT_PORTAL_LOGO;
			?>"
			rel="{handler: 'image', size: {}}"> <img width="100" height="100"
			alt="<?php
			echo DEFAULT_PORTAL_LOGO;
			?>"
			src="<?php
			echo $url . '/components/com_redshop/assets/images/shopperlogo/' . DEFAULT_PORTAL_LOGO;
			?>" /></a></div>
		<?php
		}
		?></div></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_SHOPPER_GROUP_DEFAULT_PRIVATE' ); ?>::<?php echo JText::_( 'TOOLTIP_SHOPPER_GROUP_DEFAULT_PRIVATE_LBL' ); ?>">
		<?php
		echo JText::_ ( 'SHOPPER_GROUP_DEFAULT_PRIVATE_LBL' );
		?></span></td>
		<td><?php
		echo $this->lists ['shopper_group_default_private'];

		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'TOOLTIP_SHOPPER_GROUP_DEFAULT_COMPANY' ); ?>::<?php echo JText::_( 'TOOLTIP_SHOPPER_GROUP_DEFAULT_COMPANY_LBL' ); ?>">
		<?php
		echo JText::_ ( 'SHOPPER_GROUP_DEFAULT_COMPANY_LBL' );
		?></span></td>
		<td><?php
		echo $this->lists ['shopper_group_default_company'];

		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'SHOPPER_GROUP_DEFAULT_UNREGISTERED_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_SHOPPER_GROUP_DEFAULT_UNREGISTERED_LBL' ); ?>">
		<?php
		echo JText::_ ( 'SHOPPER_GROUP_DEFAULT_UNREGISTERED_LBL' );
		?></span></td>
		<td><?php
		echo $this->lists ['shopper_group_default_unregistered'];

		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'NEW_SHOPPER_GROUP_GET_VALUE_FROM_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_NEW_SHOPPER_GROUP_GET_VALUE_FROM_LBL' ); ?>">
		<?php
		echo JText::_ ( 'NEW_SHOPPER_GROUP_GET_VALUE_FROM_LBL' );
		?></span></td>
		<td><?php
		echo $this->lists ['new_shopper_group_get_value_from'];

		?></td>
	</tr>
	<!--<tr>
		<td width="100" align="right" class="key">
		<?php
		echo JText::_ ( 'SHOPPER_GROUP_DEFAULT_TAX_EXEMPT_LBL' );
		?></td>
		<td><?php
		echo $this->lists ['shopper_group_default_tax_exempt'];

		?></td>
	</tr>
	-->

</table>