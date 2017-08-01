<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   string  $shopList  ShopList dropdown
 * @param   string  $zipcode   Zipcode input
 * @param   string  $phone     Phone input
 */
extract($displayData);
?>

<div class="form-group gls_zipcode">
    <label><?php echo JText::_('COM_REDSHOP_PROVIDE_ZIPCODE_TO_PICKUP_PARCEL') ?></label>
	<?php echo $zipcode; ?>
</div>

<div class="form-group gls_shoplist">
    <label><?php echo JText::_('COM_REDSHOP_SELECT_GLS_LOCATION') ?></label>
	<?php echo $shopList; ?>
</div>

<div class="form-group gls_mobile">
    <label><?php echo JText::_('COM_REDSHOP_ENTER_GLS_MOBILE') ?></label>
	<?php echo $phone; ?>
</div>
