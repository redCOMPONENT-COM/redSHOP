<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$jinput = JFactory::getApplication()->input;

$post = $jinput->post->getArray();

$filter        = $jinput->get('filter');
$number_order  = $jinput->get('number_order', '');
$model         = $this->getModel('newsletter');
$cid           = $jinput->post->get('cid', array(0), 'array');
$newsletter_id = $jinput->get('newsletter_id');
$oprand        = $jinput->get('oprand', 'select');
$total_start   = $jinput->get('total_start', '');
$total_end     = $jinput->get('total_end', '');
$zipstart      = $jinput->get('zipstart', '');
$zipend        = $jinput->get('zipend', '');
$start         = $jinput->get('start_date', '');
$end           = $jinput->get('end_date', '');
$cityfilter    = $jinput->get('cityfilter', '');

if (isset($post['checkoutshoppers']))
{
	$checked = 'checked="checked"';
}
else
{
	$checked = "";
}
?>
<script language="javascript" type="text/javascript">
	function clearreset() {

		var form = document.adminForm;

		form.zipstart.value = "";
		form.zipend.value = "";
		form.number_order.value = "";
		form.oprand.value = "select";
		form.total_start.value = "";
		form.total_end.value = "";
		form.start_date.value = "";
		form.end_date.value = "";
		form.cityfilter.value = "";
		form.country.value = "";
		form.shoppergroups.value = "";
		form.checkoutshoppers.checked = false;


		form.submit();
	}
</script>

<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="view" value="newsletter"/>
	<input type="hidden" name="task" value="send_newsletter_preview"/>
	<input type="hidden" name="newsletter_id" value="<?php if ($cid[0] != "") echo $cid[0];
	else echo $newsletter_id; ?>"/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>

	<div id="editcell">
		<div>
			<fieldset>
				<legend><?php echo JText::_('COM_REDSHOP_USER_FILTER');?></legend>
				<table class="adminList" cellpadding="3" cellspacing="0" border="0">
					<tr>
						<td><strong><?php echo JText::_('COM_REDSHOP_NUMBER_ODERS'); ?>:</strong></td>
						<td colspan="2"><?php echo $this->lists['oprand'];?>&nbsp;<input type="text" name="number_order"
						                                                                 id="number_order" size="5"
						                                                                 value="<?php echo $number_order; ?>">
						</td>
					</tr>
					<tr>
						<td><strong><?php echo JText::_('COM_REDSHOP_ODERS_TOTAL'); ?>:</strong></td>
						<td><label for="totalstart"><?php echo JText::_('COM_REDSHOP_START'); ?>
								:&nbsp;&nbsp;</label><input type="text" name="total_start" id="total_start" size="5"
						                                    value="<?php echo $total_start; ?>"></td>
						<td><label for="totalend"><?php echo JText::_('COM_REDSHOP_END'); ?>:&nbsp;&nbsp;</label><input
								type="text" name="total_end" id="total_end" size="5" value="<?php echo $total_end; ?>">
						</td>
					</tr>
					<tr>
						<td><strong><?php echo JText::_('COM_REDSHOP_ZIPCODE'); ?>:</strong></td>
						<td><label for="zipstart"><?php echo JText::_('COM_REDSHOP_START'); ?>
								:&nbsp;&nbsp;</label><input type="text" name="zipstart" id="zipstart" size="5"
						                                    value="<?php echo $zipstart; ?>"></td>
						<td><label for="zipend"><?php echo JText::_('COM_REDSHOP_END'); ?>:&nbsp;&nbsp;</label><input
								type="text" name="zipend" id="zipend" size="5" value="<?php echo $zipend; ?>"></td>
					</tr>
					<tr>
						<td><strong><?php echo JText::_('COM_REDSHOP_FILTER_CUSTOMER_BY');?>:</strong></td>
						<td><input type="text" name="cityfilter" id="cityfilter" value="<?php echo $cityfilter; ?>"
						           size="13"></td>
					</tr>
					<tr>
						<td><strong><?php echo JText::_('COM_REDSHOP_HOW_LONG_USER'); ?>:</strong></td>
						<td valign="middle"><?php echo JText::_('COM_REDSHOP_START'); ?>
							:&nbsp;<?php echo JHTML::_('calendar', $start, 'start_date', 'start_date', $format = '%Y-%m-%d', array('class' => 'inputbox', 'size' => '16', 'maxlength' => '19')); ?></td>
						<td><?php echo JText::_('COM_REDSHOP_END'); ?>
							:&nbsp;<?php echo JHTML::_('calendar', $end, 'end_date', 'end_date', $format = '%Y-%m-%d', array('class' => 'inputbox', 'size' => '16', 'maxlength' => '19')); ?></td>

					</tr>
					<tr>
						<td><strong><?php echo JText::_('COM_REDSHOP_CATEGORY'); ?>:</strong></td>
						<td colspan="2"><?php echo $this->lists['categories'];?></td>
					</tr>
					<tr>
						<td><strong><?php echo JText::_('COM_REDSHOP_PRODUCT'); ?>:</strong></td>
						<td colspan="2" valign="top"><?php echo $this->lists['product'];?></td>
					</tr>
					<tr>
						<td><strong><?php echo JText::_('COM_REDSHOP_COUNTRY'); ?>:</strong></td>
						<td colspan="2"><?php echo $this->lists['country'];?></td>
					</tr>
					<tr>
						<td><strong><?php echo JText::_('COM_REDSHOP_SHOPPER_GROUP'); ?>:</strong></td>
						<td colspan="2"><?php echo $this->lists['shoppergroups'];?></td>
					</tr>
					<tr>
						<td><strong><?php echo JText::_('COM_REDSHOP_CHECKOUT_SHOPPER'); ?>:</strong></td>
						<td colspan="2"><input type="checkbox" <?php echo $checked;?> name="checkoutshoppers"/></td>
					</tr>
					<tr>
						<td colspan="3" align="right">
							<input type="submit" onclick="document.adminForm.submit();"
							       value="<?php echo JText::_('COM_REDSHOP_GO'); ?>">
							<input type="reset" name="reset" id="reset"
							       value="<?php echo JText::_('COM_REDSHOP_RESET'); ?>" onclick="return clearreset();">
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
		<div>&nbsp;</div>
		<div>
			<table cellpadding="0" cellspacing="0" border="0" class="adminList">
				<tr>
					<td align="left">
						<?php echo JHtml::_('redshopgrid.checkall'); ?><strong
							style="vertical-align: baseline;"><?php echo JText::_('COM_REDSHOP_SELECT_ALL'); ?></strong>
					</td>
				</tr>
			</table>
			<br/>
			<table cellpadding="2" cellspacing="0" border="0" class="adminList">
				<?php
				$k = 0;
				for ($i = 0, $n = count($this->subscribers); $i < $n; $i++)
				{
					$row = $this->subscribers[$i];

					$row->id = $row->subscription_id;

					$cond = $model->order_user($row->user_id);

					$category = $model->category($row->user_id);

					$product = $model->product($row->user_id);

					if ($cond != 0 && ($category != 0 && $product != 0))
					{

						if ($i % 4 == 0) echo '<tr>';
						?>
						<td width="5">
							<?php echo JHTML::_('grid.id', $i, $row->id); ?>

						</td>
						<td>
							<?php
							if ($row->username != NULL)
							{
								echo $uname = $row->firstname . " " . $row->lastname . " (" . $row->username . ")";
							}
							else
							{
								echo $uname = $row->name;
							}

							?>
						</td>
						<?php
						if (($i + 1) % 4 == 0) echo '</tr>';

					}


				}
				?>
			</table>
		</div>
	</div>
</form>
