<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>

<legend><?php echo JText::_('COM_REDSHOP_DOWNLOAD'); ?></legend>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_LIMIT_LBL'); ?>::<?php echo JText::_('TOOLTIP_PRODUCT_DOWNLOAD_LIMIT_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_LIMIT_LBL');?></label>
	</span>
	<input type="text" name="product_download_limit" id="product_download_limit" value="<?php echo $this->config->get('PRODUCT_DOWNLOAD_LIMIT'); ?>">
</div>

<div class="form-group">
	<span class="editlinktip hasTip"
			      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_DAYS_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DOWNLOAD_DAYS_LBL'); ?>">
		<label for="name"><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_DAYS_LBL');?></label>
	</span>
	<input type="text" name="product_download_days" id="product_download_days" value="<?php echo $this->config->get('PRODUCT_DOWNLOAD_DAYS'); ?>">
</div>

<div class="alert alert-warning alert-dismissible" role="alert">
    <button class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> <?php echo JText::_('WARNING') ?></h4>
    <p><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_ROOT_WARNING') ?></p>
</div>

<div class="form-group">

	<span class="editlinktip hasTip"
		      title="<?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_ROOT_LBL'); ?>::<?php echo JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DOWNLOAD_ROOT_LBL'); ?>">
		<label for="product_download_root"><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_ROOT_LBL');?></label>
	</span>
	<?php
	$product_download_root = $this->config->get('PRODUCT_DOWNLOAD_ROOT');
	if (!is_dir($product_download_root))
		$product_download_root = JPATH_ROOT . '/components/com_redshop/assets/download/product';

	?>
	<input type="text" name="product_download_root" id="product_download_root" size="55" value="<?php echo $product_download_root; ?>">
</div>

