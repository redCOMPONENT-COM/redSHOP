<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Layout variables
 * ==========================
 * @var  array                    $displayData Available data.
 * @var  Joomla\Registry\Registry $params      Plugin params.
 * @var  array                    $data        Plugin data.
 */
extract($displayData);

$input  = JFactory::getApplication()->input;
$itemId = $input->getInt('Itemid', 0);

JFactory::getDocument()->addScript('https://bridge.paymill.com/');

JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_CARD_NUMBER');
JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_EXPIRATION_DATE');
JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_CARD_HOLDERNAME');
JText::script('PLG_RS_PAYMENT_PAYMILL_INVALID_CARD_CVC');

$action = JUri::base() . 'index.php?option=com_redshop&view=order_detail&layout=checkout_final&stap=2'
	. '&oid=' . $data['order_id'] . '&Itemid=' . $itemId;
?>
<script type="text/javascript">
    var PAYMILL_PUBLIC_KEY = "<?php echo $params->get('paymill_public_key', '0') ?>";
    var VALIDATE_CVC       = true;
</script>
<div class="containerPaymil">
    <div class="well">
        <div class="payment_errors text-error">&nbsp;</div>
        <form id="payment-form" method="POST" action="<?php echo JRoute::_($action, false) ?>">
            <div class="clearfix"></div>
            <div id="payment-form-cc">
                <input class="card-amount-int" type="hidden" value="<?php echo $data['order']->order_total ?>" name="amount" />
                <input class="card-currency" type="hidden" value="<?php echo Redshop::getConfig()->get('CURRENCY_CODE') ?>" name="currency"/>
                <div class="row-fluid">
                    <div class="span4">
                        <label for="card-number"><?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_CARD_NUMBER'); ?></label>
                        <input class="card-number span12" id="card-number" type="text" size="19" value=""
                                placeholder="<?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_CARD_NUMBER_PLACEHOLDER'); ?>" maxlength="19"/>
                    </div>
                    <div class="span2">
                        <label><?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_VALID_UNTIL'); ?></label>
                        <input id="card-expiry" class="card-expiry span12" type="text"
                                placeholder="<?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_CARD_EXPIRY_PLACEHOLDER'); ?>" maxlength="7">
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span4">
                        <label><?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_CARD_HOLDER'); ?></label>
                        <input class="card-holdername span12" type="text" size="20" value=""/>
                    </div>
                    <div class="span2">
                        <label>
							<?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_CVC'); ?>
							<?php echo JHtml::tooltip(JText::_('PLG_RS_PAYMENT_PAYMILL_CVC_TIP')); ?>
                        </label>
                        <input class="card-cvc span12" type="text" size="4" maxlength="4" value=""/>
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <button id="paymill-submit-button" class="submit-button btn btn-primary" type="submit">
					<?php echo JText::_('PLG_RS_PAYMENT_PAYMILL_BUY_NOW'); ?>
                </button>
            </div>
            <input type="hidden" name="option" value="com_redshop"/>
            <input type="hidden" name="Itemid" value="<?php echo $itemId; ?>"/>
            <input type="hidden" name="ccinfo" value="1"/>
            <input type="hidden" name="payment_method_id" value="<?php echo $input->get('payment_method_id', ''); ?>"/>
            <input type="hidden" name="order_id" value="<?php echo $data['order_id']; ?>"/>
        </form>
    </div>
</div>
