<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');

$heading  = JText::_('COM_REDSHOP_PRODUCT_ORDERED_DATE');
$gobtn    = JText::_('COM_REDSHOP_CUSTOMVIEW_GO');
$cur_date = date('d-m-Y');
$maindate = JRequest::getVar('maindate', $cur_date);
$popup    = JRequest::getVar('popup');

if ($popup)
{
	$db = JFactory::getDbo();
	$heading = JText::_('COM_REDSHOP_PRODUCT_ORDERED_DATE');
	$print = JText::_('COM_REDSHOP_PRINT');
	$sel = "select o.*,fd.*,p.* from #__redshop_fields_data fd left outer join  #__redshop_order_item o on o.order_item_id=fd.itemid  left outer join  #__redshop_product p on o.product_id=p.product_id where fd.section=12 order by o.cdate desc";
	$db->setQuery($sel);
	$params = $db->loadObjectList();


	?>
	<style type="text/css">
		.checkout_attribute_title {
			font-weight: bold;
		}
	</style>
	<form
		action="index.php?json=1&tmpl=component&option=com_redshop&view=customprint&layout=customview&printoption=<?php echo JRequest::getVar('printoption'); ?>&print=1&popup=2"
		method="post" name="adminForm1" id="adminForm1">
		<table bgcolor="#FE9695" align='center' width='60%' cellspacing='5' cellpadding='5'>
			<tr>
				<td valign="top" align="left">
					<h1 style="color: black;">
						<?php echo $heading;?>
					</h1>
				</td>
				<td valign="top" align="left">

				</td>
			</tr>
		</table>
		<table align='center' cellspacing='3' cellpadding='3' width='60%'>
			<tr>
				<td valign="top" colspan='3' align='right'>
					<?php
					if ($popup == 2)
					{
						?>
						<script language="javascript">window.print();</script>
						<b>Date:</b> <?php echo JRequest::getVar('maindate'); ?>     &nbsp;
						<input type="hidden" value="<?php echo $print ?>" name="printall" onclick="javascript:window.print();"/>
				<?php
					}
					else
					{
				?>
						<input type="submit" value="<?php echo $print ?>" name="printall" onclick="return printbutton('printall');"/>
				<?php
					}
				?>
				</td>
			</tr>
			<?php
			$main_cnt = 0;

			if (count($params) > 0)
			{
				for ($r = 0; $r < count($params); $r++)
				{
					if ($params[$r]->data_txt == JRequest::getVar('maindate'))
					{
						?>
						<tr>
							<td valign="top" align='left' style="padding-left: 80px;">
								<?php echo $params[$r]->product_name . "<br />" . $params[$r]->product_attribute;?>
							</td>
							<td valign="top" align='left' width='50%'>
								<?php echo $params[$r]->product_quantity;?>
							</td>

						</tr>
						<?php
						$main_cnt++;
					}
				}
			}

			if ($main_cnt == 0)
			{
				?>
				<tr>
					<td valign="top" align="center" colspan='3'>
						No Records
					</td>
				</tr>
			<?php
			}
			?>

		</table>
		<table width='60%' cellspacing='2' cellpadding='2' align='center'>
			<tr>
				<td valign="top" align="left" colspan='2'>
					&nbsp;
				</td>
			</tr>
			<tr>
				<td valign="top" align="left" colspan='2'>
					&nbsp;
				</td>
			</tr>

		</table>
		<table bgcolor="#FE9695" align='center' width='60%' cellspacing='5' cellpadding='5'>
			<tr>
				<td valign="top" align="left" colspan='2'>
					&nbsp;
				</td>
			</tr>
			<tr>
				<td valign="top" align="left" colspan='2'>
					&nbsp;
				</td>
			</tr>

		</table>

		<div class="clr"></div>
		<input type="hidden" name="task" value="<?php echo JRequest::getVar('printoption'); ?>"/>
		<input type="hidden" name="printoption" value="<?php echo JRequest::getVar('printoption'); ?>"/>
		<input type="hidden" name="maindate" value="<?php echo JRequest::getVar('maindate'); ?>"/>
		<input type="hidden" name="view" value="customprint"/>
	</form>
<?php
}
else
{
	?>
	<form
		action="index.php?option=com_redshop&view=customprint&layout=customview&printoption=<?php echo JRequest::getVar('printoption'); ?>&popup=1"
		method="post" name="adminForm" id="adminForm">

		<div class="col50">

			<table class="adminlist" align='center'>
				<tr>
					<td valign="top" align="left" colspan='2'>
						<h1>
							<?php echo $heading;?>
						</h1>
					</td>
				</tr>
				<tr>
					<td valign="top" align="left" colspan='2'>
						<?php echo JHTML::_('calendar', $maindate, 'maindate', 'maindate', $format = '%d-%m-%Y', array('class' => 'inputbox', 'size' => '10', 'maxlength' => '15'));?>
					</td>
				</tr>
				<tr>
					<td valign="top" align="left" colspan='2'>
						<input type="submit" value="<?php echo $gobtn ?>" name="printall"
						       onclick="return submitbutton('printall');"/>
					</td>
				</tr>
			</table>
		</div>
		<div class="clr"></div>
		<input type="hidden" name="task" value="<?php echo JRequest::getVar('printoption'); ?>"/>
		<input type="hidden" name="printoption" value="<?php echo JRequest::getVar('printoption'); ?>"/>
		<input type="hidden" name="view" value="customprint"/>
	</form>
<?php
}
