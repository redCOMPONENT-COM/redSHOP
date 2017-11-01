<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_LIMIT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DOWNLOAD_LIMIT_LBL'),
		'field' => '<input type="number" name="product_download_limit" id="product_download_limit" class="form-control"'
            . ' value="' . $this->config->get('PRODUCT_DOWNLOAD_LIMIT') . '" />'
	)
);

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_DAYS_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DOWNLOAD_DAYS_LBL'),
		'field' => '<input type="number" name="product_download_days" id="product_download_days" class="form-control"'
			. ' value="' . $this->config->get('PRODUCT_DOWNLOAD_DAYS') . '" />'
	)
);
?>
<div class="alert alert-warning alert-dismissible" role="alert">
    <button class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> <?php echo JText::_('WARNING') ?></h4>
    <p><?php echo JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_ROOT_WARNING') ?></p>
</div>
<?php
$product_download_root = $this->config->get('PRODUCT_DOWNLOAD_ROOT');

if (!is_dir($product_download_root))
{
	$product_download_root = JPATH_ROOT . '/components/com_redshop/assets/download/product';
}

echo RedshopLayoutHelper::render(
	'config.config',
	array(
		'title' => JText::_('COM_REDSHOP_PRODUCT_DOWNLOAD_ROOT_LBL'),
		'desc'  => JText::_('COM_REDSHOP_TOOLTIP_PRODUCT_DOWNLOAD_ROOT_LBL'),
		'line'  => false,
		'field' => '<input type="text" name="product_download_root" id="product_download_root" class="form-control" size="55"'
			. ' value="' . $product_download_root . '" />'
	)
);
