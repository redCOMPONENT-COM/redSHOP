<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die ('Restricted access');

?>
<table class="admintable">
	<tr>
		<td class="config_param"><?php echo JText::_('COM_REDSHOP_DOWNLOAD'); ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_LIMIT_LBL'); ?>::<?php echo JText::_('TOOLTIP_PRODUCT_DOWNLOAD_LIMIT_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_LIMIT_LBL');?></label></span>
		</td>
		<td>
			<input type="text" name="product_download_limit" id="product_download_limit"
			       value="<?php echo PRODUCT_DOWNLOAD_LIMIT; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
			<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_DAYS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DOWNLOAD_DAYS_LBL'); ?>">
			<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_DAYS_LBL');?></label></span>
		</td>
		<td>
			<input type="text" name="product_download_days" id="product_download_days"
			       value="<?php echo PRODUCT_DOWNLOAD_DAYS; ?>">
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key">
		<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_ROOT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DOWNLOAD_ROOT_LBL'); ?>">
		<label
			for="product_download_root"><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_ROOT_LBL');?></label></span>
		</td>
		<td>
			<?php
			$product_download_root = PRODUCT_DOWNLOAD_ROOT;
			if (!is_dir($product_download_root))
				$product_download_root = JPATH_ROOT . '/components/com_redshop/assets/download/product';

			?>
			<input type="text" name="product_download_root" id="product_download_root" size="55"
			       value="<?php echo $product_download_root; ?>">
		</td>
	</tr>

</table>
