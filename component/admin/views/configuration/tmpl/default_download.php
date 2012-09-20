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

?>
<table class="admintable">
<tr><td class="config_param"><?php echo JText::_( 'DOWNLOAD' ); ?></td></tr>
<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'PRODUCT_DOWNLOAD_LIMIT_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_PRODUCT_DOWNLOAD_LIMIT_LBL' ); ?>">
			<label for="name"><?php echo JText::_ ( 'PRODUCT_DOWNLOAD_LIMIT_LBL' );?></label></span>
		</td>
		<td>
			<input type="text" name="product_download_limit" id="product_download_limit" value="<?php echo PRODUCT_DOWNLOAD_LIMIT;?>">
		</td>
</tr>
<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip" title="<?php echo JText::_( 'PRODUCT_DOWNLOAD_DAYS_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_PRODUCT_DOWNLOAD_DAYS_LBL' ); ?>">
			<label for="name"><?php echo JText::_ ( 'PRODUCT_DOWNLOAD_DAYS_LBL' );?></label></span>
		</td>
		<td>
			<input type="text" name="product_download_days" id="product_download_days" value="<?php echo PRODUCT_DOWNLOAD_DAYS;?>">
		</td>
</tr>
<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'PRODUCT_DOWNLOAD_ROOT_LBL' ); ?>::<?php echo JText::_( 'TOOLTIP_PRODUCT_DOWNLOAD_ROOT_LBL' ); ?>">
		<label for="product_download_root"><?php echo JText::_ ( 'PRODUCT_DOWNLOAD_ROOT_LBL' );?></label></span>
		</td>
		<td>
			<?php
			$product_download_root = PRODUCT_DOWNLOAD_ROOT;
			if (! is_dir ( $product_download_root ))
				$product_download_root = JPATH_ROOT . DS . 'components' . DS . 'com_redshop' . DS . 'assets' . DS . 'download' . DS . 'product';

			?>
			<input type="text" name="product_download_root" id="product_download_root" size="55" value="<?php echo $product_download_root;?>">
		</td>
</tr>

</table>