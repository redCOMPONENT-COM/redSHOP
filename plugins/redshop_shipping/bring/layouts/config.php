<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * ===========================
 * @var  array $displayData Available Data.
 * @var  array $services    Available Services.
 * @var  array $selected    Selected services.
 */

extract($displayData);

?>
<div class="row">
    <div class="form-group">
        <label class="col-md-4"></label>
        <div class="col-md-8">

        </div>
    </div>
</div>

<table class="table table-striped">
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_USERCODE_LBL') ?></strong></td>
        <td><input type="text" name="BRING_USERCODE" class="form-control" value="<?php echo BRING_USERCODE ?>"/></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_SERVER_LBL') ?></strong></td>
        <td><input type="text" name="BRING_SERVER" class="form-control" value="<?php echo BRING_SERVER ?>"/></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_PATH_LBL') ?></strong></td>
        <td><input type="text" name="BRING_PATH" class="form-control" value="<?php echo BRING_PATH ?>"/></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_ZIPCODE_FROM_LBL') ?></strong></td>
        <td><input type="text" name="BRING_ZIPCODE_FROM" class="form-control" value="<?php echo BRING_ZIPCODE_FROM ?>"/></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_PRICE_SHOW_WITHVAT_LBL') ?></strong></td>
        <td><?php echo JHtml::_('redshopselect.booleanlist', 'BRING_PRICE_SHOW_WITHVAT', '', BRING_PRICE_SHOW_WITHVAT); ?></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_PRICE_SHOW_SHORT_DESC_LBL') ?></strong></td>
        <td><?php echo JHtml::_('redshopselect.booleanlist', 'BRING_PRICE_SHOW_SHORT_DESC', '', BRING_PRICE_SHOW_SHORT_DESC); ?></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_PRICE_SHOW_DESC_LBL') ?></strong></td>
        <td><?php echo JHtml::_('redshopselect.booleanlist', 'BRING_PRICE_SHOW_DESC', '', BRING_PRICE_SHOW_DESC); ?></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_USE_SHIPPING_BOX_LBL') ?></strong></td>
        <td><?php echo JHtml::_('redshopselect.booleanlist', 'BRING_USE_SHIPPING_BOX', '', BRING_USE_SHIPPING_BOX); ?></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_BRING_SERVICE_LBL') ?></strong></td>
        <td><?php echo JHtml::_('select.genericlist', $services, 'BRING_SERVICE[]', 'multiple="multiple"', 'value', 'text', $selected) ?></td>
    </tr>
</table>
