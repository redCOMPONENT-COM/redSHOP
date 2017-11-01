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
 * @param   array   $shopList         ShopList dropdown
 * @param   int     $selectedShopId   Selected Shop ID
 * @param   object  $values           Values
 */
extract($displayData);
?>

<div class="form-group gls_zipcode">
    <label><?php echo JText::_('COM_REDSHOP_PROVIDE_ZIPCODE_TO_PICKUP_PARCEL') ?></label>
    <input type="text" id="gls_zipcode" name="gls_zipcode" value="<?php echo $values->zipcode; ?>" onblur="javascript:updateGLSLocation(this.value);"" />
</div>

<div class="form-group gls_shoplist">
    <label><?php echo JText::_('COM_REDSHOP_SELECT_GLS_LOCATION') ?></label>
    <span id="rs_locationdropdown">
    	<?php echo JHTML::_('select.genericlist', $shopList, 'shop_id', 'class="inputbox" ', 'value', 'text', $selectedShopId, false, true); ?>
    </span>
</div>

<div class="form-group gls_mobile">
    <label><?php echo JText::_('COM_REDSHOP_ENTER_GLS_MOBILE') ?></label>
	<input type="text" id="gls_mobile" name="gls_mobile"  value="<?php echo $values->phone; ?>" />
</div>
