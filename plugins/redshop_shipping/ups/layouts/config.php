<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

?>
<div class="row">
    <div class="col-md-6">
        <table class="table table-striped">
            <tr class="row0">
                <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_ACCESS_CODE') ?></strong></td>
                <td><input type="text" name="UPS_ACCESS_CODE" class="form-control" value="<?php echo UPS_ACCESS_CODE ?>"/></td>
                <td></td>
            </tr>
            <tr class="row1">
                <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_USER_ID') ?></strong></td>
                <td><input type="text" name="UPS_USER_ID" class="form-control" value="<?php echo UPS_USER_ID ?>"/></td>
                <td></td>
            </tr>
            <tr class="row0">
                <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_PASSWORD') ?></strong></td>
                <td><input type="text" name="UPS_PASSWORD" class="form-control" value="<?php echo UPS_PASSWORD ?>"/></td>
                <td></td>
            </tr>
            <tr class="row1">
                <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_PICKUP_METHOD') ?></strong></td>
                <td>
                    <select class="form-control" name="pickup_type">
                        <option <?php if (UPS_PICKUP_TYPE == "01") echo "selected=\"selected\"" ?> value="01">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_DAILY_PICKUP'); ?>
                        </option>
                        <option <?php if (UPS_PICKUP_TYPE == "03") echo "selected=\"selected\"" ?> value="03">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_CUSTOMER_COUNTER'); ?>
                        </option>
                        <option <?php if (UPS_PICKUP_TYPE == "06") echo "selected=\"selected\"" ?> value="06">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_ONE_TIME_PICKUP'); ?>
                        </option>
                        <option <?php if (UPS_PICKUP_TYPE == "07") echo "selected=\"selected\"" ?> value="07">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_ON_CALL_AIR_PICKUP'); ?>
                        </option>
                        <option <?php if (UPS_PICKUP_TYPE == "19") echo "selected=\"selected\"" ?> value="19">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_LETTER_CENTER'); ?>
                        </option>
                        <option <?php if (UPS_PICKUP_TYPE == "20") echo "selected=\"selected\"" ?> value="20">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_AIR_SERVICE_CENTER'); ?>
                        </option>
                    </select>
                </td>
                <td></td>
            </tr>
            <tr class="row0">
                <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_PACKAGE_TYPE') ?></strong></td>
                <td>
                    <select class="form-control" name="package_type">
                        <option <?php if (UPS_PACKAGE_TYPE == "00") echo "selected=\"selected\"" ?> value="00">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UNKNOWN'); ?>
                        </option>
                        <option <?php if (UPS_PACKAGE_TYPE == "01") echo "selected=\"selected\"" ?> value="01">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_LETTER'); ?>
                        </option>
                        <option <?php if (UPS_PACKAGE_TYPE == "02") echo "selected=\"selected\"" ?> value="02">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_PACKAGE'); ?>
                        </option>
                        <option <?php if (UPS_PACKAGE_TYPE == "03") echo "selected=\"selected\"" ?> value="03">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_TUBE'); ?>
                        </option>
                        <option <?php if (UPS_PACKAGE_TYPE == "04") echo "selected=\"selected\"" ?> value="04">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_PAK'); ?>
                        </option>
                        <option <?php if (UPS_PACKAGE_TYPE == "21") echo "selected=\"selected\"" ?> value="21">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_EXPRESS_BOX'); ?>
                        </option>
                        <option <?php if (UPS_PACKAGE_TYPE == "24") echo "selected=\"selected\"" ?> value="24">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_25KG_BOX'); ?>
                        </option>
                        <option <?php if (UPS_PACKAGE_TYPE == "25") echo "selected=\"selected\"" ?> value="25">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_10KG_BOX'); ?>
                        </option>
                    </select>
                </td>
                <td></td>
            </tr>
            <tr class="row1">
                <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_TYPE_RESIDENTIAL') ?></strong></td>
                <td>
                    <select class="form-control" name="residential">
                        <option <?php if (UPS_RESIDENTIAL == "yes") echo "selected=\"selected\"" ?> value="yes">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_RESIDENTIAL') ?>
                        </option>
                        <option <?php if (UPS_RESIDENTIAL == "no") echo "selected=\"selected\"" ?> value="no">
						    <?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_COMMERCIAL') ?>
                        </option>
                    </select></td>
                <td></td>
            </tr>
            <tr class="row1">
                <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_HANDLING_FEE') ?></strong></td>
                <td><input class="form-control" type="text" name="handling_fee" value="<?php echo UPS_HANDLING_FEE ?>"/></td>
                <td></td>
            </tr>
            <tr class="row1">
                <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_SHIP_FROM_ZIPCODE'); ?></strong></td>
                <td>
                    <input class="form-control" type="text" name="Override_Source_Zip" value="<?php echo Override_Source_Zip ?>"/>
                </td>
                <td>
				    <?php echo JHtml::tooltip(JText::_('PLG_REDSHOP_SHIPPING_UPS_SHIP_FROM_ZIPCODE_TOOLTIP'), JText::_('PLG_REDSHOP_SHIPPING_UPS_SHIP_FROM_ZIPCODE'), 'tooltip.png', '', '', false); ?>
                </td>
            </tr>
            <tr class="row0">
                <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_SHOW_DELIVERY_DAY_QUOTE'); ?></strong></td>
                <td>
                    <input class="checkbox" type="checkbox"
                            name="Show_Delivery_Days_Quote" <?php if (Show_Delivery_Days_Quote == 1) echo "checked=\"checked\""; ?> value="1"/>
                </td>
                <td></td>
            </tr>
            <tr class="row1">
                <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_SHOW_DELIVERY_ETA'); ?></strong></td>
                <td>
                    <input class="checkbox" type="checkbox" name="Show_Delivery_ETA_Quote"
					    <?php if (Show_Delivery_ETA_Quote == 1) echo "checked=\"checked\""; ?> value="1"/>
                </td>
                <td></td>
            </tr>
            <tr class="row0">
                <td><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_SHOW_DELIVERY_WARNING'); ?></strong></td>
                <td>
                    <input class="checkbox" type="checkbox" name="Show_Delivery_Warning" <?php if (Show_Delivery_Warning == 1) echo "checked=\"checked\""; ?>
                            value="1"/>
                </td>
                <td></td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-striped table-bordered">
            <tr class="row0">
                <td colspan="3"><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_AUTHORIZED_SHIPPING_METHOD'); ?></strong></td>
            </tr>
            <tr class="row1">
                <td>
                    <div align="left"><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_SHIPPING_METHOD_LBL'); ?></strong>
                    </div>
                </td>
                <td width="10">
                    <div align="left"><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_ENABLE'); ?></strong></div>
                </td>
                <td>
                    <div align="left"><strong><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_FUEL_SURCHARGE_RATE'); ?></strong>
					    <?php echo JHtml::tooltip(JText::_('PLG_REDSHOP_SHIPPING_UPS_SHIPPING_METHOD_LBL'), JText::_('PLG_REDSHOP_SHIPPING_UPS_SHIPPING_METHOD_LBL_TOOLTIP'), 'tooltip.png', '', '', false); ?>
                    </div>
                </td>
            </tr>
            <tr class="row0">
                <td><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_NEXT_DAY_AIR'); ?></td>
                <td>
                    <div align="center"><input type="checkbox" name="UPS_Next_Day_Air"
                                class="checkbox" <?php if (UPS_Next_Day_Air == 01) echo "checked=\"checked\""; ?>
                                value="01"/></div>
                </td>
                <td><input class="form-control" type="text" name="UPS_Next_Day_Air_FSC"
                            value="<?php echo UPS_Next_Day_Air_FSC; ?>"/></td>
            </tr>
            <tr class="row1">
                <td><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_2ND_DAY_AIR'); ?></td>
                <td>
                    <div align="center"><input type="checkbox" name="UPS_2nd_Day_Air"
                                class="checkbox" <?php if (UPS_2nd_Day_Air == 02) echo "checked=\"checked\""; ?>
                                value="02"/></div>
                </td>
                <td><input class="form-control" type="text" name="UPS_2nd_Day_Air_FSC"
                            value="<?php echo UPS_2nd_Day_Air_FSC; ?>"/></td>
            </tr>
            <tr class="row0">
                <td><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_GROUND'); ?></td>
                <td>
                    <div align="center"><input type="checkbox" name="UPS_Ground"
                                class="checkbox" <?php if (UPS_Ground == 03) echo "checked=\"checked\""; ?>
                                value="03"/></div>
                </td>
                <td><input class="form-control" type="text" name="UPS_Ground_FSC"
                            value="<?php echo UPS_Ground_FSC; ?>"/></td>
            </tr>
            <tr class="row1">
                <td><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_WORLDWIDE_EXPRESS_SM'); ?></td>
                <td>
                    <div align="center"><input type="checkbox" name="UPS_Worldwide_Express_SM"
                                class="checkbox" <?php if (UPS_Worldwide_Express_SM == 07) echo "checked=\"checked\""; ?>
                                value="07"/></div>
                </td>
                <td><input class="form-control" type="text" name="UPS_Worldwide_Express_SM_FSC"
                            value="<?php echo UPS_Worldwide_Express_SM_FSC; ?>"/></td>
            </tr>
            <tr class="row0">
                <td><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_WORLDWIDE_EXPEDITED_SM'); ?></td>
                <td>
                    <div align="center"><input type="checkbox" name="UPS_Worldwide_Expedited_SM"
                                class="checkbox" <?php if (UPS_Worldwide_Expedited_SM == '08') echo "checked=\"checked\""; ?>
                                value="08"/></div>
                </td>
                <td><input class="form-control" type="text" name="UPS_Worldwide_Expedited_SM_FSC"
                            value="<?php echo UPS_Worldwide_Expedited_SM_FSC; ?>"/></td>
            </tr>
            <tr class="row1">
                <td><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_STANDARD'); ?></td>
                <td>
                    <div align="center">
                        <input type="checkbox" name="UPS_Standard"
                                class="checkbox" <?php if (UPS_Standard == 11) echo "checked=\"checked\""; ?>
                                value="11"/>
                    </div>
                </td>
                <td><input class="form-control" type="text" name="UPS_Standard_FSC"
                            value="<?php echo UPS_Standard_FSC; ?>"/>
                </td>
            </tr>
            <tr class="row0">
                <td><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_3_DAY_SELECT'); ?></td>
                <td>
                    <div align="center">
                        <input type="checkbox" name="UPS_3_Day_Select"
                                class="checkbox" <?php if (UPS_3_Day_Select == 12) echo "checked=\"checked\""; ?>
                                value="12"/>
                    </div>
                </td>
                <td><input class="form-control" type="text" name="UPS_3_Day_Select_FSC"
                            value="<?php echo UPS_3_Day_Select_FSC; ?>"/>
                </td>
            </tr>
            <tr class="row1">
                <td><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_NEXT_DAY_AIR_SAVER'); ?></td>
                <td>
                    <div align="center">
                        <input type="checkbox" name="UPS_Next_Day_Air_Saver"
                                class="checkbox" <?php if (UPS_Next_Day_Air_Saver == 13) echo "checked=\"checked\""; ?>
                                value="13"/>
                    </div>
                </td>
                <td><input class="form-control" type="text" name="UPS_Next_Day_Air_Saver_FSC"
                            value="<?php echo UPS_Next_Day_Air_Saver_FSC; ?>"/>
                </td>
            </tr>
            <tr class="row0">
                <td><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_NEXT_DAY_AIR_EARLY_AM'); ?></td>
                <td>
                    <div align="center">
                        <input type="checkbox" name="UPS_Next_Day_Air_Early_AM"
                                class="checkbox" <?php if (UPS_Next_Day_Air_Early_AM == 14) echo "checked=\"checked\""; ?>
                                value="14"/>
                    </div>
                </td>
                <td><input class="form-control" type="text" name="UPS_Next_Day_Air_Early_AM_FSC"
                            value="<?php echo UPS_Next_Day_Air_Early_AM_FSC; ?>"/>
                </td>
            </tr>
            <tr class="row1">
                <td><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_WORLDWIDE_EXPRESS_PLUS_SM'); ?></td>
                <td>
                    <div align="center">
                        <input type="checkbox" name="UPS_Worldwide_Express_Plus_SM"
                                class="checkbox" <?php if (UPS_Worldwide_Express_Plus_SM == 54) echo "checked=\"checked\""; ?>
                                value="54"/>
                    </div>
                </td>
                <td><input class="form-control" type="text" name="UPS_Worldwide_Express_Plus_SM_FSC"
                            value="<?php echo UPS_Worldwide_Express_Plus_SM_FSC; ?>"/>
                </td>
            </tr>
            <tr class="row0">
                <td><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_2ND_DAY_AIR_AM'); ?></td>
                <td>
                    <div align="center">
                        <input type="checkbox" name="UPS_2nd_Day_Air_AM"
                                class="checkbox" <?php if (UPS_2nd_Day_Air_AM == 59) echo "checked=\"checked\""; ?>
                                value="59"/>
                    </div>
                </td>
                <td><input class="form-control" type="text" name="UPS_2nd_Day_Air_AM_FSC"
                            value="<?php echo UPS_2nd_Day_Air_AM_FSC; ?>"/>
                </td>
            </tr>
            <tr class="row1">
                <td><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_UPS_EXPRESS_SAVER'); ?></td>
                <td>
                    <div align="center">
                        <input type="checkbox" name="UPS_Saver"
                                class="checkbox" <?php if (UPS_Saver == 65) echo "checked=\"checked\""; ?>
                                value="65"/>
                    </div>
                </td>
                <td><input class="form-control" type="text" name="UPS_Saver_FSC" value="<?php echo UPS_Saver_FSC; ?>"/>
                </td>
            </tr>
            <tr class="row0">
                <td><?php echo JText::_('PLG_REDSHOP_SHIPPING_UPS_N_A'); ?></td>
                <td>
                    <div align="center">
                        <input type="checkbox" name="na"
                                class="checkbox" <?php if (na == 64) echo "checked=\"checked\""; ?> value="64"/>
                    </div>
                </td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>
</div>
