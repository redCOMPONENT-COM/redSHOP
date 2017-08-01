<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
$producthelper = productHelper::getInstance();


$editor = JFactory::getEditor();
JHTML::_('behavior.tooltip');
$user = JFactory::getUser();
$url = JURI::base();

$model = $this->getModel('coupon_detail');

$date = JFactory::getDate();
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.coupon_type.value == 1 && form.userid.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_YOU_MUST_LINK_COUPON_TO_USER', true ); ?>");
			return false;
		}
		else {
			submitform(pressbutton);
		}
	};
	jQuery(function($) {
		var userId = $("#userid");
		var couponType = $('#coupon_type');
		couponType.on('change', function () {
			if ($(this).val() != 1){
				userId.select2("val", "").prop("disabled", true);
			}else{
				userId.prop("disabled", false);
			}
		});
		if (couponType.val() != 1){
			userId.select2("val", "").prop("disabled", true);
		}
	});
</script>

<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable table">
				<tr>
					<td valign="top" align="right" class="key">
						<label for="volume">
							<?php echo JText::_('COM_REDSHOP_COUPON_CODE'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="coupon_code" id="coupon_code" size="75"
						       maxlength="250" value="<?php echo $this->detail->coupon_code; ?>"/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="volume">
							<?php echo JText::_('COM_REDSHOP_PERCENTAGE_OR_TOTAL'); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->lists['percent_or_total']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="volume">
							<?php echo JText::_('COM_REDSHOP_COUPON_VALUE'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="coupon_value" id="coupon_value" size="20"
						       value="<?php echo  $producthelper->redpriceDecimal($this->detail->coupon_value); ?>"/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_COUPON_START_DATE'); ?>:
						</label>
					</td>
					<td>
						<?php
						if ($this->detail->start_date)
							$datee = date("d-m-Y", $this->detail->start_date);
						else
							$datee = date("d-m-Y");
						echo JHTML::_('calendar', $datee, 'start_date', 'start_date', $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => '32', 'maxlength' => '19')); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="deliverytime">
							<?php echo JText::_('COM_REDSHOP_COUPON_END_DATE'); ?>:
						</label>
					</td>
					<td>
						<?php
						if ($this->detail->end_date)
							$datee = date("d-m-Y", $this->detail->end_date);
						else
							$datee = date("d-m-Y");
						echo JHTML::_('calendar', $datee, 'end_date', 'end_date', $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => '32', 'maxlength' => '19')); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="volume">
							<?php echo JText::_('COM_REDSHOP_COUPON_TYPE'); ?>:
						</label>
					</td>
					<td>
						<?php echo $this->lists['coupon_type']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="volume">
							<?php echo JText::_('COM_REDSHOP_SUBTOTAL_LBL'); ?>:
						</label>
					</td>
					<td>
						<input type="text" name="subtotal" id="subtotal" value="<?php echo $this->detail->subtotal; ?>"
						       size="5"/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_USER'); ?>:
					</td>
					<td>
						<?php
						$value = new stdClass;

						if ($this->detail->coupon_type == 1)
						{
							$value->value = $this->detail->userid;
							$value->text = $model->getuserfullname2($this->detail->userid);
						}

						echo JHTML::_('redshopselect.search', $value, 'userid',
							array(
								'select2.ajaxOptions' => array('typeField' => ', user:1'),
								'select2.options' => array('placeholder' => JText::_('COM_REDSHOP_USER'))
							)
						);
						?>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_COUPON_USER'), JText::_('COM_REDSHOP_USER'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_FREE_SHIPPING'); ?>:
					</td>
					<td>
						<?php echo $this->lists['free_shipping']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<label for="volume">
							<?php echo JText::_('COM_REDSHOP_LBL_COUPON_LEFT'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="coupon_left" id="coupon_left" size="20"
						       value="<?php echo $this->detail->coupon_left; ?>"/>
						<?php echo JHTML::tooltip(JText::_('COM_REDSHOP_TOOLTIP_COUPON_LEFT'), JText::_('COM_REDSHOP_LBL_COUPON_LEFT'), 'tooltip.png', '', '', false); ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
					</td>
					<td>
						<?php echo $this->lists['published']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_COUPON_REMAINING_AMOUNT'); ?>:
					</td>
					<td>
						<?php echo $model->getRemainingCouponAmount(); ?>
					</td>
				</tr>


			</table>
		</fieldset>
	</div>

	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo $this->detail->coupon_id; ?>"/>
	<input type="hidden" name="old_coupon_code" value="<?php echo $this->detail->coupon_code; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="coupon_detail"/>
</form>
