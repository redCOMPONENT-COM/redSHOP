<?php

/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$model   = $this->getModel('checkout');
$Itemid  = RedshopHelperRouter::getCheckoutItemId();
$session = JFactory::getSession();
$user    = $session->get('user');
$auth    = $session->get('auth');
$cart    = \Redshop\Cart\Helper::getCart();
?>

<form
    action="<?php echo Redshop\IO\Route::_('index.php?option=com_redshop&view=checkout&Itemid=' . $Itemid . '', false) ?>"
    method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
    <input type="hidden" name='l' value='0'>
    <?php
    $billingAddresses = $model->billingaddresses();
    $editbill         = Redshop\IO\Route::_(
        "index.php?option=com_redshop&view=account_billto&return=checkout&tmpl=component&setexit=1&Itemid=" . $Itemid,
        false
    );

    ?>
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <?php echo Text::_('COM_REDSHOP_BILL_TO_INFORMATION'); ?>
                    </h3>
                </div>

                <div class="panel-body">
                    <?php
                    if ($billingAddresses) {
                        echo $this->loadTemplate('billing'); ?>
                        <?php echo
                            RedshopLayoutHelper::render(
                                'modal.lightbox',
                                [
                                    'selector' => 'ModalEditBilling',
                                    'params'   => [
                                        'buttonText'  => Text::_('COM_REDSHOP_EDIT'),
                                        'buttonClass' => 'btn btn-primary',
                                        'width'       => '',
                                        'height'      => '',
                                        'url'         => $editbill,
                                    ]
                                ]
                            );
                        ?>
                        <?php if (($auth['users_info_id'] && Redshop::getConfig()->getInt('ENABLE_CLEAR_USER_INFO') == 1) && !$user->id): ?>
                            <button type="button" class="btn btn-primary" name="clear_user_info"
                                onclick="javascript:clearUserInfo();">
                                <?php echo Text::_('COM_REDSHOP_CLEAR_USER_INFO'); ?>
                            </button>
                        <?php endif;
                    } else {
                        ?>
                        <div class="billnotice">
                            <?php echo Text::_('COM_REDSHOP_FILL_BILLING_ADDRESS'); ?>
                        </div>
                        <?php echo
                            RedshopLayoutHelper::render(
                                'modal.lightbox',
                                [
                                    'selector' => 'ModalAddBilling',
                                    'params'   => [
                                        'buttonText'  => Text::_('COM_REDSHOP_ADD'),
                                        'buttonClass' => 'btn btn-primary',
                                        'width'       => '',
                                        'height'      => '',
                                        'url'         => $editbill,
                                    ]
                                ]
                            );
                        ?>
                        <?php
                    } ?>
                </div>
            </div>
        </div>

        <?php
        if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE')) {
            ?>
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <?php echo Text::_('COM_REDSHOP_SHIPPING_ADDRESSES'); ?>
                        </h3>
                    </div>

                    <div class="panel-body">
                        <?php
                        if ($billingAddresses && Redshop::getConfig()->get('OPTIONAL_SHIPPING_ADDRESS')) {
                            $checked = ((!isset($this->users_info_id) || $this->users_info_id == 0) || $this->users_info_id == $billingAddresses->users_info_id) ? 'checked' : ''; ?>

                            <div class="form-check">
                                <input onclick="document.adminForm.task.value = '';document.adminForm.submit();" type="radio"
                                    name="users_info_id" id="users_info_id_default" class="form-check-input"
                                    value="<?php echo $billingAddresses->users_info_id; ?>" <?php echo $checked; ?> />
                                <label class="form-check-label" for="users_info_id_default">
                                    <?php echo Text::_('COM_REDSHOP_DEFAULT_SHIPPING_ADDRESS'); ?>
                                </label>
                            </div>
                            <?php
                        }

                        $shippingaddresses = $model->shippingaddresses();
                        $addAddLink        = Redshop\IO\Route::_(
                            "index.php?option=com_redshop&view=account_shipto&task=addshipping&return=checkout&tmpl=component&is_company=" . $billingAddresses->is_company . "&Itemid=" . $Itemid,
                            false
                        );

                        for ($i = 0, $in = count($shippingaddresses); $i < $in; $i++) {
                            if ($this->users_info_id != "") {
                                $checked = ($this->users_info_id == $shippingaddresses[$i]->users_info_id) ? 'checked' : '';
                            }

                            $edit_addlink   = Redshop\IO\Route::_(
                                "index.php?option=com_redshop&view=account_shipto&task=addshipping&return=checkout&tmpl=component&infoid=" . $shippingaddresses[$i]->users_info_id . "&Itemid=" . $Itemid,
                                false
                            );
                            $delete_addlink = Redshop\IO\Route::_(
                                "index.php?option=com_redshop&view=account_shipto&return=checkout&tmpl=component&task=remove&infoid=" . $shippingaddresses[$i]->users_info_id . "&Itemid=" . $Itemid,
                                false
                            ); ?>

                            <div class="form-check">
                                <input onclick="document.adminForm.task.value = '';document.adminForm.submit();" type="radio"
                                    name="users_info_id" id="users_info_id_<?php echo $i; ?>" class="form-check-input"
                                    value="<?php echo $shippingaddresses[$i]->users_info_id; ?>" <?php echo $checked; ?> />
                                <label for="users_info_id_<?php echo $i ?>" class="form-check-label">
                                    <?php if (Redshop::getConfig()->get('ENABLE_ADDRESS_DETAIL_IN_SHIPPING')) {
                                        echo $shippingaddresses[$i]->address . " ";
                                    }
                                    echo $shippingaddresses[$i]->text; ?>
                                </label>
                                <?php echo
                                    RedshopLayoutHelper::render(
                                        'modal.lightbox',
                                        [
                                            'selector' => 'ModalEditAddAddress',
                                            'params'   => [
                                                'buttonText'  => Text::_('COM_REDSHOP_EDIT_LBL'),
                                                'buttonClass' => '',
                                                'width'       => '800',
                                                'height'      => '',
                                                'url'         => $edit_addlink,
                                            ]
                                        ]
                                    );
                                ?>
                                <a href="<?php echo $delete_addlink; ?>" title="">(
                                    <?php echo Text::_('COM_REDSHOP_DELETE_LBL'); ?>)
                                </a>
                            </div>
                        <?php } ?>

                        <?php echo
                            RedshopLayoutHelper::render(
                                'modal.lightbox',
                                [
                                    'selector' => 'ModalAddShippingAddress',
                                    'params'   => [
                                        'buttonText'  => Text::_('COM_REDSHOP_ADD_ADDRESS'),
                                        'buttonClass' => 'btn btn-primary',
                                        'width'       => '800',
                                        'height'      => '',
                                        'url'         => $addAddLink,
                                    ]
                                ]
                            );
                        ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php
    if (Redshop::getConfig()->get('SHIPPING_METHOD_ENABLE') && $cart['free_shipping'] != 1) {
        echo $this->loadTemplate('shipping');
    }
    ?>

    <br />

    <div id="paymentblock">
        <?php echo $this->loadTemplate('payment'); ?>
    </div>
    <div class="clr"></div>
    <input type="hidden" name="option" value="com_redshop" />
    <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
    <input type="hidden" name="order_id" value="<?php echo JFactory::getApplication()->input->getInt('order_id'); ?>" />
    <input type="hidden" name="task" value="checkoutNext" />
    <input type="hidden" name="view" value="checkout" />

    <div align="right"><input type="submit" class="greenbutton btn btn-primary" name="checkoutNext"
            value="<?php echo Text::_("COM_REDSHOP_CHECKOUT") ?>" /></div>
</form>
