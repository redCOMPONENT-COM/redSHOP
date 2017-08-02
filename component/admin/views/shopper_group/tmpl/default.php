<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

?>
<script language="javascript" type="text/javascript">

    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;
        if (pressbutton) {
            form.task.value = pressbutton;
        }

        if (pressbutton == 'add' || pressbutton == 'edit' || pressbutton == 'remove') {
            if (pressbutton == 'remove' && !confirm('<?php echo JText::_("COM_REDSHOP_SHOPPER_GROUPS_DELETE_CONFIRM")?>')) {
                return;
            }

            form.view.value = "shopper_group_detail";
        }
        try {
            form.onsubmit();
        }
        catch (e) {
        }

        form.submit();
    }

</script>
<form action="<?php echo 'index.php?option=com_redshop'; ?>" method="post" name="adminForm" id="adminForm">
    <div id="editcell">
        <table class="adminlist table table-striped">
            <thead>
            <tr>
                <th width="5%">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
                </th>
                <th width="5%">
					<?php echo JHtml::_('redshopgrid.checkall'); ?>
                </th>
                <th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_SHOPPER_GROUP_NAME', 'shopper_group_name', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="15%" nowrap="nowrap">
					<?php echo JText::_('COM_REDSHOP_DISCOUNT'); ?>
                </th>
                <th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'published', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'shopper_group_id', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

            </tr>
            </thead>
			<?php
			$k = 0;

			for ($i = $this->pagination->limitstart, $j = 0, $n = count($this->media); $i < ($this->pagination->limitstart + $this->pagination->limit); $i++, $j++)
			{
				if (!isset($this->media[$i]))
				{
					break;
				}

				$row     = $this->media[$i];
				$row->id = $row->shopper_group_id;

				$link = JRoute::_('index.php?option=com_redshop&view=shopper_group_detail&task=edit&cid[]=' . $row->shopper_group_id);

				$published = JHTML::_('grid.published', $row, $j);

				$link_adddis = JRoute::_('index.php?option=com_redshop&view=discount&spgrpdis_filter=' . $row->shopper_group_id);

				?>
                <tr class="<?php echo "row$k"; ?>">
                    <td align="center">
						<?php echo $this->pagination->getRowOffset($j); ?>
                    </td>
                    <td align="center">
						<?php echo JHTML::_('grid.id', $j, $row->id); ?>
                    </td>
                    <td><a href="<?php echo $link; ?>"
                           title="<?php echo JText::_('COM_REDSHOP_EDIT_SHOPPER_GROUP'); ?>"><?php echo $row->shopper_group_name; ?></a>
                    <td align="center"><a
                                href="<?php echo $link_adddis; ?>"><?php echo JText::_('COM_REDSHOP_ADD_DISCOUNT'); ?></a>
                    </td>
                    <td align="center">
						<?php echo $published; ?>
                    </td>
                    <td align="center">
						<?php echo $row->shopper_group_id; ?>
                    </td>
                </tr>
				<?php
				$k = 1 - $k;
			}
			?>

            <tfoot>
            <td colspan="9">
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

    <input type="hidden" name="view" value="shopper_group"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
