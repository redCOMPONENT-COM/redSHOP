<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

$ordering = ($this->lists['order'] == 'ordering');
?>
<form action="index.php?option=com_redshop&view=shipping" method="post" name="adminForm" id="adminForm">
    <table class="adminlist table table-striped">
        <thead>
        <tr>
            <th width="1">#</th>
            <th width="1">
				<?php echo JHtml::_('redshopgrid.checkall'); ?>
            </th>
            <th class="title">
				<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_SHIPPING_NAME', 'name ', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th class="title">
				<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_PLUGIN', 'element ', $this->lists['order_Dir'], $this->lists['order']); ?>

            </th>
            <th class="title" width="10%">
				<?php echo JText::_("COM_REDSHOP_VERSION") ?>
            </th>
            <th width="10%">
				<?php echo JText::_("COM_REDSHOP_SHIPPING_SUPPORT_RATE") ?>
            </th>
            <th width="10%">
				<?php echo JText::_("COM_REDSHOP_SHIPPING_SUPPORT_LOCATION") ?>
            </th>
            <th width="5%" nowrap="nowrap">
				<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_PUBLISHED', 'state', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
            <th width="5%" nowrap="nowrap">
				<?php echo JHtml::_('grid.sort', 'COM_REDSHOP_ID', 'extension_id', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
        </tr>
        </thead>
		<?php
		$k = 0;
		for ($i = 0, $n = count($this->shippings); $i < $n; $i++)
		{
			$row  = $this->shippings[$i];
			$link = JRoute::_('index.php?option=com_redshop&view=shipping_detail&task=edit&cid[]=' . $row->extension_id);

			$published = JHtml::_('jgrid.published', $row->enabled, $i, '', 1);
			$cache     = new Registry($row->manifest_cache);
			$params    = new Registry($row->params);
			?>
            <tr class="<?php echo "row$k"; ?>">
                <td align="center">
					<?php echo $this->pagination->getRowOffset($i); ?>
                </td>
                <td align="center">
					<?php echo JHtml::_('grid.id', $i, $row->extension_id); ?>
                </td>
                <td width="50%">
                    <a href="<?php echo $link; ?>"
                            title="<?php echo JText::_('COM_REDSHOP_EDIT_SHIPPING'); ?>">
						<?php echo JText::_($row->name); ?>
                    </a>
                </td>

                <td align="center">
					<?php echo $row->element; ?>
                </td>
                <td align="center">
					<?php echo $cache->get('version'); ?>
                </td>
                <td>
					<?php if ($params->get('is_shipper', 0) == 1): ?>
                        <i class="fa fa-check text-success"></i>
					<?php endif; ?>
                </td>
                <td>
					<?php if ($params->get('shipper_location', 0) == 1): ?>
                        <i class="fa fa-check text-success"></i>
					<?php endif; ?>
                </td>
                <td align="center" width="5%">
					<?php echo $published; ?>
                </td>
                <td align="center" width="5%">
					<?php echo $row->extension_id; ?>
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
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
</form>
