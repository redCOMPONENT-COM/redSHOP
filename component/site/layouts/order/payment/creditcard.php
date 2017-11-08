<?php
/**
 * @package     Redshop.Layouts
 * @subpackage  Order.Payment
 * @copyright   Copyright (C) 2008-2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;

extract($displayData);

JHtml::_('formbehavior.chosen', 'select');

$creditCardData = JFactory::getSession()->get('ccdata');

$url                             = JURI::base(true);
$creditCardList                  = array();
$creditCardList['VISA']          = new stdClass;
$creditCardList['VISA']->img     = 'visa.jpg';
$creditCardList['MC']            = new stdClass;
$creditCardList['MC']->img       = 'master.jpg';
$creditCardList['amex']          = new stdClass;
$creditCardList['amex']->img     = 'blue.jpg';
$creditCardList['maestro']       = new stdClass;
$creditCardList['maestro']->img  = 'mastero.jpg';
$creditCardList['jcb']           = new stdClass;
$creditCardList['jcb']->img      = 'jcb.jpg';
$creditCardList['diners']        = new stdClass;
$creditCardList['diners']->img   = 'dinnersclub.jpg';
$creditCardList['discover']      = new stdClass;
$creditCardList['discover']->img = 'discover.jpg';

$months   = array();
$months[] = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_MONTH'));
$months[] = JHtml::_('select.option', '01', 1);
$months[] = JHtml::_('select.option', '02', 2);
$months[] = JHtml::_('select.option', '03', 3);
$months[] = JHtml::_('select.option', '04', 4);
$months[] = JHtml::_('select.option', '05', 5);
$months[] = JHtml::_('select.option', '06', 6);
$months[] = JHtml::_('select.option', '07', 7);
$months[] = JHtml::_('select.option', '08', 8);
$months[] = JHtml::_('select.option', '09', 9);
$months[] = JHtml::_('select.option', '10', 10);
$months[] = JHtml::_('select.option', '11', 11);
$months[] = JHtml::_('select.option', '12', 12);

?>
<?php
JPluginHelper::importPlugin('redshop_payment');
RedshopHelperUtility::getDispatcher()->trigger('onListCreditCards', array('selectable' => true));
?>
<fieldset class="adminform">
    <legend>
        <input type="radio" name="selectedCard" value="" checked="checked">
		<?php echo JText::_('COM_REDSHOP_CARD_INFORMATION') ?>
    </legend>
    <div class="credit-card-form">
        <div class="control-group">
            <div class="controls">
				<?php
				$cardTypes  = array();
				$creditCard = $pluginParams->get("accepted_credict_card", array());

				for ($ic = 0, $nic = count($creditCard); $ic < $nic; $ic++)
				{
					$url         = REDSHOP_FRONT_IMAGES_ABSPATH . 'checkout/' . $creditCardList[$creditCard[$ic]]->img;
					$text        = '<img src="' . $url . '" alt="" border="0" />';
					$cardTypes[] = JHtml::_('select.option', $creditCard[$ic], $text);
				}

				echo JHtml::_('redshopselect.radiolist', $cardTypes, 'creditcard_code', array('cssClassSuffix' => ''), 'value', 'text', $creditCardData['creditcard_code']);
				?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="order_payment_name">
				<?php echo JText::_('COM_REDSHOP_NAME_ON_CARD'); ?>
            </label>
            <div class="controls">
				<?php $orderPaymentName = (!empty($creditCardData['order_payment_name'])) ? $creditCardData['order_payment_name'] : ""; ?>
                <input
                        class="input-medium"
                        type="text"
                        placeholder="<?php echo JText::_('COM_REDSHOP_NAME_ON_CARD'); ?>"
                        id="order_payment_name"
                        name="order_payment_name"
                        value="<?php echo $orderPaymentName; ?>"
                        autocomplete="off"
                />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="order_payment_number">
				<?php echo JText::_('COM_REDSHOP_CARD_NUM'); ?>
            </label>
            <div class="controls">
				<?php
				$orderPaymentNumber = (!empty($creditCardData['order_payment_number'])) ? $creditCardData['order_payment_number'] : "";
				?>
                <input
                        class="input-medium"
                        type="text"
                        placeholder="<?php echo JText::_('COM_REDSHOP_CARD_NUM'); ?>"
                        id="order_payment_number"
                        name="order_payment_number"
                        value="<?php echo $orderPaymentNumber; ?>"
                        autocomplete="off"
                />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="order_payment_expire_month"><?php echo JText::_('COM_REDSHOP_EXPIRY_DATE'); ?></label>
            <div class="controls">
				<?php
				echo JHtml::_(
					'select.genericlist',
					$months,
					'order_payment_expire_month',
					'size="1" class="input-small" ',
					'value',
					'text',
					$creditCardData['order_payment_expire_month']
				);

				$currentYear = date('Y');
				$years       = array();

				for ($y = $currentYear; $y < ($currentYear + 10); $y++)
				{
					$years[] = JHtml::_('select.option', $y, $y);
				}

				echo JHtml::_(
					'select.genericlist',
					$years,
					'order_payment_expire_year',
					'size="1" class="input-small" ',
					'value',
					'text',
					$creditCardData['order_payment_expire_year']
				);
				?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="credit_card_code">
				<?php echo JText::_('COM_REDSHOP_CARD_SECURITY_CODE'); ?>
            </label>
            <div class="controls">
				<?php
				$creditCardCode = (!empty($creditCardData['credit_card_code'])) ? $creditCardData['credit_card_code'] : "";
				?>
                <input
                        class="input-mini"
                        type="password"
                        placeholder="<?php echo JText::_('COM_REDSHOP_CARD_SECURITY_CODE'); ?>"
                        maxlength="4"
                        id="credit_card_code"
                        name="credit_card_code"
                        value="<?php echo $creditCardCode ?>"
                        autocomplete="off"
                />
            </div>
        </div>
    </div>
</fieldset>
