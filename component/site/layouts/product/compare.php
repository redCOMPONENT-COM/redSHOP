<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$itemId = RedshopHelperUtility::getRedShopMenuItem(
	array(
		'option' => 'com_redshop',
		'view'   => 'product',
		'layout' => 'compare'
	)
);

$compareLink = JRoute::_('index.php?option=com_redshop&view=product&layout=compare&Itemid=' . $itemId);
?>

<div class="compare_product_div">
    <a class="btn btn-primary" href="<?php echo $compareLink ?>"><?php echo JText::_('COM_REDSHOP_SHOW_PRODUCTS_TO_COMPARE') ?></a>
    <div id="divCompareProduct"></div>
</div>
