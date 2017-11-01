<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');

extract($displayData);

$thumbUrl = RedshopHelperMedia::getImagePath(
	$stockamountImage->stock_amount_image,
	'',
	'thumb',
	'stockroom',
	Redshop::getConfig()->get('DEFAULT_STOCKAMOUNT_THUMB_WIDTH'),
	Redshop::getConfig()->get('DEFAULT_STOCKAMOUNT_THUMB_HEIGHT'),
	Redshop::getConfig()->get('USE_IMAGE_SIZE_SWAPPING')
);
?>

<span class="hasTooltip" title="<?php echo $stockamountImage->stock_amount_image_tooltip ?>">
	<img src="<?php echo $thumbUrl ?>" alt="<?php echo $stockamountImage->stock_amount_image_tooltip ?>" id="stockImage<?php echo $product_id ?>"/>
</span>
