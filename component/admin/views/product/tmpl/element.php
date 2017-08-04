<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$model       = $this->getModel('product');
$category_id = $this->state->get('category_id', 0);
$object      = JFactory::getApplication()->input->getRaw('object');
$action      = 'index.php?option=com_redshop&view=product&layout=element&tmpl=component&object=' . $object;
?>
<script language="javascript" type="text/javascript">

    Joomla.submitbutton = function (pressbutton) {
        var form = document.adminForm;
        if (pressbutton) {
            form.task.value = pressbutton;
        }
        try {
            form.onsubmit();
        }
        catch (e) {
        }

        form.submit();
    }
</script>
<form action="<?php echo $action ?>" method="post" name="adminForm" id="adminForm">
    <div class="filterItem">
        <div class="btn-wrapper input-append">
            <input type="text" name="keyword" value="<?php echo $this->keyword; ?>">
            <input type="submit" class="btn" value="<?php echo JText::_("COM_REDSHOP_SEARCH") ?>">
        </div>
    </div>
    <div class="filterItem">
        <select name="search_field" onchange="javascript:document.adminForm.submit();">
            <option value="p.product_name" <?php if ($this->search_field == 'p.product_name') echo "selected='selected'" ?>>
				<?php echo JText::_("COM_REDSHOP_PRODUCT_NAME") ?>
            </option>
            <option value="c.category_name" <?php if ($this->search_field == 'c.category_name') echo "selected='selected'" ?>>
				<?php echo JText::_("COM_REDSHOP_CATEGORY") ?>
            </option>
            <option value="p.product_number" <?php if ($this->search_field == 'p.product_number') echo "selected='selected'" ?>>
				<?php echo JText::_("COM_REDSHOP_PRODUCT_NUMBER") ?>
            </option>
        </select>
    </div>
    <div class="filterItem">
		<?php echo $this->lists['category']; ?>
    </div>

    <div id="editcell">
        <table class="adminlist table table-striped">
            <thead>
            <tr>
                <th width="5">
					<?php echo JText::_('COM_REDSHOP_NUM'); ?>
                </th>
                <th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NAME', 'product_name', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th class="title">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_PRODUCT_NUMBER', 'product_number', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
                <th>
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_NUMBER_OF_VIEWS', 'visited', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>

                <th>
					<?php echo JText::_('COM_REDSHOP_CATEGORY'); ?>
                </th>
                <th>
					<?php echo JText::_('COM_REDSHOP_MANUFACTURER'); ?>
                </th>
                <th width="5%" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ID', 'product_id', $this->lists['order_Dir'], $this->lists['order']); ?>
                </th>
				<?php if ($category_id > 0): ?>
                    <th nowrap="nowrap">
						<?php echo JHTML::_('grid.sort', 'COM_REDSHOP_ORDERING', 'x.ordering', $this->lists['order_Dir'], $this->lists['order']); ?>
                    </td>
				<?php endif; ?>
            </tr>
            </thead>
			<?php
			$k = 0;

			for ($i = 0, $n = count($this->products); $i < $n; $i++)
			{
				$row     = $this->products[$i];
				$row->id = $row->product_id;
				$link    = JRoute::_('index.php?option=com_redshop&view=product_detail&task=edit&cid[]=' . $row->product_id);

				$published = JHtml::_('jgrid.published', $row->published, $i, '', 1);

				?>
                <tr class="<?php echo "row$k"; ?>">
                    <td>
						<?php echo $this->pagination->getRowOffset($i) ?>
                    </td>
                    <td>
                        <a style="cursor: pointer;"
                           onclick="window.parent.jSelectProduct('<?php echo $row->product_id ?>', '<?php echo str_replace(array("'", "\""), array("\\'", ""), $row->product_name) ?>', '<?php echo $object ?>');">
							<?php echo $row->product_name; ?></a>
                    </td>
                    <td>
						<?php echo $row->product_number; ?>
                    </td>
                    <td align="center">
						<?php echo $row->visited; ?>
                    </td>

                    <td>
						<?php $listedincats = $model->listedincats($row->product_id);
						for ($j = 0, $jn = count($listedincats); $j < $jn; $j++)
						{
							echo $cat = $listedincats[$j]->category_name . "<br />";
						}
						?>
					</td>
					<td>
						<?php echo RedshopEntityManufacturer::getInstance($row->manufacturer_id)->get('manufacturer_name') ?>
					</td>
					<td align="center" width="5%">
						<?php echo $row->product_id; ?>
                    </td>
					<?php if ($category_id > 0): ?>
                        <td class="order">
							<?php echo $row->ordering; ?>
                        </td>
					<?php endif; ?>
                </tr>
				<?php
				$k = 1 - $k;
			}
			?>

            <tfoot>
            <td colspan="13">
				<?php if (version_compare(JVERSION, '3.0', '>=')): ?>
                    <div class="redShopLimitBox">
						<?php echo $this->pagination->getLimitBox() ?>
                    </div>
				<?php endif; ?>
				<?php echo $this->pagination->getListFooter() ?>
            </td>
            </tfoot>
        </table>
    </div>

    <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
