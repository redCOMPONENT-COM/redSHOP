<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHTML::_('behavior.tooltip');
$editor = JFactory::getEditor();
$model = $this->getModel('giftcard_detail');
$showbuttons = JRequest::getVar('showbuttons');
$producthelper = new producthelper();
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		submitbutton(pressbutton);
	}

	submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
			return;
		}

		if (form.giftcard_name.value == "") {
			alert("<?php echo JText::_('COM_REDSHOP_GIFTCARD_MUST_HAVE_A_NAME', true ); ?>");
		} else {
			submitform(pressbutton);
		}
	}

	//else if(form.giftcard_validity.value == "" || form.giftcard_validity.value == '0' ){
	//alert( "
	<?php //echo JText::_('COM_REDSHOP_GIFTCARD_MUST_HAVE_A_VALIDATE_PERIOD', true );?>" );
	//}
	//function amountValidation(amt)
	//{
	//
	//	if((amt == 0)){
	//		document.getElementById('showdiv').style.display = 'block';
	//	}else{
	//		document.getElementById('showdiv').style.display = 'none';
	//	}
	//
	//}
</script>
<?php
if (isset($showbuttons))
{
	?>
	<fieldset>
		<div style="float: right">
			<button type="button" onclick="submitbutton('save');">
				<?php echo JText::_('COM_REDSHOP_SAVE'); ?>
			</button>
			<button type="button" onclick="window.parent.SqueezeBox.close();">
				<?php echo JText::_('COM_REDSHOP_CANCEL'); ?>
			</button>
		</div>
	</fieldset>
<?php
}
?>
<form action="<?php echo JRoute::_($this->request_url) ?>" method="post" name="adminForm" id="adminForm"
      enctype="multipart/form-data">
	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DETAILS'); ?></legend>

			<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_GIFTCARD_NAME'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="giftcard_name" id="giftcard_name" size="32"
						       maxlength="250" value="<?php echo $this->detail->giftcard_name; ?>"/>
					</td>
				</tr>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_CUSTOMER_AMOUNT'); ?>:
					</td>
					<td>
						<?php

						echo $this->lists['customer_amount']; ?>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_GIFTCARD_PRICE'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="giftcard_price" id="giftcard_price" size="5"
						       maxlength="250"
						       value="<?php echo $producthelper->redpriceDecimal($this->detail->giftcard_price); ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_GIFTCARD_VALUE'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="text" name="giftcard_value" id="giftcard_value" size="5"
						       maxlength="250"
						       value="<?php echo $producthelper->redpriceDecimal($this->detail->giftcard_value); ?>"/>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<div id="showdiv">
							<table cellspacing="0" cellpadding="0" border="0" width="100%">
								<tr>
									<td width="100" align="right" class="key">
										<label for="name">
											<?php echo JText::_('COM_REDSHOP_GIFTCARD_VALIDITY'); ?>:
										</label>
									</td>
									<td>
										<input class="text_area" type="text" name="giftcard_validity"
										       id="giftcard_validity" size="5" maxlength="250"
										       value="<?php echo $this->detail->giftcard_validity; ?>"/>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<!--<tr>
			<td width="100" align="right" class="key">
				<label for="name">
					<?php echo JText::_('COM_REDSHOP_GIFTCARD_DATE' ); ?>:
				</label>
			</td>
			<td>
				<?php

					//echo JHTML::_('calendar',$this->detail->giftcard_date , 'giftcard_date', 'giftcard_date',$format = '%d-%m-%Y',array('class'=>'inputbox', 'size'=>'10',  'maxlength'=>'19')); ?>
			</td>
		</tr>
		-->
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_GIFTCARD_BGIMAGE'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="file" name="giftcard_bgimage" id="giftcard_bgimage" size="32"
						       maxlength="250" value="<?php echo $this->detail->giftcard_bgimage; ?>"/>
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<label for="name">
							<?php echo JText::_('COM_REDSHOP_GIFTCARD_IMAGE'); ?>:
						</label>
					</td>
					<td>
						<input class="text_area" type="file" name="giftcard_image" id="giftcard_image" size="32"
						       maxlength="250" value="<?php echo $this->detail->giftcard_image; ?>"/>
					</td>
				</tr>
				<?php
				if (ECONOMIC_INTEGRATION == 1)
				{
					?>
					<tr>
						<td valign="top" align="right" class="key">
							<?php echo JText::_('COM_REDSHOP_ECONOMIC_ACCOUNTGROUP_LBL'); ?>:
						</td>
						<td>
							<?php echo $this->lists['accountgroup_id']; ?>
						</td>
					</tr>
				<?php
				}
				?>
				<tr>
					<td valign="top" align="right" class="key">
						<?php echo JText::_('COM_REDSHOP_PUBLISHED'); ?>:
					</td>
					<td>
						<?php echo $this->lists['published']; ?>
					</td>
				</tr>

			</table>
		</fieldset>
	</div>

	<div class="col50">
		<fieldset class="adminform">
			<legend><?php echo JText::_('COM_REDSHOP_DESCRIPTION'); ?></legend>

			<table class="admintable">
				<tr>
					<td>
						<?php echo $editor->display("giftcard_desc", $this->detail->giftcard_desc, '$widthPx', '$heightPx', '100', '20');    ?>
					</td>
				</tr>
			</table>
		</fieldset>
	</div>

	<div class="clr"></div>
	<input type="hidden" name="giftcard_id" value="<?php echo $this->detail->giftcard_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="view" value="giftcard_detail"/>
</form>
