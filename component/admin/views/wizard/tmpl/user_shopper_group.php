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
?>
<table class="admintable">
	<tr>
		<td width="100" align="right" class="key">
		<?php
		echo JText::_ ( 'PORTAL_SHOP_LBL' );
		?></td>
		<td><?php
		echo $this->lists ['portalshop'];
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<?php
		echo JText::_ ( 'URL_AFTER_PORTAL_LOGIN' );
		?></td>
		<td><?php
		echo $this->lists ['url_after_portal_login'];
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<?php
		echo JText::_ ( 'URL_AFTER_PORTAL_LOGOUT' );
		?></td>
		<td><?php
		echo $this->lists ['url_after_portal_logout'];
		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<?php
		echo JText::_ ( 'DEFAULT_PORTAL_NAME_LBL' );
		?></td>
		<td><input type="text" name="default_portal_name"
			id="default_portal_name"
			value="<?php
			echo DEFAULT_PORTAL_NAME;
			?>" />
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<?php
		echo JText::_ ( 'SHOPPER_GROUP_DEFAULT_PRIVATE_LBL' );
		?></td>
		<td><?php
		echo $this->lists ['shopper_group_default_private'];

		?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<?php
		echo JText::_ ( 'SHOPPER_GROUP_DEFAULT_COMPANY_LBL' );
		?></td>
		<td><?php
		echo $this->lists ['shopper_group_default_company'];

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
	--><tr>
		<td width="100" align="right" class="key">
		<?php
		echo JText::_ ( 'DEFAULT_PORTAL_LOGO_LBL' );
		?></td>
		<td>
		<div><input type="file" name="default_portal_logo"
			id="default_portal_logo" size="57" /> <input type="hidden"
			name="default_portal_logo_tmp"
			value="<?php
			echo DEFAULT_PORTAL_LOGO;
			?>" /></div>
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
		?></td>
	</tr>
</table>