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
$redhelper     = redhelper::getInstance();
$userhelper    = rsUserHelper::getInstance();
$filter        = JFactory::getApplication()->input->get('filter');
$model         = $this->getModel('user');
?>
<script language="javascript" type="text/javascript">
	Joomla.submitbutton = function (pressbutton) {
		var form = document.adminForm;
		if (pressbutton) {
			form.task.value = pressbutton;
		}
		if (pressbutton == 'add')
		{
			<?php
				$link = RedshopHelperUtility::getSSLLink('index.php?option=com_redshop&view=user_detail');
			?>

			window.location = '<?php echo $link;?>';
			return;
		}
		else if (
			pressbutton == 'edit' || pressbutton == 'publish' || pressbutton == 'unpublish' || pressbutton == 'remove' || pressbutton == 'copy'
		) {
			if (pressbutton == 'remove' && confirm("<?php echo JText::_('COM_REDSHOP_CONFIRM_DELETE_RESPECTIVE_JOOMLA_USERS'); ?>"))
			{
				form.delete_joomla_users.value = true;
			}

			form.view.value = "user_detail";
		}
		try {
			form.onsubmit();
		}
		catch (e) {
		}
		form.submit();
	}

	resetfilter = function()
	{
		document.getElementById('filter').value = '';
		document.getElementById('tax_exempt_request_filter').value = 'select';
		document.getElementById('spgrp_filter').value = '0';
		document.adminForm.submit();
	}
</script>

<form action="index.php?option=com_redshop" method="post" name="adminForm" id="adminForm">
	<div class="filterTool">
		<div class="filterItem">
			<div class="btn-wrapper input-append">
				<input
					type="text"
					class="input-medium"
					name="filter"
					id="filter"
					value="<?php echo $this->state->get('filter'); ?>"
					placeholder="<?php echo JText::_('COM_REDSHOP_USER_FILTER');?>"
					>
				<input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
				<input type="button" class="btn reset" onclick="resetfilter();" value="<?php echo JText::_('COM_REDSHOP_RESET');?>"/>

			</div>
		</div>

		<div class="filterItem">
			<?php echo JText::_('COM_REDSHOP_SHOPPERGRP_FILTER');?>
			<?php echo $this->lists ['shopper_group'];?>
		</div>
		<div class="filterItem">
			<?php echo JText::_('COM_REDSHOP_TAX_EXEMPT_REQUESTED');?>
			<?php echo $this->lists ['tax_exempt_request'];?>
		</div>
	</div>
	<div id="editcell">
		<table class="adminlist table table-striped">
			<thead>
			<tr>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_NUM');?></th>
				<th width="5%"><?php echo JHtml::_('redshopgrid.checkall'); ?></th>
				<th class="title"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_FIRST_NAME', 'firstname', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th class="title"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_LAST_NAME', 'lastname', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th class="title"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_REGISTER_AS', 'is_company', $this->lists ['order_Dir'], $this->lists ['order']); ?></th>
				<th class="title"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_USERNAME', 'username', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th class="title"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_SHOPPER_GROUP_NAME', 'shopper_group_id', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
				<th width="5%"><?php echo JText::_('COM_REDSHOP_CUSTOMER_SALES'); ?></th>
				<th width="5%"
				    nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'users_info_id', $this->lists ['order_Dir'], $this->lists ['order']);?></th>
			</tr>
			</thead>
			<?php
			$k = 0;

			for ($i = 0, $n = count($this->user); $i < $n; $i++)
			{
				$row = $this->user [$i];
				$row->id = $row->user_id;

				$link = RedshopHelperUtility::getSSLLink(
					'index.php?option=com_redshop&view=user_detail&task=edit&user_id=' . $row->id . '&cid[]=' . $row->users_info_id
				);

				$iscompany = JText::_('COM_REDSHOP_USER_CUSTOMER');

				if ($row->is_company)
				{
					$iscompany = JText::_('COM_REDSHOP_USER_COMPANY');
				}

				$fisrt_name = '<a href="' . $link . '" title="' . JText::_('COM_REDSHOP_EDIT_USER') . '">' . $row->firstname . '</a>';
				$last_name = $row->lastname;
				?>
				<tr class="<?php echo "row$k"; ?>">
					<td align="center"><?php echo $this->pagination->getRowOffset($i);?></td>
					<td align="center"><?php echo JHTML::_('grid.id', $i, $row->users_info_id);?></td>
					<td><?php echo $fisrt_name;?></td>
					<td><?php echo $last_name;?> </td>
					<td align="center"><?php echo $iscompany?></td>
					<td><?php echo $row->username;?></td>
					<td>
						<?php
						$shoppergroup = Redshop\Helper\ShopperGroup::generateList($row->shopper_group_id);

						if (count($shoppergroup) > 0)
						{
							echo $shoppergroup[0]->text;
						}
						?>
					</td>
					<td align="center" class="nowrap">
						<?php
							$totalsales = RedshopHelperUser::totalSales($row->users_info_id);
							echo $producthelper->getProductFormattedPrice($totalsales);
						?>
					</td>
					<td align="center" width="5%"><?php echo $row->users_info_id;?></td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			<tfoot>
				<td colspan="11">
					<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
						<div class="redShopLimitBox">
							<?php echo $this->pagination->getLimitBox(); ?>
						</div>
					<?php endif; ?>
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tfoot>
		</table>
	</div>
	<input type="hidden" name="view" value="user"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists ['order']; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists ['order_Dir']; ?>"/>
	<input type="hidden" name="delete_joomla_users"/>
</form>
