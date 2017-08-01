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
<table class="table table-striped">
    <tbody>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_DEVELOPMENT_LBL') ?></strong></td>
        <td><?php echo JHtml::_('select.booleanlist', 'FEDEX_DEVELOPMENT', '', FEDEX_DEVELOPMENT) ?></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_ACCOUNT_NUMBER_LBL') ?></strong></td>
        <td><input type="text" name="FEDEX_ACCOUNT_NUMBER" class="form-control"
                   value="<?php echo FEDEX_ACCOUNT_NUMBER ?>"/></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_METER_NUMBER_LBL') ?></strong></td>
        <td><input type="text" name="FEDEX_METER_NUMBER" class="form-control" value="<?php echo FEDEX_METER_NUMBER ?>"/>
        </td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_KEY_LBL') ?></strong></td>
        <td><input type="text" name="FEDEX_KEY" class="form-control" value="<?php echo FEDEX_KEY ?>"/></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_PASSWORD_LBL') ?></strong></td>
        <td><input type="text" name="FEDEX_PASSWORD" class="form-control" value="<?php echo FEDEX_PASSWORD ?>"/></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_ADDRESS_LBL') ?></strong></td>
        <td><input type="text" name="FEDEX_SHIPPER_ADDRESS" class="form-control"
                   value="<?php echo FEDEX_SHIPPER_ADDRESS ?>"/></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_CITY_LBL') ?></strong></td>
        <td><input type="text" name="FEDEX_SHIPPER_CITY" class="form-control" value="<?php echo FEDEX_SHIPPER_CITY ?>"/>
        </td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_STATE_LBL') ?></strong></td>
        <td><input type="text" name="FEDEX_SHIPPER_STATE" class="form-control"
                   value="<?php echo FEDEX_SHIPPER_STATE ?>"/></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_POSTAL_CODE_LBL') ?></strong></td>
        <td><input type="text" name="FEDEX_SHIPPER_POSTAL_CODE" class="form-control"
                   value="<?php echo FEDEX_SHIPPER_POSTAL_CODE ?>"/></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SHIPPER_COUNTRY_LBL') ?></strong></td>
        <td><input type="text" name="FEDEX_SHIPPER_COUNTRY_CODE" class="form-control"
                   value="<?php echo FEDEX_SHIPPER_COUNTRY_CODE ?>"/></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_DISCOUNT_LBL') ?></strong></td>
        <td><?php echo JHtml::_('select.booleanlist', 'FEDEX_DISCOUNT', '', Redshop::getConfig()->get('FEDEX_DISCOUNT')); ?></td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_CARRIERCODE_LBL') ?></strong></td>
        <td>
			<?php
			$source         = array();
			$source['FDXE'] = JText::_('PLG_REDSHOP_SHIPPING_FDXE_LBL');
			$source['FDXG'] = JText::_('PLG_REDSHOP_SHIPPING_FDXG_LBL');
			echo JHtml::_('select.genericlist', $source, 'FEDEX_CARRIERCODE', '', 'value', 'text', FEDEX_CARRIERCODE);
			?>
        </td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_SERVICE_TYPE_LBL') ?></strong></td>
        <td>
			<?php
			$source                                        = array();
			$source['PRIORITY_OVERNIGHT']                  = JText::_('PLG_REDSHOP_SHIPPING_PRIORITY_OVERNIGHT_LBL');
			$source['STANDARD_OVERNIGHT']                  = JText::_('PLG_REDSHOP_SHIPPING_STANDARD_OVERNIGHT_LBL');
			$source['FIRST_OVERNIGHT']                     = JText::_('PLG_REDSHOP_SHIPPING_FIRST_OVERNIGHT_LBL');
			$source['FEDEX_2_DAY']                         = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_2DAY_LBL');
			$source['FEDEX_EXPRESS_SAVER']                 = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_EXPRESS_SAVER_LBL');
			$source['INTERNATIONAL_PRIORITY']              = JText::_('PLG_REDSHOP_SHIPPING_INTERNATIONAL_PRIORITY_LBL');
			$source['INTERNATIONAL_ECONOMY']               = JText::_('PLG_REDSHOP_SHIPPING_INTERNATIONAL_ECONOMY_LBL');
			$source['INTERNATIONAL_FIRST']                 = JText::_('PLG_REDSHOP_SHIPPING_INTERNATIONAL_FIRST_LBL');
			$source['FEDEX_1_DAY_FREIGHT']                 = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_1_DAY_FREIGHT_LBL');
			$source['FEDEX_2_DAY_FREIGHT']                 = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_2_DAY_FREIGHT_LBL');
			$source['FEDEX_3_DAY_FREIGHT']                 = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_3_DAY_FREIGHT_LBL');
			$source['FEDEX_GROUND']                        = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_GROUND_LBL');
			$source['GROUND_HOME_DELIVERY']                = JText::_('PLG_REDSHOP_SHIPPING_GROUND_HOME_DELIVERY_LBL');
			$source['INTERNATIONAL_PRIORITY_FREIGHT']      = JText::_('PLG_REDSHOP_SHIPPING_INTERNATIONAL_PRIORITY_FREIGHT_LBL');
			$source['INTERNATIONAL_ECONOMY_FREIGHT']       = JText::_('PLG_REDSHOP_SHIPPING_INTERNATIONAL_ECONOMY_FREIGHT_LBL');
			$source['EUROPE_FIRST_INTERNATIONAL_PRIORITY'] = JText::_('PLG_REDSHOP_SHIPPING_EUROPE_FIRST_INTERNATIONAL_PRIORITY_LBL');

			echo JHtml::_('select.genericlist', $source, 'FEDEX_SERVICETYPE[]', 'multiple="multiple" size="10"', 'value', 'text', unserialize(FEDEX_SERVICETYPE));
			?>
        </td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_DROP_OFF_TYPE_LBL') ?></strong></td>
        <td>
			<?php
			$source                            = array();
			$source['REGULAR_PICKUP']          = JText::_('PLG_REDSHOP_SHIPPING_REGULAR_PICKUP_LBL');
			$source['REQUEST_COURIER']         = JText::_('PLG_REDSHOP_SHIPPING_REQUEST_COURIER_LBL');
			$source['DROP_BOX']                = JText::_('PLG_REDSHOP_SHIPPING_DROP_BOX_LBL');
			$source['BUSINESS_SERVICE_CENTER'] = JText::_('PLG_REDSHOP_SHIPPING_BUSINESS_SERVICE_CENTER_LBL');
			$source['STATION']                 = JText::_('PLG_REDSHOP_SHIPPING_STATION_LBL');

			echo JHtml::_('select.genericlist', $source, 'FEDEX_DROPOFFTYPE', '', 'value', 'text', FEDEX_DROPOFFTYPE);
			?>
        </td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_PACKAGING_TYPE_LBL') ?></strong></td>
        <td>
			<?php
			$source                   = array();
			$source['FEDEX_ENVELOPE'] = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_ENVELOPE_LBL');
			$source['FEDEX_PAK']      = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_PAK_LBL');
			$source['FEDEX_BOX']      = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_BOX_LBL');
			$source['FEDEX_TUBE']     = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_TUBE_LBL');
			$source['FEDEX_10KG_BOX'] = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_10KG_BOX_LBL');
			$source['FEDEX_25KG_BOX'] = JText::_('PLG_REDSHOP_SHIPPING_FEDEX_25KG_BOX_LBL');
			$source['YOUR_PACKAGING'] = JText::_('PLG_REDSHOP_SHIPPING_YOUR_PACKAGING_LBL');

			echo JHtml::_('select.genericlist', $source, 'FEDEX_PACKAGINGTYPE', '', 'value', 'text', FEDEX_PACKAGINGTYPE);
			?>
        </td>
    </tr>
    <tr>
        <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_FEDEX_WEIGHT_UNITS_LBL') ?></strong></td>
        <td>
			<?php
			$source        = array();
			$source['lbs'] = JText::_('PLG_REDSHOP_SHIPPING_LBS');
			$source['kg']  = JText::_('PLG_REDSHOP_SHIPPING_KGS');

			echo JHtml::_('select.genericlist', $source, 'FEDEX_WEIGHTUNITS', '', 'value', 'text', FEDEX_WEIGHTUNITS);
			?>
        </td>
    </tr>
    </tbody>
</table>
