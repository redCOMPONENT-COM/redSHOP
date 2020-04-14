<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @var  array $displayData Layout data.
 * @var  array $data        Extra field data
 */
extract($displayData);
?>
<table style="border: 1px solid #ccc;background: #fff; width:600px;" cellpadding="0" cellspacing="0">
    <tr>
        <td>

            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;background: #f7f7f7;">
                <tbody>
                <tr>
                    <td colspan="3" style="height: 20px;"></td>
                </tr>
                <tr>
                    <td style="width: 20px;"></td>
                    <td>
                        <table width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="400px">
                                    <a href="http://redcomponent.com" target="_blank"> <img src="<?php echo \JUri::root() . 'media/com_redshop/images/redcomponent-logo.jpg' ?>" style="width: 340px; height: auto; padding-top: 20px;" /> </a>
                                </td>
                                <td style="font-size: 12px; color: #878787; float: right;text-align: right;font-family: verdana;vertical-align:top;">
                                    <p style="font-size: 24px; font-family: verdana; color: #616161; text-align: justify;">Order status</p>
                                    <p style="font-size: 14px; font-family: verdana; color: #616161; text-align: justify;">Your order payment method has changed.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 20px;"></td>
                </tr>
                </tbody>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="height: 20px; background-color: #444544;"></td>
                </tr>
            </table>

            <table width="100%" style="margin-top: 30px;" cellpadding="0" cellspacing="0">
                <tr>
                    <p> You can view order detail
                        <a href="<?php echo \JRoute::_('index.php?option=com_redshop&view=order_detail&layout=receipt&oid=' . $order->order_id . '&Itemid=0') ?>">here</a>
                    </p>
                    <td style="height: 45px; background-color: #444544; display: block !important;"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>