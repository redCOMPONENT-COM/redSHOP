<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$heading  = JText::_('COM_REDSHOP_PRODUCT_ORDERED_COMPANY');
$gobtn    = JText::_('COM_REDSHOP_CUSTOMVIEW_GO');

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'components/com_redshop/assets/css/search.css');
$document->addScript(JURI::base() . 'components/com_redshop/assets/js/search.js');

$cur_date = date('d-m-Y');
$maindate = JRequest::getVar('maindate', $cur_date);
$popup    = JRequest::getVar('popup');

if ($popup)
{
	$db = JFactory::getDbo();
	$print = JText::_('COM_REDSHOP_PRINT');
?>
	<style type="text/css">
		.checkout_attribute_title {
			font-weight: bold;
		}

		table.customviewcompany {
			width: 70%;
			border-spacing: 1px;
			background-color: #e7e7e7;
			color: #666;
		}

		table.customviewcompany td,
		table.customviewcompany th {
			padding: 4px;
		}

		table.customviewcompany thead th {
			text-align: center;
			background: #f0f0f0;
			color: #666;
			border-bottom: 1px solid #999;
			border-left: 1px solid #fff;
		}

		table.customviewcompany thead a:hover {
			text-decoration: none;
		}

		table.customviewcompany thead th img {
			vertical-align: middle;
		}

		table.customviewcompany tbody th {
			font-weight: bold;
		}

		table.customviewcompany tbody tr {
			background-color: #fff;
			text-align: left;
		}

		table.customviewcompany tbody tr.row1 {
			background: #f9f9f9;
			border-top: 1px solid #fff;
		}

		table.customviewcompany tbody tr.row0:hover td,
		table.customviewcompany tbody tr.row1:hover td {
			background-color: #ffd;
		}

		table.customviewcompany tbody tr td {
			height: 25px;
			background: #fff;
			border: 1px solid #fff;
		}

		table.customviewcompany tbody tr.row1 td {
			background: #f9f9f9;
			border-top: 1px solid #FFF;
		}

		table.customviewcompany tfoot tr {
			text-align: center;
			color: #333;
		}

		table.customviewcompany tfoot td,
		table.customviewcompany tfoot th {
			background-color: #f3f3f3;
			border-top: 1px solid #999;
			text-align: center;
		}

		table.customviewcompany td.order {
			text-align: center;
			white-space: nowrap;
		}

		table.customviewcompany td.order span {
			float: left;
			display: block;
			width: 20px;
			text-align: center;
		}

		table.customviewcompany .pagination {
			display: table;
			padding: 0;
			margin: 0 auto;
		}
	</style>

	<form
		action="index.php?json=1&tmpl=component&option=com_redshop&view=customprint&layout=customview&printoption=<?php echo JRequest::getVar('printoption'); ?>&print=1&popup=2"
		method="post" name="adminForm1" id="adminForm1">
		<table bgcolor="#98E273" align='center' width='80%' cellspacing='5' cellpadding='5'>
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
		<table align='center' cellspacing='3' cellpadding='3' width='80%'>
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

		</table>

		<?php
		$time = JRequest::getVar('maindate');
		$sel_shopper_group_name = "select * from #__redshop_shopper_group order by shopper_group_name asc";
		$db->setQuery($sel_shopper_group_name);
		$params_shopper_group_name = $db->loadObjectList();

		$main_cnt = 0;

		if (count($params_shopper_group_name) > 0)
		{
			for ($r = 0; $r < count($params_shopper_group_name); $r++)
			{
				$sel = "select o.*,p.*,u.*,fd.*,GROUP_CONCAT( CONCAT_WS(' x ',product_quantity,product_name),product_attribute SEPARATOR ' - ' ) as nq from #__redshop_order_item o left outer join #__redshop_product p on o.product_id=p.product_id left outer join #__redshop_users_info u on u.users_info_id=o.user_info_id left outer join  #__redshop_fields_data fd on o.order_item_id=fd.itemid where data_txt = '" . $time . "' and fd.section =12 and u.shopper_group_id = '" . $params_shopper_group_name[$r]->shopper_group_id . "'  group by  o.user_info_id order by u.firstname desc";
				$db->setQuery($sel);
				$params = $db->loadObjectList();

				if (count($params) > 0)
				{
					?>
					<table align='center' border="0" width="100%">
						<tr>
							<td>
								<h3 style="padding-left:50px;"><?php echo $params_shopper_group_name[$r]->shopper_group_name;?></h3>
							</td>
						</tr>
					</table>
					<table align='center' class='customviewcompany'>

						<tr>
							<th valign="top" align="left" class="title">
								<b><?php echo JText::_('COM_REDSHOP_NAME');?></b>
							</th>
							<th valign="top" align="left" class="title">
								<b><?php echo JText::_('COM_REDSHOP_USER_COMPANY');?></b>
							</th>
							<th valign="top" align="left" class="title">
								<b><?php echo JText::_('COM_REDSHOP_ORDER');?></b>
							</th>
						</tr>
						<?php
						for ($i = 0; $i < count($params); $i++)
						{
							?>
							<tr>
								<td valign="top" align='left'>
									<?php echo $params[$i]->firstname . "&nbsp;" . $params[$i]->lastname;?>
								</td>
								<td valign="top" align='left'>
									<?php
									if ($params[$i]->company_name == "")
									{
										echo "-";
									}
									else
									{
										echo $params[$i]->company_name;
									}?>
								</td>
								<td valign="top" align='left' width='60%'>
									<?php echo $params[$i]->nq;?>
								</td>

							</tr>
						<?php
						}
						?>

					</table>
				<?php
				}
			}
		}
		?>
		<table align='center' width='80%' cellspacing='2' cellpadding='2'>
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

		<table bgcolor="#98E273" align='center' width='80%' cellspacing='5' cellpadding='5'>
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

			<table class="adminlist">
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
						<input type="button" value="<?php echo $gobtn ?>" name="printall"
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
	<script>

		var useroptions = {
			script: "index.php?&option=com_redshop&view=search&tmpl=component&plgcustomview=1&iscompany=1&json=true&",
			varname: "input",
			json: true,
			shownoresults: true,
			callback: function (obj) {
				document.getElementById('user_id').value = obj.id;
			}
		};

		var as_json = new bsn.AutoSuggest('searchusernames', useroptions);
	</script>
<?php
}
