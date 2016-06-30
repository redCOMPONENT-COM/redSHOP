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

$ccdata = JFactory::getSession()->get('ccdata');

$url                      = JURI::base(true);
$cc_list                  = array();
$cc_list['VISA']          = new stdClass;
$cc_list['VISA']->img     = 'visa.jpg';
$cc_list['MC']            = new stdClass;
$cc_list['MC']->img       = 'master.jpg';
$cc_list['amex']          = new stdClass;
$cc_list['amex']->img     = 'blue.jpg';
$cc_list['maestro']       = new stdClass;
$cc_list['maestro']->img  = 'mastero.jpg';
$cc_list['jcb']           = new stdClass;
$cc_list['jcb']->img      = 'jcb.jpg';
$cc_list['diners']        = new stdClass;
$cc_list['diners']->img   = 'dinnersclub.jpg';
$cc_list['discover']      = new stdClass;
$cc_list['discover']->img = 'discover.jpg';

$months   = array();
$months[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_MONTH'));
$months[] = JHTML::_('select.option', '01', 1);
$months[] = JHTML::_('select.option', '02', 2);
$months[] = JHTML::_('select.option', '03', 3);
$months[] = JHTML::_('select.option', '04', 4);
$months[] = JHTML::_('select.option', '05', 5);
$months[] = JHTML::_('select.option', '06', 6);
$months[] = JHTML::_('select.option', '07', 7);
$months[] = JHTML::_('select.option', '08', 8);
$months[] = JHTML::_('select.option', '09', 9);
$months[] = JHTML::_('select.option', '10', 10);
$months[] = JHTML::_('select.option', '11', 11);
$months[] = JHTML::_('select.option', '12', 12);

?>
<?php
	JPluginHelper::importPlugin('redshop_payment');
	JEventDispatcher::getInstance()->trigger('onListCreditCards', array('selectable' => true));
?>
<fieldset class="adminform">
	<legend>
		<input type="radio" name="selectedCard" value="" checked="checked">
		<?php echo JText::_('COM_REDSHOP_CARD_INFORMATION'); ?>
	</legend>
	<div class="credit-card-form">
		<div class="control-group">
			<div class="controls">
				<?php
				$cardTypes = array();
				$credictCard = $pluginParams->get("accepted_credict_card", array());

				for ($ic = 0, $nic = count($credictCard); $ic < $nic; $ic++)
				{
					$url = REDSHOP_FRONT_IMAGES_ABSPATH . 'checkout/' . $cc_list[$credictCard[$ic]]->img;
					$text = '<img src="' . $url . '" alt="" border="0" />';
					$cardTypes[] = JHtml::_('select.option', $credictCard[$ic], $text);
				}

				echo JHtml::_('redshopselect.radiolist', $cardTypes, 'creditcard_code', '', 'value', 'text', $ccdata['creditcard_code']);
				?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="order_payment_name">
				<?php echo JText::_('COM_REDSHOP_NAME_ON_CARD'); ?>
			</label>
			<div class="controls">
				<?php $order_payment_name = (!empty($ccdata['order_payment_name'])) ? $ccdata['order_payment_name'] : ""; ?>
				<input
					class="input-medium"
					type="text"
					placeholder="<?php echo JText::_('COM_REDSHOP_NAME_ON_CARD'); ?>"
					id="order_payment_name"
					name="order_payment_name"
					value="<?php echo $order_payment_name; ?>"
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
				$order_payment_number = (!empty($ccdata['order_payment_number'])) ? $ccdata['order_payment_number'] : "";
				?>
				<input
					class="input-medium"
					type="text"
					placeholder="<?php echo JText::_('COM_REDSHOP_CARD_NUM'); ?>"
					id="order_payment_number"
					name="order_payment_number"
					value="<?php echo $order_payment_number; ?>"
					autocomplete="off"
				/>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="order_payment_expire_month"><?php echo JText::_('COM_REDSHOP_EXPIRY_DATE'); ?></label>
			<div class="controls">
				<?php
					echo JHTML::_(
						'select.genericlist',
						$months,
						'order_payment_expire_month',
						'size="1" class="input-small" ',
						'value',
						'text',
						$ccdata['order_payment_expire_month']
					);

					$thisyear = date('Y');
					$years = array();

					for ($y = $thisyear; $y < ($thisyear + 10); $y++)
					{
						$years[] = JHtml::_('select.option', $y, $y);
					}

					echo JHTML::_(
						'select.genericlist',
						$years,
						'order_payment_expire_year',
						'size="1" class="input-small" ',
						'value',
						'text',
						$ccdata['order_payment_expire_year']
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
				$credit_card_code = (!empty($ccdata['credit_card_code'])) ? $ccdata['credit_card_code'] : "";
				?>
				<input
					class="input-mini"
					type="password"
					placeholder="<?php echo JText::_('COM_REDSHOP_CARD_SECURITY_CODE'); ?>"
					maxlength="4"
					id="credit_card_code"
					name="credit_card_code"
					value="<?php echo $credit_card_code; ?>"
					autocomplete="off"
				/>
			</div>
		</div>
	</div>
</fieldset>
